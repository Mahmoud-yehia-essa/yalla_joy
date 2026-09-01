<?php

namespace App\Http\Controllers;

use App\Mail\OtpVerificationMail;
use App\Mail\SpecialCouponMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpEmailController extends Controller
{
    /**
     * إرسال كود OTP عبر بريد لارفيل الاحتياطي
     */
    public function sendOtpEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required',
            'type'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $email = trim($request->email);
            $otp = (string)$request->otp;
            $type = $request->type ?? 'signup';

            Mail::to($email)->send(new OtpVerificationMail($otp, $type));

            Log::info("OTP email successfully sent to {$email} via Laravel Backup Mailer.");

            return response()->json([
                'status'  => true,
                'message' => 'تم إرسال كود التحقق بنجاح عبر البريد الاحتياطي',
            ], 200);
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email to {$request->email}: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'فشل إرسال كود التحقق: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * إرسال تفاصيل الكوبون الخاص عبر بريد لارفيل الاحتياطي
     */
    public function sendCouponEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'       => 'required|email',
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $email = trim($request->email);
            $userObj = (object)[
                'fname' => $request->fname ?? 'بطل التحدي',
            ];
            $sponsorObj = (object)[
                'title' => $request->sponsor_title ?? 'شريكنا المتميز',
                'photo' => $request->sponsor_photo ?? null,
            ];
            $couponObj = (object)[
                'coupon_name'        => $request->coupon_name ?? 'قسيمة مميزة',
                'coupon_description' => $request->coupon_description ?? '',
                'coupon_code'        => $request->coupon_code,
                'valid_until'        => $request->valid_until,
                'sponsor'            => $sponsorObj,
            ];

            Mail::to($email)->send(new SpecialCouponMail($couponObj, $userObj));

            Log::info("Special Coupon email sent to {$email} via Laravel Backup Mailer.");

            return response()->json([
                'status'  => true,
                'message' => 'تم إرسال بريد القسيمة بنجاح عبر البريد الاحتياطي',
            ], 200);
        } catch (\Exception $e) {
            Log::error("Failed to send special coupon email to {$request->email}: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'فشل إرسال بريد القسيمة: ' . $e->getMessage(),
            ], 500);
        }
    }
}
