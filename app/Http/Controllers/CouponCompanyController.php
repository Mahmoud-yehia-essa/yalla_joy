<?php

namespace App\Http\Controllers;

use App\Models\CouponCompany;
use App\Models\Sponsor;
use App\Models\GameCoin;
use App\Models\CouponCompanyUserUsed;
use App\Models\User;
use App\Mail\SpecialCouponMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\CouponCompanyExport;
use Maatwebsite\Excel\Facades\Excel;

class CouponCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allCouponCompanies()
    {
        $couponCompanies = CouponCompany::with(['sponsor', 'gameCoin'])->latest()->get();
        return view('admin.coupon_companies.coupon_companies_all', compact('couponCompanies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addCouponCompany()
    {
        $sponsors = Sponsor::all();
        $gameCoins = GameCoin::all();
        return view('admin.coupon_companies.coupon_companies_add', compact('sponsors', 'gameCoins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCouponCompany(Request $request)
    {
        $request->validate([
            'coupon_name' => 'required',
            'sponsor_id' => 'required|exists:sponsors,id',
        ]);

        // Generate unique coupon code starting with 'F'
        do {
            $coupon_code = 'F' . strtoupper(bin2hex(random_bytes(4))); // Example: F1A2B3C4D
        } while (CouponCompany::where('coupon_code', $coupon_code)->exists());

        CouponCompany::create([
            'coupon_name' => $request->coupon_name,
            'coupon_name_en' => $request->coupon_name_en,
            'coupon_description' => $request->coupon_description,
            'coupon_description_en' => $request->coupon_description_en,
            'coupon_code' => $coupon_code,
            'valid_until' => $request->valid_until,
            'sponsor_id' => $request->sponsor_id,
            'is_scratch_coupon' => $request->has('is_scratch_coupon') ? 1 : 0,
            'is_special_coupon' => $request->has('is_special_coupon') ? 1 : 0,
            'game_coin_id' => $request->game_coin_id,
            'game_coins_count' => $request->game_coins_count ?? 0,
        ]);

        $notification = array(
            'message' => 'تم إضافة كوبون الشركة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.coupon_companies')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editCouponCompany($id)
    {
        $couponCompany = CouponCompany::findOrFail($id);
        $sponsors = Sponsor::all();
        $gameCoins = GameCoin::all();
        return view('admin.coupon_companies.coupon_companies_edit', compact('couponCompany', 'sponsors', 'gameCoins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCouponCompany(Request $request)
    {
        $id = $request->id;
        $couponCompany = CouponCompany::findOrFail($id);

        $request->validate([
            'coupon_name' => 'required',
            'sponsor_id' => 'required|exists:sponsors,id',
        ]);

        $couponCompany->update([
            'coupon_name' => $request->coupon_name,
            'coupon_name_en' => $request->coupon_name_en,
            'coupon_description' => $request->coupon_description,
            'coupon_description_en' => $request->coupon_description_en,
            'valid_until' => $request->valid_until,
            'sponsor_id' => $request->sponsor_id,
            'is_scratch_coupon' => $request->has('is_scratch_coupon') ? 1 : 0,
            'is_special_coupon' => $request->has('is_special_coupon') ? 1 : 0,
            'game_coin_id' => $request->game_coin_id,
            'game_coins_count' => $request->game_coins_count ?? 0,
        ]);

        $notification = array(
            'message' => 'تم تحديث كوبون الشركة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.coupon_companies')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteCouponCompany($id)
    {
        CouponCompany::findOrFail($id)->delete();

        $notification = array(
            'message' => 'تم حذف كوبون الشركة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /**
     * Public coupon page - accessible by coupon_code parameter.
     */
    public function showCouponPage(Request $request)
    {
        $coupon_code = $request->coupon_code;
        $user_id = $request->user_id;

        if (!$coupon_code) {
            abort(200, 'كود الكوبون غير موجود');
        }

        $coupon = CouponCompany::with(['sponsor', 'gameCoin'])
            ->where('coupon_code', strtoupper($coupon_code))
            ->first();

        if (!$coupon) {
            abort(404, 'الكوبون غير موجود');
        }

        $isExpired = $coupon->valid_until
            ? \Carbon\Carbon::parse($coupon->valid_until)->isPast()
            : false;

        $usageRecord = null;
        if ($user_id) {
            $usageRecord = CouponCompanyUserUsed::where('user_id', $user_id)
                ->where('coupon_companie_id', $coupon->id)
                ->first();
        }

        $isUsedByUser = $usageRecord && $usageRecord->is_used;
        $usedAt = $usageRecord ? $usageRecord->used_at : null;

        return view('coupon.show', compact('coupon', 'isExpired', 'user_id', 'isUsedByUser', 'usedAt'));
    }

    /**
     * API: Get all coupon companies for a user with status.
     */
    public function getCouponCompaniesApi(Request $request)
    {
        $user_id = $request->user_id;

        if (!$user_id) {
            return response()->json([
                'status' => false,
                'message' => 'user_id is required'
            ], 200);
        }

        // Get all coupons with their sponsor and game coin info
        $coupons = CouponCompany::with(['sponsor', 'gameCoin'])->latest()->get();

        // Get the coupons this user has already bought with their usage status
        $userCoupons = \App\Models\CouponCompanyUserUsed::where('user_id', $user_id)
            ->get()
            ->keyBy('coupon_companie_id');

        $data = $coupons->map(function ($coupon) use ($userCoupons) {
            $userRecord = $userCoupons->get($coupon->id);
            
            return [
                'id' => $coupon->id,
                'coupon_name' => $coupon->coupon_name,
                'coupon_name_en' => $coupon->coupon_name_en,
                'coupon_description' => $coupon->coupon_description,
                'coupon_description_en' => $coupon->coupon_description_en,
                'coupon_code' => $coupon->coupon_code,
                'valid_until' => $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d') : null,
                'valid_until_human' => $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->locale('ar')->diffForHumans() : 'دائم',
                'sponsor' => [
                    'id' => $coupon->sponsor->id ?? null,
                    'title' => $coupon->sponsor->title ?? null,
                    'image' => $coupon->sponsor->photo ?? null,
                ],
                'is_scratch_coupon' => (bool)$coupon->is_scratch_coupon,
                'is_special_coupon' => (bool)$coupon->is_special_coupon,
                'cost' => [
                    'coin_id' => $coupon->game_coin_id,
                    'coin_name' => $coupon->gameCoin->name ?? null,
                    'coin_photo' => $coupon->gameCoin->photo ?? null,
                    'count' => $coupon->game_coins_count,
                ],
                'is_bought' => $userRecord ? true : false,
                'is_used' => $userRecord ? (bool)$userRecord->is_used : false,
                'is_expired' => $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->isPast() : false,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * API: Purchase a coupon using game coins.
     */
    public function buyCouponApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'coupon_id' => 'required|exists:coupon_companies,id',
        ]);

        $userId = $request->user_id;
        $couponId = $request->coupon_id;

        $coupon = CouponCompany::findOrFail($couponId);

        // Check if already bought
        $alreadyBought = \App\Models\CouponCompanyUserUsed::where('user_id', $userId)
            ->where('coupon_companie_id', $couponId)
            ->exists();

        if ($alreadyBought) {
            return response()->json([
                'status' => false,
                'message' => 'لقد قمت بشراء هذا الكوبون مسبقاً'
            ], 200);
        }

        // Check if coupon is expired
        if ($coupon->valid_until && \Carbon\Carbon::parse($coupon->valid_until)->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'عذراً، هذا الكوبون منتهي الصلاحية'
            ], 200);
        }

        // If coupon has a cost
        if ($coupon->game_coin_id && $coupon->game_coins_count > 0) {
            $gameCoinId = $coupon->game_coin_id;
            $cost = $coupon->game_coins_count;

            // Calculate user balance for this specific coin
            $totalAdd = \App\Models\UserCoin::where('user_id', $userId)
                ->where('game_coin_id', $gameCoinId)
                ->where('type', 'add')
                ->sum('coins_number');

            $totalDeduct = \App\Models\UserCoin::where('user_id', $userId)
                ->where('game_coin_id', $gameCoinId)
                ->where('type', '!=', 'add')
                ->sum('coins_number');

            $balance = $totalAdd - abs($totalDeduct);

            if ($balance < $cost) {
                return response()->json([
                    'status' => false,
                    'message' => 'عذراً، رصيدك من العملات غير كافٍ لشراء هذا الكوبون'
                ], 200);
            }

            // Deduct coins
            \App\Models\UserCoin::create([
                'user_id' => $userId,
                'game_coin_id' => $gameCoinId,
                'coins_number' => -abs($cost),
                'type' => 'withdraw'
            ]);
        }

        // Record the purchase
        \App\Models\CouponCompanyUserUsed::create([
            'user_id' => $userId,
            'coupon_companie_id' => $couponId,
            'is_buy' => 1,
            'is_used' => 0
        ]);

        $user = User::findOrFail($userId);
        $message = 'تم شراء الكوبون بنجاح';

        if ($coupon->is_special_coupon) {
            try {
                if ($user->email) {
                    Mail::to($user->email)->send(new SpecialCouponMail($coupon, $user));
                    $message = 'تم شراء الكوبون بنجاح، وتم إرسال تفاصيل القسيمة والرمز الخاص بها إلى بريدك الإلكتروني بنجاح.';
                } else {
                    $message = 'تم شراء الكوبون بنجاح، ولكن لم نتمكن من إرسال البريد الإلكتروني لعدم توفر عنوان بريدي للمستخدم.';
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send special coupon email: " . $e->getMessage());
                $message = 'تم شراء الكوبون بنجاح، ولكن حدث خطأ أثناء إرسال البريد الإلكتروني بالتفاصيل.';
            }
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    /**
     * API: Mark a coupon as used.
     */
    public function useCouponApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'coupon_id' => 'required|exists:coupon_companies,id',
        ]);

        $record = CouponCompanyUserUsed::where('user_id', $request->user_id)
            ->where('coupon_companie_id', $request->coupon_id)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'عذراً، لم تقم بشراء هذا الكوبون بعد'
            ], 200);
        }

        if ($record->is_used) {
            return response()->json([
                'status' => false,
                'message' => 'هذا الكوبون تم استخدامه مسبقاً'
            ], 200);
        }

        $usedAt = now();
        $record->update([
            'is_used' => 1,
            'used_at' => $usedAt,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تم استخدام الكوبون بنجاح',
            'used_at' => $usedAt->format('Y-m-d H:i:s')
        ]);
    }

    public function exportCouponCompany()
    {
        return Excel::download(new CouponCompanyExport, 'coupon_companies_' . date('Y_m_d_His') . '.xlsx');
    }
}
