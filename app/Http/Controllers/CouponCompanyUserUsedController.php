<?php

namespace App\Http\Controllers;

use App\Models\CouponCompanyUserUsed;
use Illuminate\Http\Request;

class CouponCompanyUserUsedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allUsedCoupons()
    {
        $usedCoupons = CouponCompanyUserUsed::with(['user', 'couponCompany'])->latest()->get();
        return view('admin.coupon_companies.coupon_companies_used', compact('usedCoupons'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteUsedCoupon($id)
    {
        CouponCompanyUserUsed::findOrFail($id)->delete();

        $notification = array(
            'message' => 'تم حذف سجل استخدام الكوبون بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
