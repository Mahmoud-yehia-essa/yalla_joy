<?php

namespace App\Http\Controllers;

use App\Models\GameCoupon;
use App\Models\GamePurchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Exports\GameCouponExport;
use Maatwebsite\Excel\Facades\Excel;

class GameCouponController extends Controller
{
    public function allGameCoupon()
    {
        $coupons = GameCoupon::with('gamePurchase')->latest()->get();
        return view('admin.game_coupon.all_game_coupon', compact('coupons'));
    }

    public function addGameCoupon()
    {
        $purchases = GamePurchase::latest()->get();
        return view('admin.game_coupon.add_game_coupon', compact('purchases'));
    }

    public function storeGameCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:game_coupons,code',
            'type' => 'required|in:percentage,free_games,package_bonus',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'free_games_count' => 'nullable|integer|min:0',
            'game_purchases_id' => 'nullable|exists:game_purchases,id',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ], [
            'code.required' => '⚠️ الرجاء إدخال كود الكوبون',
            'code.unique' => '⚠️ هذا الكود مستخدم بالفعل',
            'type.required' => '⚠️ الرجاء اختيار نوع الكوبون',
            'type.in' => '⚠️ نوع الكوبون المختار غير صالح',
            'discount_percentage.numeric' => '⚠️ نسبة الخصم يجب أن تكون قيمة رقمية',
            'discount_percentage.min' => '⚠️ نسبة الخصم لا يمكن أن تقل عن 0',
            'discount_percentage.max' => '⚠️ نسبة الخصم لا يمكن أن تتجاوز 100',
            'free_games_count.integer' => '⚠️ عدد الألعاب يجب أن يكون رقمًا صحيحًا',
            'free_games_count.min' => '⚠️ عدد الألعاب لا يمكن أن يقل عن 0',
            'game_purchases_id.exists' => '⚠️ الباقة المحددة غير صالحة',
            'usage_limit.integer' => '⚠️ الحد الأقصى للاستخدام يجب أن يكون رقمًا صحيحًا',
            'usage_limit.min' => '⚠️ الحد الأقصى للاستخدام يجب أن يكون 1 على الأقل',
            'expires_at.date' => '⚠️ تاريخ انتهاء الصلاحية غير صالح',
        ]);

        GameCoupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'discount_percentage' => $request->type == 'percentage' ? $request->discount_percentage : null,
            'free_games_count' => ($request->type == 'free_games' || $request->type == 'package_bonus') ? $request->free_games_count : null,
            'game_purchases_id' => $request->type == 'package_bonus' ? $request->game_purchases_id : null,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        $notification = array(
            'message' => 'تم إضافة كوبون الألعاب بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.coupon')->with($notification);
    }

    public function editGameCoupon($id)
    {
        $coupon = GameCoupon::findOrFail($id);
        $purchases = GamePurchase::latest()->get();
        return view('admin.game_coupon.edit_game_coupon', compact('coupon', 'purchases'));
    }

    public function updateGameCoupon(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'code' => 'required|string|max:255|unique:game_coupons,code,' . $id,
            'type' => 'required|in:percentage,free_games,package_bonus',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'free_games_count' => 'nullable|integer|min:0',
            'game_purchases_id' => 'nullable|exists:game_purchases,id',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ], [
            'code.required' => '⚠️ الرجاء إدخال كود الكوبون',
            'code.unique' => '⚠️ هذا الكود مستخدم بالفعل',
            'type.required' => '⚠️ الرجاء اختيار نوع الكوبون',
            'type.in' => '⚠️ نوع الكوبون المختار غير صالح',
            'discount_percentage.numeric' => '⚠️ نسبة الخصم يجب أن تكون قيمة رقمية',
            'discount_percentage.min' => '⚠️ نسبة الخصم لا يمكن أن تقل عن 0',
            'discount_percentage.max' => '⚠️ نسبة الخصم لا يمكن أن تتجاوز 100',
            'free_games_count.integer' => '⚠️ عدد الألعاب يجب أن يكون رقمًا صحيحًا',
            'free_games_count.min' => '⚠️ عدد الألعاب لا يمكن أن يقل عن 0',
            'game_purchases_id.exists' => '⚠️ الباقة المحددة غير صالحة',
            'usage_limit.integer' => '⚠️ الحد الأقصى للاستخدام يجب أن يكون رقمًا صحيحًا',
            'usage_limit.min' => '⚠️ الحد الأقصى للاستخدام يجب أن يكون 1 على الأقل',
            'expires_at.date' => '⚠️ تاريخ انتهاء الصلاحية غير صالح',
        ]);

        GameCoupon::findOrFail($id)->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'discount_percentage' => $request->type == 'percentage' ? $request->discount_percentage : null,
            'free_games_count' => ($request->type == 'free_games' || $request->type == 'package_bonus') ? $request->free_games_count : null,
            'game_purchases_id' => $request->type == 'package_bonus' ? $request->game_purchases_id : null,
            'usage_limit' => $request->usage_limit,
            'expires_at' => $request->expires_at,
        ]);

        $notification = array(
            'message' => 'تم تحديث كوبون الألعاب بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.coupon')->with($notification);
    }

    public function deleteGameCoupon($id)
    {
        GameCoupon::findOrFail($id)->delete();

        $notification = array(
            'message' => 'تم حذف كوبون الألعاب بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.coupon')->with($notification);
    }

    public function gameCouponActive($id)
    {
        GameCoupon::findOrFail($id)->update(['is_active' => true]);

        $notification = array(
            'message' => 'تم تفعيل الكوبون بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function gameCouponInactive($id)
    {
        GameCoupon::findOrFail($id)->update(['is_active' => false]);

        $notification = array(
            'message' => 'تم إلغاء تفعيل الكوبون بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function applyGameCouponApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'code' => 'required|string',
        ], [
            'user_id.required' => '⚠️ معرف المستخدم مطلوب',
            'user_id.exists' => '⚠️ هذا المستخدم غير موجود في النظام',
            'code.required' => '⚠️ كود الكوبون مطلوب',
            'code.string' => '⚠️ يجب أن يكون كود الكوبون نصاً',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 200);
        }

        $code = strtoupper(trim($request->code));
        $user_id = $request->user_id;

        $coupon = GameCoupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ كود الكوبون غير صحيح أو غير موجود'
            ], 200);
        }

        // 1. Check if coupon is active
        if (!$coupon->is_active) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ هذا الكوبون غير نشط حالياً'
            ], 200);
        }

        // 2. Check expiration date
        if ($coupon->expires_at && Carbon::parse($coupon->expires_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ هذا الكوبون منتهي الصلاحية'
            ], 200);
        }

        // 3. Check usage limit
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'success' => false,
                'message' => '⚠️ تم استنفاد الحد الأقصى لاستخدام هذا الكوبون'
            ], 200);
        }

        $user = User::find($user_id);

        if ($coupon->type === 'free_games') {
            $added_games = (int)($coupon->free_games_count ?? 0);
            $user->number_of_games = ($user->number_of_games ?? 0) + $added_games;
            $user->save();

            $coupon->increment('used_count');

            return response()->json([
                'success' => true,
                'message' => '🎉 تم تطبيق الكوبون بنجاح وتم إضافة ' . $added_games . ' من الألعاب إلى حسابك.',
                'coupon_type' => 'free_games',
                'added_games' => $added_games,
                'current_games' => $user->number_of_games,
                'coupon' => $coupon
            ], 200);

        } elseif ($coupon->type === 'package_bonus') {
            $package = $coupon->gamePurchase;

            if (!$package) {
                return response()->json([
                    'success' => false,
                    'message' => '⚠️ هذا الكوبون غير مرتبط بأي باقة ألعاب صالحة'
                ], 200);
            }

            $package_games = (int)$package->games_count;
            $bonus_games = (int)($coupon->free_games_count ?? 0);
            $added_games = $package_games + $bonus_games;

            $user->number_of_games = ($user->number_of_games ?? 0) + $added_games;
            $user->save();

            $coupon->increment('used_count');

            return response()->json([
                'success' => true,
                'message' => '🎉 تم تطبيق كوبون الباقة بنجاح وتم إضافة ' . $added_games . ' لعبة لحسابك (' . $package_games . ' باقة + ' . $bonus_games . ' إضافية).',
                'coupon_type' => 'package_bonus',
                'added_games' => $added_games,
                'package_games' => $package_games,
                'bonus_games' => $bonus_games,
                'current_games' => $user->number_of_games,
                'coupon' => $coupon
            ], 200);

        } elseif ($coupon->type === 'percentage') {
            $discount_percentage = $coupon->discount_percentage;

            $coupon->increment('used_count');

            return response()->json([
                'success' => true,
                'message' => '🎉 تم تطبيق نسبة خصم الكوبون بنجاح بقيمة ' . $discount_percentage . '%.',
                'coupon_type' => 'percentage',
                'discount_percentage' => $discount_percentage,
                'coupon' => $coupon
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => '⚠️ نوع الكوبون غير معروف'
        ], 200);
    }

    public function exportGameCoupon()
    {
        return Excel::download(new GameCouponExport, 'game_coupons_' . date('Y_m_d_His') . '.xlsx');
    }
}
