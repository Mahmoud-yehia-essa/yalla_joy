<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\Price;
use App\Models\UserCoin;

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
        $apiKey = config('ottu.api_key', 'KSK2Iuqw.mowuSwOTIq6ZDT48FvQvW0GaaQPwFjIy');
        $apiUrl = config('ottu.api_url', 'https://pay.pikw.com/b/checkout/v1/pymt-txn/');

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
            $configPgCodes = config('ottu.pg_codes');
            if (!empty($configPgCodes)) {
                $pgCodes = is_array($configPgCodes) ? $configPgCodes : explode(',', $configPgCodes);
            } else {
                $single = config('ottu.pg_code', 'knet');
                $pgCodes = [$single];
            }
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
        $apiKey = config('ottu.api_key', 'KSK2Iuqw.mowuSwOTIq6ZDT48FvQvW0GaaQPwFjIy');
        $baseUrl = rtrim(config('ottu.api_url', 'https://pay.pikw.com/b/checkout/v1/pymt-txn/'), '/');
        $apiUrl = $baseUrl . '/' . $sessionId . '/';

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
}
