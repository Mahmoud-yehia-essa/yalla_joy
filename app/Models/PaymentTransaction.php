<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
        'games_count' => 'integer',
        'coins_count' => 'integer',
        'paid_at' => 'datetime',
    ];

    protected $appends = [
        'status_arabic',
        'gateway_payload',
        'gateway_payment_id',
        'gateway_ref_number',
        'gateway_track_id',
        'gateway_method_name',
        'gateway_fee',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Price package relationship
     */
    public function price()
    {
        return $this->belongsTo(Price::class, 'price_id');
    }

    /**
     * Game Coin relationship
     */
    public function gameCoin()
    {
        return $this->belongsTo(GameCoin::class, 'game_coin_id');
    }

    /**
     * Scope for successful payments
     */
    public function scopePaid($query)
    {
        return $query->whereIn('status', ['paid', 'success', 'captured', 'completed', 'approved', 'processed']);
    }

    /**
     * Scope for failed/cancelled payments
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', ['failed', 'cancelled', 'expired', 'error']);
    }

    /**
     * Status in Arabic
     */
    public function getStatusArabicAttribute()
    {
        $status = strtolower($this->status ?? '');
        if (in_array($status, ['paid', 'success', 'captured', 'completed', 'approved', 'processed'])) {
            return 'ناجحة (تم الدفع)';
        }
        if (in_array($status, ['failed', 'error'])) {
            return 'فشلت العملية';
        }
        if (in_array($status, ['cancelled', 'canceled'])) {
            return 'ملغاة';
        }
        if ($status === 'expired') {
            return 'منتهية الصلاحية';
        }
        return 'قيد الانتظار';
    }

    /**
     * Status Badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $status = strtolower($this->status ?? '');
        if (in_array($status, ['paid', 'success', 'captured', 'completed', 'approved', 'processed'])) {
            return '<span class="badge bg-success text-white px-2 py-2" style="font-size: 12px; border-radius: 8px;"><i class="bx bx-check-circle me-1"></i>ناجحة (تم الدفع)</span>';
        }
        if (in_array($status, ['failed', 'error'])) {
            return '<span class="badge bg-danger text-white px-2 py-2" style="font-size: 12px; border-radius: 8px;"><i class="bx bx-x-circle me-1"></i>فشلت العملية</span>';
        }
        if (in_array($status, ['cancelled', 'canceled'])) {
            return '<span class="badge bg-secondary text-white px-2 py-2" style="font-size: 12px; border-radius: 8px;"><i class="bx bx-block me-1"></i>ملغاة</span>';
        }
        return '<span class="badge bg-warning text-dark px-2 py-2" style="font-size: 12px; border-radius: 8px;"><i class="bx bx-time me-1"></i>قيد الانتظار</span>';
    }

    /**
     * Decoded gateway response array
     */
    public function getGatewayPayloadAttribute()
    {
        if (empty($this->gateway_response)) {
            return null;
        }
        $decoded = json_decode($this->gateway_response, true);
        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Extract Payment ID / Transaction ID from Gateway
     */
    public function getGatewayPaymentIdAttribute()
    {
        $payload = $this->gateway_payload;
        if (!$payload) return null;

        if (!empty($payload['payment_methods']) && is_array($payload['payment_methods'])) {
            foreach ($payload['payment_methods'] as $pm) {
                if (!empty($pm['payment_id'])) return $pm['payment_id'];
                if (!empty($pm['gateway_response']['payment_id'])) return $pm['gateway_response']['payment_id'];
                if (!empty($pm['gateway_response']['PaymentID'])) return $pm['gateway_response']['PaymentID'];
            }
        }

        return $payload['payment_id'] ?? ($payload['payment_transaction_id'] ?? null);
    }

    /**
     * Extract Bank Reference Number (RRN) from Gateway
     */
    public function getGatewayRefNumberAttribute()
    {
        $payload = $this->gateway_payload;
        if (!$payload) return null;

        if (!empty($payload['payment_methods']) && is_array($payload['payment_methods'])) {
            foreach ($payload['payment_methods'] as $pm) {
                if (!empty($pm['reference_number'])) return $pm['reference_number'];
                if (!empty($pm['gateway_response']['reference_number'])) return $pm['gateway_response']['reference_number'];
                if (!empty($pm['gateway_response']['ReferenceID'])) return $pm['gateway_response']['ReferenceID'];
                if (!empty($pm['gateway_response']['TrackID'])) return $pm['gateway_response']['TrackID'];
            }
        }

        return $payload['reference_number'] ?? null;
    }

    /**
     * Extract Track ID from Gateway
     */
    public function getGatewayTrackIdAttribute()
    {
        $payload = $this->gateway_payload;
        if (!$payload) return null;

        if (!empty($payload['payment_methods']) && is_array($payload['payment_methods'])) {
            foreach ($payload['payment_methods'] as $pm) {
                if (!empty($pm['track_id'])) return $pm['track_id'];
                if (!empty($pm['gateway_response']['track_id'])) return $pm['gateway_response']['track_id'];
                if (!empty($pm['gateway_response']['TrackID'])) return $pm['gateway_response']['TrackID'];
            }
        }

        return $payload['track_id'] ?? null;
    }

    /**
     * Extract Payment Method Name from Gateway (e.g. KNET, Credit Card, Visa, MasterCard, Apple Pay)
     */
    public function getGatewayMethodNameAttribute()
    {
        $payload = $this->gateway_payload;
        $code = null;
        $name = null;

        if ($payload && !empty($payload['payment_methods']) && is_array($payload['payment_methods'])) {
            foreach ($payload['payment_methods'] as $pm) {
                if (!empty($pm['name'])) $name = $pm['name'];
                if (!empty($pm['code'])) $code = strtolower($pm['code']);
                if ($name || $code) break;
            }
        }

        if (!$code) {
            $code = strtolower($this->pg_code ?? 'knet');
        }

        if ($code === 'knet') {
            return 'KNET (كي نت)';
        }
        if (in_array($code, ['credit_card', 'mpgs', 'cybersource', 'card', 'cc', 'debit_card'])) {
            return 'بطاقة ائتمان (Credit Card)';
        }
        if ($code === 'visa') {
            return 'Visa (فيزا)';
        }
        if (in_array($code, ['mastercard', 'master'])) {
            return 'MasterCard (ماستركارد)';
        }
        if (in_array($code, ['apple-pay', 'apple_pay', 'applepay'])) {
            return 'Apple Pay (أبل باي)';
        }
        if (in_array($code, ['google-pay', 'google_pay', 'googlepay'])) {
            return 'Google Pay (جوجل باي)';
        }

        return $name ?: strtoupper($code);
    }

    /**
     * Extract Gateway Fee if present
     */
    public function getGatewayFeeAttribute()
    {
        $payload = $this->gateway_payload;
        if (!$payload) return '0.000';

        if (!empty($payload['billing']['fee']['value'])) {
            return $payload['billing']['fee']['value'];
        }

        if (!empty($payload['payment_methods']) && is_array($payload['payment_methods'])) {
            foreach ($payload['payment_methods'] as $pm) {
                if (isset($pm['fee'])) return $pm['fee'];
            }
        }

        return '0.000';
    }
}
