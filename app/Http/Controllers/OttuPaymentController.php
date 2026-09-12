<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\Price;
use App\Models\UserCoin;
use App\Models\AppVersion;

class OttuPaymentController extends Controller
{
    /**
     * Generate Ottu Checkout Session
     * 
     * Endpoint: POST /api/ottu/generate-checkout
     */
    public function generateCheckout(Request $request)
    {
        $validated = $request->validate([
            'user_id'              => 'required',
            'amount'               => 'required|numeric|gt:0',
            'games_count'          => 'required|integer|min:0',
            'customer_first_name'  => 'required|string',
            'customer_last_name'   => 'required|string',
            'customer_phone'       => 'nullable|string',
            'customer_email'       => 'required|email',
        ]);

        $orderNo = 'ORD-' . time() . '-' . rand(1000, 9999);
        $ottuConfig = $this->getOttuCredentials();
        $apiKey = $ottuConfig['api_key'];
        $apiUrl = $ottuConfig['api_url'];
        $activeMode = $ottuConfig['mode'];

        Log::info("Ottu generateCheckout using [{$activeMode}] mode. URL: {$apiUrl}");

        // Resolve package details
        $packageType = $request->input('package_type', 'games');
        $priceId = $request->input('price_id');
        $packageTitle = $request->input('package_title');
        $coinsCount = (int)($request->input('coins_count') ?? 0);
        $gameCoinId = $request->input('game_coin_id');

        if (empty($packageTitle) && $priceId) {
            $priceObj = Price::find($priceId);
            if ($priceObj) {
                $packageTitle = $priceObj->title;
                if ($priceObj->coins_number && $coinsCount <= 0) {
                    $coinsCount = $priceObj->coins_number;
                }
                if ($priceObj->game_coin_id && empty($gameCoinId)) {
                    $gameCoinId = $priceObj->game_coin_id;
                }
            }
        }

        if (empty($packageTitle)) {
            $packageTitle = $validated['games_count'] > 0 
                ? "باقة {$validated['games_count']} ألعاب" 
                : ($coinsCount > 0 ? "باقة {$coinsCount} عملة" : 'باقة شراء');
        }

        // Resolve payment gateway codes (pg_codes)
        $pgCodesInput = $request->input('pg_codes');
        if (!empty($pgCodesInput)) {
            $pgCodes = is_array($pgCodesInput) ? $pgCodesInput : explode(',', $pgCodesInput);
        } else {
            $pgCodes = $ottuConfig['pg_codes'];
        }
        $pgCodes = array_values(array_unique(array_filter(array_map('trim', (array)$pgCodes))));
        if (empty($pgCodes)) {
            $pgCodes = ['knet'];
        }

        // Prepare Ottu payload
        $payload = [
            'type'                => 'e_commerce',
            'pg_codes'            => $pgCodes,
            'amount'              => number_format((float)$validated['amount'], 3, '.', ''),
            'currency_code'       => 'KWD',
            'disclosure_url'      => url('/api/ottu/redirect'),
            'redirect_url'        => url('/api/ottu/redirect'),
            'webhook_url'         => url('/api/ottu/webhook'),
            'customer_first_name' => $validated['customer_first_name'],
            'customer_last_name'  => $validated['customer_last_name'],
            'customer_phone'      => !empty($validated['customer_phone']) ? $validated['customer_phone'] : '96512345678',
            'customer_email'      => $validated['customer_email'],
            'order_no'            => $orderNo,
            'extra'               => [
                'user_id'       => $validated['user_id'],
                'games_count'   => $validated['games_count'],
                'package_type'  => $packageType,
                'price_id'      => $priceId,
                'package_title' => $packageTitle,
                'coins_count'   => $coinsCount,
                'game_coin_id'  => $gameCoinId,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $sessionId = $data['session_id'] ?? '';

                Log::info('Ottu Checkout Session Created', ['order_no' => $orderNo, 'session_id' => $sessionId]);

                // Create transaction in database
                PaymentTransaction::create([
                    'user_id'          => $validated['user_id'],
                    'order_no'         => $orderNo,
                    'session_id'       => $sessionId,
                    'package_type'     => $packageType,
                    'price_id'         => $priceId,
                    'package_title'    => $packageTitle,
                    'games_count'      => $validated['games_count'],
                    'coins_count'      => $coinsCount,
                    'game_coin_id'     => $gameCoinId,
                    'amount'           => $validated['amount'],
                    'currency'         => 'KWD',
                    'pg_code'          => $pgCodes[0] ?? 'knet',
                    'status'           => 'pending',
                    'customer_name'    => trim($validated['customer_first_name'] . ' ' . $validated['customer_last_name']),
                    'customer_email'   => $validated['customer_email'],
                    'customer_phone'   => $validated['customer_phone'] ?? null,
                    'gateway_response' => json_encode($data),
                ]);

                return response()->json([
                    'status'       => 'success',
                    'checkout_url' => $data['checkout_url'] ?? '',
                    'session_id'   => $sessionId,
                    'order_no'     => $orderNo,
                    'message'      => 'Checkout created successfully',
                ]);
            }

            Log::error('Ottu Checkout Error', ['response' => $response->body()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to initialize payment with gateway: ' . $response->body(),
            ], 400);

        } catch (\Exception $e) {
            Log::error('Ottu Exception', ['error' => $e->getMessage()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Server error initiating payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook callback from Ottu
     * 
     * Endpoint: POST /api/ottu/webhook
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Ottu Webhook Received', $request->all());

        $payload = $request->all();
        $state = strtolower($payload['state'] ?? ($payload['status'] ?? ''));
        $sessionId = $payload['session_id'] ?? ($payload['id'] ?? null);
        $orderNo = $payload['order_no'] ?? null;
        $extra = $payload['extra'] ?? [];
        $userId = $extra['user_id'] ?? null;
        $gamesCount = (int)($extra['games_count'] ?? 0);
        $coinsCount = (int)($extra['coins_count'] ?? 0);
        $gameCoinId = $extra['game_coin_id'] ?? null;
        $isPaid = in_array($state, ['paid', 'captured', 'success', 'completed', 'approved', 'processed']);

        // Find transaction
        $transaction = null;
        if ($sessionId || $orderNo) {
            $transaction = PaymentTransaction::where(function($q) use ($sessionId, $orderNo) {
                if ($sessionId) $q->where('session_id', $sessionId);
                if ($orderNo) $q->orWhere('order_no', $orderNo);
            })->first();
        }

        // Extract actual pg_code
        $actualPgCode = null;
        if (!empty($payload['payment_methods']) && is_array($payload['payment_methods'])) {
            foreach ($payload['payment_methods'] as $pm) {
                $pmState = strtolower($pm['state'] ?? ($pm['status'] ?? ''));
                if (in_array($pmState, ['paid', 'captured', 'success', 'completed', 'approved', 'processed'])) {
                    $actualPgCode = $pm['code'] ?? ($pm['name'] ?? null);
                    break;
                }
            }
            if (!$actualPgCode && !empty($payload['payment_methods'][0]['code'])) {
                $actualPgCode = $payload['payment_methods'][0]['code'];
            }
        }

        $user = null;
        // Process successful payment
        if ($isPaid) {
            if ($userId && $sessionId) {
                $processedCacheKey = 'ottu_session_processed_' . $sessionId;
                if (!Cache::has($processedCacheKey)) {
                    $user = User::find($userId);
                    if ($user) {
                        if ($gamesCount > 0) {
                            $user->number_of_games = ($user->number_of_games ?? 0) + $gamesCount;
                            $user->save();
                        }
                        if ($coinsCount > 0 && $gameCoinId) {
                            UserCoin::create([
                                'user_id'      => $user->id,
                                'game_coin_id' => $gameCoinId,
                                'coins_number' => $coinsCount,
                                'type'         => 'add',
                            ]);
                        }
                        Cache::put($processedCacheKey, true, now()->addDays(7));
                        Log::info("Updated user #{$userId} purchases via webhook");
                    }
                }
            }

            if ($transaction) {
                $updateData = [
                    'status'           => 'paid',
                    'paid_at'          => now(),
                    'gateway_response' => json_encode($payload),
                ];
                if ($actualPgCode) {
                    $updateData['pg_code'] = $actualPgCode;
                }
                $transaction->update($updateData);
            }

            // Send branded invoice email upon successful payment
            $this->sendInvoiceEmail($payload, $user, $transaction);
        } else {
            if ($transaction && !in_array($transaction->status, ['paid', 'success', 'captured'])) {
                $newStatus = 'failed';
                if (in_array($state, ['created', 'pending', 'initiated', 'open'])) {
                    $newStatus = 'pending';
                } elseif (in_array($state, ['cancelled', 'canceled'])) {
                    $newStatus = 'cancelled';
                } elseif ($state === 'expired') {
                    $newStatus = 'expired';
                }

                $updateData = [
                    'status'           => $newStatus,
                    'gateway_response' => json_encode($payload),
                ];
                if ($actualPgCode) {
                    $updateData['pg_code'] = $actualPgCode;
                }
                $transaction->update($updateData);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Check Status of Session
     * 
     * Endpoint: GET /api/ottu/check-status/{sessionId}
     */
    public function checkStatus($sessionId)
    {
        $ottuConfig = $this->getOttuCredentials();
        $apiKey = $ottuConfig['api_key'];
        $baseUrl = rtrim($ottuConfig['api_url'], '/');
        $apiUrl = $baseUrl . '/' . $sessionId . '/';
        $activeMode = $ottuConfig['mode'];

        Log::info("Ottu checkStatus for {$sessionId} using [{$activeMode}] mode. URL: {$apiUrl}");

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $apiKey,
            ])->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Ottu checkStatus response for {$sessionId}:", $data ?? []);

                $state = strtolower($data['state'] ?? ($data['status'] ?? 'pending'));
                $orderNo = $data['order_no'] ?? null;
                $isPaid = in_array($state, ['paid', 'captured', 'success', 'completed', 'processed', 'approved']);

                $actualPgCode = null;
                if (!empty($data['payment_methods']) && is_array($data['payment_methods'])) {
                    foreach ($data['payment_methods'] as $pm) {
                        $pmState = strtolower($pm['state'] ?? ($pm['status'] ?? ''));
                        if (in_array($pmState, ['paid', 'captured', 'success', 'completed', 'approved', 'processed'])) {
                            $isPaid = true;
                            $state = $pmState;
                            $actualPgCode = $pm['code'] ?? ($pm['name'] ?? null);
                            break;
                        }
                    }
                    if (!$actualPgCode && !empty($data['payment_methods'][0]['code'])) {
                        $actualPgCode = $data['payment_methods'][0]['code'];
                    }
                }

                // Find transaction
                $transaction = PaymentTransaction::where('session_id', $sessionId)
                    ->orWhere('order_no', $orderNo)
                    ->first();

                $currentNumberOfGames = null;
                $user = null;
                if ($isPaid) {
                    $extra = $data['extra'] ?? [];
                    $userId = $extra['user_id'] ?? ($transaction->user_id ?? null);
                    $gamesCount = (int)($extra['games_count'] ?? ($transaction->games_count ?? 0));
                    $coinsCount = (int)($extra['coins_count'] ?? ($transaction->coins_count ?? 0));
                    $gameCoinId = $extra['game_coin_id'] ?? ($transaction->game_coin_id ?? null);

                    if ($userId) {
                        $user = User::find($userId);
                        if ($user) {
                            $processedCacheKey = 'ottu_session_processed_' . $sessionId;
                            if (!Cache::has($processedCacheKey)) {
                                if ($gamesCount > 0) {
                                    $user->number_of_games = ($user->number_of_games ?? 0) + $gamesCount;
                                    $user->save();
                                }
                                if ($coinsCount > 0 && $gameCoinId) {
                                    UserCoin::create([
                                        'user_id'      => $user->id,
                                        'game_coin_id' => $gameCoinId,
                                        'coins_number' => $coinsCount,
                                        'type'         => 'add',
                                    ]);
                                }
                                Cache::put($processedCacheKey, true, now()->addDays(7));
                                Log::info("Updated user #{$userId} purchases via checkStatus");
                            }
                            $currentNumberOfGames = $user->number_of_games;
                        }
                    }

                    if ($transaction) {
                        $updateData = [
                            'status'           => 'paid',
                            'paid_at'          => now(),
                            'gateway_response' => json_encode($data),
                        ];
                        if ($actualPgCode) {
                            $updateData['pg_code'] = $actualPgCode;
                        }
                        $transaction->update($updateData);
                    }

                    // Send branded invoice email upon successful payment
                    $this->sendInvoiceEmail($data, $user, $transaction);
                } else {
                    if ($transaction && !in_array($transaction->status, ['paid', 'success', 'captured'])) {
                        $newStatus = 'failed';
                        if (in_array($state, ['created', 'pending', 'initiated', 'open'])) {
                            $newStatus = 'pending';
                        } elseif (in_array($state, ['cancelled', 'canceled'])) {
                            $newStatus = 'cancelled';
                        } elseif ($state === 'expired') {
                            $newStatus = 'expired';
                        }

                        $updateData = [
                            'status'           => $newStatus,
                            'gateway_response' => json_encode($data),
                        ];
                        if ($actualPgCode) {
                            $updateData['pg_code'] = $actualPgCode;
                        }
                        $transaction->update($updateData);
                    }
                }

                return response()->json([
                    'status'          => $state,
                    'paid'            => $isPaid,
                    'number_of_games' => $currentNumberOfGames,
                    'session_id'      => $sessionId,
                    'order_no'        => $data['order_no'] ?? ($transaction->order_no ?? ''),
                    'message'         => $isPaid ? 'Payment successful' : 'Payment not completed',
                ]);
            }

            return response()->json([
                'status'  => 'failed',
                'paid'    => false,
                'message' => 'Could not fetch status from Ottu',
            ]);
        } catch (\Exception $e) {
            Log::error('Ottu checkStatus exception: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'paid'    => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Web Redirect Handler
     * 
     * Endpoint: GET /api/ottu/redirect
     */
    public function handleRedirect(Request $request)
    {
        Log::info('Ottu Redirect Received', $request->all());
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>العودة للتطبيق</title>
</head>
<body style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:80vh;font-family:sans-serif;text-align:center;">
    <h3 style="color:#2c3e50;">تمت معالجة عملية الدفع</h3>
    <p style="color:#7f8c8d;">جاري العودة إلى تطبيق فيك تحدي...</p>
    <script>
        setTimeout(function() {
            window.location.href = "fiktahadiapp://payment-callback";
        }, 100);
    </script>
</body>
</html>';

        return response($html);
    }

    /**
     * Send Invoice Email via Brevo with multi-tier failover
     * 
     * @param array $data Payment and transaction data
     * @param User|null $user
     * @param PaymentTransaction|null $transaction
     * @return bool
     */
    protected function sendInvoiceEmail(array $data, $user = null, $transaction = null)
    {
        $sessionId = $data['session_id'] ?? ($data['id'] ?? ($transaction->session_id ?? null));
        if (!$sessionId) {
            return false;
        }

        $invoiceCacheKey = 'ottu_invoice_sent_' . $sessionId;
        if (Cache::has($invoiceCacheKey)) {
            Log::info("Ottu invoice already sent for session: {$sessionId}");
            return true;
        }

        try {
            // Extract & sanitize data
            $extra = $data['extra'] ?? [];
            $userId = $extra['user_id'] ?? ($data['user_id'] ?? ($transaction->user_id ?? ($user->id ?? null)));
            $gamesCount = (int)($extra['games_count'] ?? ($data['games_count'] ?? ($transaction->games_count ?? 0)));
            $coinsCount = (int)($extra['coins_count'] ?? ($transaction->coins_count ?? 0));
            $packageTitle = $extra['package_title'] ?? ($transaction->package_title ?? null);

            if (!$user && $userId) {
                $user = User::find($userId);
            }

            $customerEmail = !empty($data['customer_email']) ? trim($data['customer_email']) : ($transaction->customer_email ?? ($user->email ?? null));
            if (!$customerEmail) {
                Log::warning("Cannot send invoice: No email found for session {$sessionId}");
                return false;
            }

            $firstName = $data['customer_first_name'] ?? '';
            $lastName = $data['customer_last_name'] ?? '';
            $customerName = trim($firstName . ' ' . $lastName);
            if (empty($customerName)) {
                $customerName = $transaction->customer_name ?? ($user->name ?? ($user->user_name ?? 'العميل المميز'));
            }

            $customerPhone = $data['customer_phone'] ?? ($transaction->customer_phone ?? ($user->phone ?? ($user->mobile ?? '-')));
            $amountVal = (float)($data['amount'] ?? ($transaction->amount ?? 0));
            $amountFormatted = number_format($amountVal, 3, '.', '');
            $currencyCode = $data['currency_code'] ?? ($transaction->currency ?? 'KWD');
            $orderNo = $data['order_no'] ?? ($transaction->order_no ?? ('ORD-' . time()));
            $invoiceDate = date('Y-m-d');
            $paymentTime = date('Y-m-d H:i:s');

            // Detect payment method (Arabic display)
            $paymentMethod = 'كي نت (KNET)';
            if (!empty($data['payment_methods']) && is_array($data['payment_methods'])) {
                $pmName = $data['payment_methods'][0]['name'] ?? ($data['payment_methods'][0]['code'] ?? '');
                if (!empty($pmName)) {
                    $pmLower = strtolower($pmName);
                    if (str_contains($pmLower, 'knet') || str_contains($pmLower, 'kpay')) {
                        $paymentMethod = 'كي نت (KNET)';
                    } elseif (str_contains($pmLower, 'apple')) {
                        $paymentMethod = 'أبل باي (Apple Pay)';
                    } elseif (str_contains($pmLower, 'card') || str_contains($pmLower, 'visa') || str_contains($pmLower, 'master') || str_contains($pmLower, 'mpgs') || str_contains($pmLower, 'alhil')) {
                        $paymentMethod = 'بطاقة بنكية (Credit/Debit Card)';
                    } else {
                        $paymentMethod = strtoupper($pmName);
                    }
                }
            }

            if (!empty($packageTitle)) {
                $description = "باقة {$packageTitle} في تطبيق فيك تحدي";
            } elseif ($gamesCount > 0) {
                $description = "باقة تتيح لك إنشاء والمشاركة في عدد ({$gamesCount}) ألعاب في تطبيق فيك تحدي";
            } elseif ($coinsCount > 0) {
                $description = "باقة شحن عدد ({$coinsCount}) من العملات في تطبيق فيك تحدي";
            } else {
                $description = "شراء وتفعيل رصيد ألعاب في تطبيق فيك تحدي";
            }

            $htmlContent = $this->buildInvoiceHtml([
                'order_no'        => $orderNo,
                'session_id'      => $sessionId,
                'invoice_date'    => $invoiceDate,
                'payment_time'    => $paymentTime,
                'amount'          => $amountFormatted,
                'currency_code'   => $currencyCode,
                'payment_method'  => $paymentMethod,
                'description'     => $description,
                'package_title'   => $packageTitle,
                'games_count'     => $gamesCount,
                'customer_name'   => $customerName,
                'customer_phone'  => $customerPhone,
                'customer_email'  => $customerEmail,
                'user_id'         => $userId ?? '-',
            ]);

            $subject = "فاتورة تأكيد الدفع الإلكتروني #{$orderNo} - تطبيق فيك تحدي";
            $fromAddress = config('ottu.mail.from_address', 'no-reply@fiktahadi.com');
            $fromName = config('ottu.mail.from_name', 'فيك تحدي');

            $isSent = false;

            // 1. Primary Method: Direct Brevo SMTP Socket Connection
            try {
                $smtpHost = config('ottu.mail.brevo_smtp_host', env('BREVO_SMTP_HOST', 'smtp-relay.brevo.com'));
                $smtpPort = (int)config('ottu.mail.brevo_smtp_port', env('BREVO_SMTP_PORT', 587));
                $smtpUser = config('ottu.mail.brevo_smtp_username', env('BREVO_SMTP_USERNAME', 'aebc32001@smtp-brevo.com'));
                $smtpPass = config('ottu.mail.brevo_smtp_password', env('BREVO_SMTP_PASSWORD', ''));

                $this->sendViaSmtpSocket($customerEmail, $customerName, $subject, $htmlContent, $fromAddress, $fromName, $smtpHost, $smtpPort, $smtpUser, $smtpPass);
                $isSent = true;
                Log::info("✅ Invoice email sent successfully via Brevo SMTP to {$customerEmail} for session {$sessionId}");
            } catch (\Exception $brevoEx) {
                Log::warning("⚠️ Brevo SMTP failed: {$brevoEx->getMessage()}. Trying Laravel default mailer...");
            }

            // 2. Backup 1: Laravel Default Mailer
            if (!$isSent) {
                try {
                    Mail::html($htmlContent, function ($msg) use ($customerEmail, $customerName, $subject, $fromAddress, $fromName) {
                        $msg->to($customerEmail, $customerName)
                            ->subject($subject)
                            ->from($fromAddress, $fromName);
                    });
                    $isSent = true;
                    Log::info("✅ Invoice email sent via Laravel Default Mailer to {$customerEmail}");
                } catch (\Exception $mailEx) {
                    Log::warning("⚠️ Laravel default mailer failed: {$mailEx->getMessage()}. Trying PHP native mail()...");
                }
            }

            // 3. Backup 2: PHP Native mail()
            if (!$isSent) {
                try {
                    $headers  = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                    $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromAddress}>\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

                    $encodedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
                    if (@mail($customerEmail, $encodedSubject, $htmlContent, $headers)) {
                        $isSent = true;
                        Log::info("✅ Invoice email sent via native PHP mail() to {$customerEmail}");
                    }
                } catch (\Exception $phpMailEx) {
                    Log::error("❌ Native PHP mail() failed: " . $phpMailEx->getMessage());
                }
            }

            if ($isSent) {
                Cache::put($invoiceCacheKey, true, now()->addDays(30));
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("❌ Error in sendInvoiceEmail: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email via direct SMTP Socket with TLS encryption
     */
    protected function sendViaSmtpSocket($to, $toName, $subject, $htmlBody, $from, $fromName, $host, $port, $user, $pass)
    {
        $socket = @fsockopen($host, $port, $errno, $errstr, 8);
        if (!$socket) {
            throw new \Exception("Could not connect to SMTP host: {$errstr} ({$errno})");
        }

        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '220') {
            fclose($socket);
            throw new \Exception("Unexpected greeting: {$res}");
        }

        fputs($socket, "EHLO fiktahadi.com\r\n");
        while ($line = fgets($socket, 515)) {
            if (substr($line, 3, 1) == ' ') break;
        }

        fputs($socket, "STARTTLS\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '220') {
            fclose($socket);
            throw new \Exception("STARTTLS failed: {$res}");
        }

        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($socket);
            throw new \Exception("TLS encryption negotiation failed");
        }

        fputs($socket, "EHLO fiktahadi.com\r\n");
        while ($line = fgets($socket, 515)) {
            if (substr($line, 3, 1) == ' ') break;
        }

        fputs($socket, "AUTH LOGIN\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '334') {
            fclose($socket);
            throw new \Exception("AUTH LOGIN failed: {$res}");
        }

        fputs($socket, base64_encode($user) . "\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '334') {
            fclose($socket);
            throw new \Exception("Username rejected: {$res}");
        }

        fputs($socket, base64_encode($pass) . "\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '235') {
            fclose($socket);
            throw new \Exception("Password authentication rejected: {$res}");
        }

        fputs($socket, "MAIL FROM: <{$from}>\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '250') {
            fclose($socket);
            throw new \Exception("MAIL FROM rejected: {$res}");
        }

        fputs($socket, "RCPT TO: <{$to}>\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '250' && substr($res, 0, 3) != '251') {
            fclose($socket);
            throw new \Exception("RCPT TO rejected: {$res}");
        }

        fputs($socket, "DATA\r\n");
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '354') {
            fclose($socket);
            throw new \Exception("DATA rejected: {$res}");
        }

        $boundary = "----=_NextPart_" . md5(time() . rand(1000, 9999));
        $encodedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $encodedFromName = "=?UTF-8?B?" . base64_encode($fromName) . "?=";
        $encodedToName = "=?UTF-8?B?" . base64_encode($toName) . "?=";

        $headers = [];
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "From: {$encodedFromName} <{$from}>";
        $headers[] = "To: {$encodedToName} <{$to}>";
        $headers[] = "Date: " . date('r');
        $headers[] = "Subject: {$encodedSubject}";
        $headers[] = "X-Mailer: Fiktahadi-Payment-Mailer/1.0";
        $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";

        $body = implode("\r\n", $headers) . "\r\n\r\n";
        
        // Plain text part
        $plainText = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\n", $htmlBody));
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($plainText)) . "\r\n";

        // HTML part
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($htmlBody)) . "\r\n";

        $body .= "--{$boundary}--\r\n";
        $body .= ".\r\n";

        fputs($socket, $body);
        $res = fgets($socket, 515);
        if (substr($res, 0, 3) != '250') {
            fclose($socket);
            throw new \Exception("Sending body failed: {$res}");
        }

        fputs($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }

    /**
     * Build Invoice HTML Template with Pure Arabic Visual Identity
     */
    protected function buildInvoiceHtml(array $inv)
    {
        $logoUrl = 'https://fiktahadi.com/backend/assets/images/login-images/logo_yalla.png';
        $orderNo = htmlspecialchars($inv['order_no'] ?? '');
        $sessionId = htmlspecialchars($inv['session_id'] ?? '');
        $invoiceDate = htmlspecialchars($inv['invoice_date'] ?? date('Y-m-d'));
        $paymentTime = htmlspecialchars($inv['payment_time'] ?? date('Y-m-d H:i:s'));
        $amount = htmlspecialchars($inv['amount'] ?? '0.000');
        $currencyCode = htmlspecialchars($inv['currency_code'] ?? 'KWD');
        $paymentMethod = htmlspecialchars($inv['payment_method'] ?? 'كي نت (KNET)');
        $description = htmlspecialchars($inv['description'] ?? 'باقة شراء رصيد في تطبيق فيك تحدي');
        $customerName = htmlspecialchars($inv['customer_name'] ?? 'العميل المميز');
        $customerPhone = htmlspecialchars($inv['customer_phone'] ?? '-');
        $customerEmail = htmlspecialchars($inv['customer_email'] ?? '-');
        $userId = htmlspecialchars((string)($inv['user_id'] ?? '-'));
        $gamesCount = (int)($inv['games_count'] ?? 0);
        $packageTitle = htmlspecialchars($inv['package_title'] ?? '');

        if (empty($packageTitle)) {
            if ($gamesCount > 0) {
                $packageTitle = "باقة ({$gamesCount}) ألعاب فيك تحدي";
            } else {
                $packageTitle = "باقة رصيد ألعاب فيك تحدي";
            }
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة تأكيد الدفع - فيك تحدي</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0A1128; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1E293B;">
    <!-- Outer Background Wrapper -->
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0A1128; padding: 35px 12px;">
        <tr>
            <td align="center">
                <!-- Main Container Card -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #FFFFFF; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.4); border: 1px solid #1E293B;">
                    
                    <!-- Header Section: Deep Royal Blue Gradient with Golden Accent -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0A192F 0%, #102A56 50%, #1A365D 100%); padding: 36px 20px 30px 20px; border-bottom: 4px solid #F59E0B;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; padding: 6px; background-color: rgba(255, 255, 255, 0.08); border: 2px solid rgba(245, 158, 11, 0.45); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.35);">
                                            <img src="{$logoUrl}" alt="فيك تحدي" width="76" height="76" style="display: block; border-radius: 14px;">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 14px;">
                                        <h1 style="margin: 0; font-size: 26px; font-weight: 900; color: #FFFFFF; letter-spacing: -0.5px;">فيك تحدي</h1>
                                        <div style="margin: 6px 0 0 0; font-size: 13px; color: #FBBF24; font-weight: 700; letter-spacing: 0.2px;">المعرفة متعة والتحدي عندنا</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 16px;">
                                        <div style="background-color: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.25); color: #FFFFFF; font-size: 12px; font-weight: 800; padding: 6px 20px; border-radius: 24px; display: inline-block;">
                                            🧾 فاتورة دفع إلكترونية معتمدة
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 28px 24px 20px 24px;">
                            
                            <!-- Invoice Meta 2-Column Balanced Table -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 20px; table-layout: fixed;">
                                <tr>
                                    <!-- Right Column: Order Details -->
                                    <td align="right" width="48%" style="vertical-align: top; background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
                                        <div style="font-size: 11px; font-weight: 700; color: #64748B; margin-bottom: 3px;">رقم الفاتورة</div>
                                        <div style="font-size: 13px; font-weight: 800; color: #0F172A; font-family: monospace; word-break: break-all;">#{$orderNo}</div>
                                        
                                        <div style="font-size: 11px; font-weight: 700; color: #64748B; margin-top: 12px; margin-bottom: 3px;">تاريخ العملية</div>
                                        <div style="font-size: 12px; font-weight: 700; color: #334155; direction: ltr; text-align: right;">{$paymentTime}</div>
                                    </td>
                                    
                                    <!-- Spacer -->
                                    <td width="4%" style="width: 4%;"></td>
                                    
                                    <!-- Left Column: Payment Status & Method -->
                                    <td align="right" width="48%" style="vertical-align: top; background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 14px 16px;">
                                        <div style="font-size: 11px; font-weight: 700; color: #64748B; margin-bottom: 4px;">حالة الدفع</div>
                                        <div style="background-color: #DCFCE7; border: 1px solid #86EFAC; color: #15803D; font-weight: 800; font-size: 11px; padding: 4px 12px; border-radius: 20px; display: inline-block;">
                                            ✓ مدفوع بنجاح
                                        </div>
                                        
                                        <div style="font-size: 11px; font-weight: 700; color: #64748B; margin-top: 12px; margin-bottom: 3px;">طريقة الدفع</div>
                                        <div style="font-size: 12px; font-weight: 800; color: #1E3A8A;">💳 {$paymentMethod}</div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Total Paid Highlight Banner -->
                            <div style="background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%); border: 1px solid #BAE6FD; border-radius: 16px; padding: 18px 20px; margin-bottom: 20px; text-align: center;">
                                <div style="font-size: 12px; font-weight: 800; color: #0369A1; margin-bottom: 2px;">المبلغ الإجمالي المدفوع</div>
                                <div style="font-size: 30px; font-weight: 900; color: #0C4A6E; line-height: 1.2;">
                                    {$amount} <span style="font-size: 16px; font-weight: 800; color: #D97706;">د.ك ({$currencyCode})</span>
                                </div>
                            </div>

                            <!-- Purchased Package Card -->
                            <div style="background-color: #FFFFFF; border: 1px solid #E2E8F0; border-right: 5px solid #1E3A8A; border-radius: 12px; padding: 16px 18px; margin-bottom: 20px;">
                                <div style="font-size: 11px; font-weight: 800; color: #64748B; margin-bottom: 4px;">تفاصيل الطلب</div>
                                <div style="font-size: 15px; font-weight: 800; color: #0F172A; margin-bottom: 3px;">{$packageTitle}</div>
                                <div style="font-size: 12px; color: #475569; line-height: 1.5;">{$description}</div>
                            </div>

                            <!-- Customer Information Section -->
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 16px 18px; margin-bottom: 20px;">
                                <div style="font-size: 12px; font-weight: 800; color: #1E3A8A; border-bottom: 1px dashed #CBD5E1; padding-bottom: 8px; margin-bottom: 12px;">
                                    بيانات المشترك
                                </div>
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
                                    <tr>
                                        <td align="right" width="50%" style="padding: 4px 0; vertical-align: top;">
                                            <div style="font-size: 11px; color: #64748B; font-weight: 600;">اسم المشترك</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0F172A; margin-top: 2px;">{$customerName}</div>
                                        </td>
                                        <td align="right" width="50%" style="padding: 4px 0; vertical-align: top;">
                                            <div style="font-size: 11px; color: #64748B; font-weight: 600;">البريد الإلكتروني</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #1E3A8A; margin-top: 2px; direction: ltr; text-align: right; word-break: break-all;">{$customerEmail}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right" width="50%" style="padding: 10px 0 0 0; vertical-align: top;">
                                            <div style="font-size: 11px; color: #64748B; font-weight: 600;">رقم الهاتف</div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0F172A; margin-top: 2px; direction: ltr; text-align: right;">{$customerPhone}</div>
                                        </td>
                                        <td align="right" width="50%" style="padding: 10px 0 0 0; vertical-align: top;">
                                            <div style="font-size: 11px; color: #64748B; font-weight: 600;">معرّف الحساب</div>
                                            <div style="font-size: 13px; font-weight: 800; color: #D97706; margin-top: 2px; font-family: monospace;">#{$userId}</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Financial Breakdown -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
                                <tr>
                                    <td align="right" style="padding: 9px 0; font-size: 13px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">المجموع الفرعي</td>
                                    <td align="left" style="padding: 9px 0; font-size: 13px; font-weight: 700; color: #0F172A; border-bottom: 1px solid #E2E8F0; direction: ltr; text-align: left;">{$amount} {$currencyCode}</td>
                                </tr>
                                <tr>
                                    <td align="right" style="padding: 9px 0; font-size: 13px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">الخصم</td>
                                    <td align="left" style="padding: 9px 0; font-size: 13px; font-weight: 700; color: #10B981; border-bottom: 1px solid #E2E8F0; direction: ltr; text-align: left;">0.000 {$currencyCode}</td>
                                </tr>
                                <tr>
                                    <td align="right" style="padding: 12px 0; font-size: 14px; font-weight: 800; color: #1E3A8A; border-bottom: 2px solid #1E3A8A;">الإجمالي الكلي المدفوع</td>
                                    <td align="left" style="padding: 12px 0; font-size: 17px; font-weight: 900; color: #1E3A8A; border-bottom: 2px solid #1E3A8A; direction: ltr; text-align: left;">{$amount} <span style="font-size: 12px; color: #D97706;">{$currencyCode}</span></td>
                                </tr>
                            </table>

                            <!-- Transaction ID Reference Box -->
                            <div style="background-color: #F1F5F9; border-radius: 10px; padding: 12px 14px; margin-bottom: 20px; font-size: 11px; color: #475569;">
                                <div>
                                    <strong style="color: #0F172A;">رقم المعاملة:</strong> 
                                    <span style="font-family: monospace; color: #1E3A8A; margin-right: 4px;">{$sessionId}</span>
                                </div>
                                <div style="margin-top: 4px;">
                                    <strong style="color: #0F172A;">الرقم المرجعي للطلب:</strong> 
                                    <span style="font-family: monospace; color: #1E3A8A; margin-right: 4px;">{$orderNo}</span>
                                </div>
                            </div>

                            <!-- Thank You Message -->
                            <div style="text-align: center; padding: 10px 0;">
                                <div style="font-size: 15px; font-weight: 800; color: #1E3A8A;">شكراً لاختيارك تطبيق فيك تحدي! 🎉</div>
                                <div style="font-size: 12px; color: #64748B; margin-top: 4px; line-height: 1.5;">تمت إضافة الرصيد لحسابك بنجاح، نتمنى لك وقتاً ممتعاً في المنافسة والتحدي!</div>
                            </div>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #F8FAFC; padding: 22px 24px; border-top: 1px solid #E2E8F0; font-size: 11px; color: #64748B;">
                            <div style="color: #334155; font-weight: 600; margin-bottom: 4px;">لأي استفسار يمكنك التواصل مع فريق الدعم الفني مباشرة عبر التطبيق</div>
                            <div style="color: #94A3B8; font-size: 10px; margin-top: 4px;">جميع الحقوق محفوظة © 2026 تطبيق فيك تحدي</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    /**
     * Get active Ottu payment gateway credentials based on CPanel settings (AppVersion)
     * 
     * @return array
     */
    public function getOttuCredentials()
    {
        try {
            $appVersion = AppVersion::first();
            $mode = $appVersion->payment_mode ?? env('OTTU_PAYMENT_MODE', 'sandbox');
            
            if ($mode === 'live') {
                $apiKey = !empty($appVersion->ottu_live_api_key) ? $appVersion->ottu_live_api_key : config('ottu.api_key', 'KSK2Iuqw.mowuSwOTIq6ZDT48FvQvW0GaaQPwFjIy');
                $apiUrl = !empty($appVersion->ottu_live_api_url) ? $appVersion->ottu_live_api_url : config('ottu.api_url', 'https://pay.pikw.com/b/checkout/v1/pymt-txn/');
                $pgCodesStr = !empty($appVersion->ottu_live_pg_codes) ? $appVersion->ottu_live_pg_codes : config('ottu.pg_codes', 'knet');
            } else {
                $apiKey = !empty($appVersion->ottu_sandbox_api_key) ? $appVersion->ottu_sandbox_api_key : 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL';
                $apiUrl = !empty($appVersion->ottu_sandbox_api_url) ? $appVersion->ottu_sandbox_api_url : 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/';
                $pgCodesStr = !empty($appVersion->ottu_sandbox_pg_codes) ? $appVersion->ottu_sandbox_pg_codes : 'knet';
            }

            $pgCodes = array_values(array_unique(array_filter(array_map('trim', explode(',', (string)$pgCodesStr)))));
            if (empty($pgCodes)) {
                $pgCodes = ['knet'];
            }

            return [
                'mode'     => $mode,
                'api_key'  => $apiKey,
                'api_url'  => $apiUrl,
                'pg_codes' => $pgCodes,
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching Ottu credentials from AppVersion: ' . $e->getMessage());
            return [
                'mode'     => 'sandbox',
                'api_key'  => 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL',
                'api_url'  => 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/',
                'pg_codes' => ['knet'],
            ];
        }
    }
}
