<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

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
        $apiKey = config('ottu.api_key', 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL');
        $apiUrl = config('ottu.api_url', 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/');

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
                $pgCodes = array_filter([$single, 'aub-test-alhil']);
            }
        }
        $pgCodes = array_values(array_unique(array_filter(array_map('trim', (array)$pgCodes))));
        if (empty($pgCodes)) {
            $pgCodes = ['knet', 'aub-test-alhil'];
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
                'user_id'     => $validated['user_id'],
                'games_count' => $validated['games_count'],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Ottu Checkout Session Created', ['order_no' => $orderNo, 'session_id' => $data['session_id'] ?? '']);

                return response()->json([
                    'status'       => 'success',
                    'checkout_url' => $data['checkout_url'] ?? '',
                    'session_id'   => $data['session_id'] ?? '',
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
        $extra = $payload['extra'] ?? [];
        $userId = $extra['user_id'] ?? null;
        $gamesCount = (int)($extra['games_count'] ?? 0);

        // Process successful payment
        if (in_array($state, ['paid', 'captured', 'success', 'completed', 'approved', 'processed'])) {
            if ($userId && $gamesCount > 0 && $sessionId) {
                $processedCacheKey = 'ottu_session_processed_' . $sessionId;
                if (!Cache::has($processedCacheKey)) {
                    $user = User::find($userId);
                    if ($user) {
                        $user->number_of_games = ($user->number_of_games ?? 0) + $gamesCount;
                        $user->save();
                        Cache::put($processedCacheKey, true, now()->addDays(7));
                        Log::info("Updated user #{$userId} games by +{$gamesCount} via webhook");
                    }
                }
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
        $apiKey = config('ottu.api_key', 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL');
        $apiUrl = 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/' . $sessionId . '/';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Api-Key ' . $apiKey,
            ])->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("Ottu checkStatus response for {$sessionId}:", $data ?? []);

                $state = strtolower($data['state'] ?? ($data['status'] ?? 'pending'));
                $isPaid = in_array($state, ['paid', 'captured', 'success', 'completed', 'processed', 'approved']);

                if (!$isPaid && !empty($data['payment_methods']) && is_array($data['payment_methods'])) {
                    foreach ($data['payment_methods'] as $pm) {
                        $pmState = strtolower($pm['state'] ?? ($pm['status'] ?? ''));
                        if (in_array($pmState, ['paid', 'captured', 'success', 'completed', 'approved', 'processed'])) {
                            $isPaid = true;
                            $state = $pmState;
                            break;
                        }
                    }
                }

                $currentNumberOfGames = null;
                if ($isPaid) {
                    $extra = $data['extra'] ?? [];
                    $userId = $extra['user_id'] ?? null;
                    $gamesCount = (int)($extra['games_count'] ?? 0);
                    if ($userId && $gamesCount > 0) {
                        $user = User::find($userId);
                        if ($user) {
                            $processedCacheKey = 'ottu_session_processed_' . $sessionId;
                            if (!Cache::has($processedCacheKey)) {
                                $user->number_of_games = ($user->number_of_games ?? 0) + $gamesCount;
                                $user->save();
                                Cache::put($processedCacheKey, true, now()->addDays(7));
                                Log::info("Updated user #{$userId} games by +{$gamesCount} via checkStatus");
                            }
                            $currentNumberOfGames = $user->number_of_games;
                        }
                    }
                }

                return response()->json([
                    'status'          => $state,
                    'paid'            => $isPaid,
                    'number_of_games' => $currentNumberOfGames,
                    'session_id'      => $sessionId,
                    'order_no'        => $data['order_no'] ?? '',
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
        
        return response('<html><body><script>window.location.href="myapp://payment-callback";</script><p>Redirecting to app...</p></body></html>');
    }
}
