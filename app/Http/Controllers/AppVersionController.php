<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use App\Models\FontFamily;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function addVersions() {



        $appVersion = AppVersion::findOrFail(1);

                $fontFamilies = FontFamily::latest()->get();


        return view('admin.app_version.app_version_add',compact('appVersion','fontFamilies'));


    }

    public function updateVersions(Request $request) {


          // Validate incoming data
          $request->validate([
            'version' => 'required',
            'ios' => 'required|url',
            'android' => 'required|url',
            'app_status' => 'nullable|in:normal,update,maintenance',
            'maintenance_title' => 'nullable|string|max:255',
            'maintenance_message' => 'nullable|string',
            'des' => 'nullable|string|max:500',
            'whatsapp_number' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'online_game_win_points' => 'nullable|integer|min:0',
            'offline_game_win_points' => 'nullable|integer|min:0',
            'mail_from_name'         => 'nullable|string|max:150',
            'mail_from_address'      => 'nullable|email|max:150',
            'mail_otp_from_name'     => 'nullable|string|max:150',
            'mail_otp_from_address'  => 'nullable|email|max:150',
            'mail_otp_cc'            => 'nullable|string|max:255',
            'mail_invoice_from_name' => 'nullable|string|max:150',
            'mail_invoice_from_address' => 'nullable|email|max:150',
            'mail_invoice_cc'        => 'nullable|string|max:255',
            'mail_coupon_from_name'  => 'nullable|string|max:150',
            'mail_coupon_from_address' => 'nullable|email|max:150',
            'mail_coupon_cc'         => 'nullable|string|max:255',
            'payment_mode'           => 'nullable|in:sandbox,live',
            'ottu_live_api_url'      => 'nullable|string',
            'ottu_live_api_key'      => 'nullable|string',
            'ottu_live_pg_codes'     => 'nullable|string',
            'ottu_sandbox_api_url'   => 'nullable|string',
            'ottu_sandbox_api_key'   => 'nullable|string',
            'ottu_sandbox_pg_codes'  => 'nullable|string',
        ], [
            'version.required' => 'يجب إدخال إصدار التطبيق.',
            'ios.required' => 'يجب إدخال رابط التطبيق على App Store.',
            'ios.url' => 'رابط App Store يجب أن يكون رابطًا صحيحًا.',
            'android.required' => 'يجب إدخال رابط التطبيق على Google Play.',
            'android.url' => 'رابط Google Play يجب أن يكون رابطًا صحيحًا.',
            'app_status.in' => 'حالة تنبيه التطبيق يجب أن تكون: طبيعي أو تحديث أو صيانة.',
            'des.string' => 'يجب أن يكون الوصف نصًا.',
            'des.max' => 'يجب ألا يتجاوز الوصف 500 حرف.',
            'contact_email.email' => 'البريد الإلكتروني للتواصل يجب أن يكون بريداً صالحاً.',
            'mail_from_address.email' => 'البريد الإلكتروني العام للمرسل يجب أن يكون بريداً صالحاً.',
            'mail_otp_from_address.email' => 'البريد الإلكتروني لمرسل رمز التحقق يجب أن يكون بريداً صالحاً.',
            'mail_invoice_from_address.email' => 'البريد الإلكتروني لمرسل الفواتير يجب أن يكون بريداً صالحاً.',
            'mail_coupon_from_address.email' => 'البريد الإلكتروني لمرسل الكوبونات يجب أن يكون بريداً صالحاً.',
            'online_game_win_points.integer' => 'نقاط الفائز في لعبة الميدان يجب أن تكون رقماً صحيحاً.',
            'offline_game_win_points.integer' => 'نقاط الفريق المرشح الفائز في لعبة الجلسة يجب أن تكون رقماً صحيحاً.',
            'payment_mode.in' => 'بيئة الدفع يجب أن تكون إما تجريبية (sandbox) أو حقيقية (live).',
        ]);

    // Save or update the version in the database (if using a Version model)
    $version = AppVersion::updateOrCreate(
        ['id' => 1], // Assuming a single version record (modify as needed)
        [
            'version' => $request->version,
            'ios' => $request->ios,
            'android' => $request->android,
            'des' => $request->des,
            'app_status' => $request->app_status ?? 'normal',
            'maintenance_title' => $request->maintenance_title,
            'maintenance_message' => $request->maintenance_message,
            'app_type' => $request->app_type,
            'update_required' => $request->update_required,
            'font_family_id' => $request->font_family_id,
            'primary_color' => $request->primary_color,
            'font_color_normal' => $request->font_color_normal,
            'app_name' => $request->app_name,
            'whatsapp_number' => $request->whatsapp_number,
            'contact_email' => $request->contact_email,
            'mail_from_name' => $request->mail_from_name,
            'mail_from_address' => $request->mail_from_address,
            'mail_otp_from_name' => $request->mail_otp_from_name,
            'mail_otp_from_address' => $request->mail_otp_from_address,
            'mail_otp_cc' => $request->mail_otp_cc,
            'mail_invoice_from_name' => $request->mail_invoice_from_name,
            'mail_invoice_from_address' => $request->mail_invoice_from_address,
            'mail_invoice_cc' => $request->mail_invoice_cc,
            'mail_coupon_from_name' => $request->mail_coupon_from_name,
            'mail_coupon_from_address' => $request->mail_coupon_from_address,
            'mail_coupon_cc' => $request->mail_coupon_cc,
            'online_game_win_points' => $request->online_game_win_points ?? 6,
            'offline_game_win_points' => $request->offline_game_win_points ?? 6,
            'payment_mode' => $request->payment_mode ?? 'sandbox',
            'ottu_live_api_url' => $request->ottu_live_api_url ?: 'https://pay.pikw.com/b/checkout/v1/pymt-txn/',
            'ottu_live_api_key' => $request->ottu_live_api_key ?: 'KSK2Iuqw.mowuSwOTIq6ZDT48FvQvW0GaaQPwFjIy',
            'ottu_live_pg_codes' => $request->ottu_live_pg_codes ?: 'knet,credit-card',
            'ottu_sandbox_api_url' => $request->ottu_sandbox_api_url ?: 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/',
            'ottu_sandbox_api_key' => $request->ottu_sandbox_api_key ?: 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL',
            'ottu_sandbox_pg_codes' => $request->ottu_sandbox_pg_codes ?: 'knet',
        ]
    );
    $notification = array(
        'message' => 'تم تحديث اعدادات التطبيق بنجاح',
        'alert-type' => 'success'
    );


    return back()->with($notification);

    // return redirect()->back()->with('success', 'تم تحديث بيانات الإصدار بنجاح');


    }

    /**
     * Show coin transaction descriptions settings page
     */
    public function coinTransactionSettings()
    {
        $appVersion = AppVersion::firstOrCreate(['id' => 1]);
        return view('admin.coin_settings.transaction_descriptions', compact('appVersion'));
    }

    /**
     * Update coin transaction descriptions settings
     */
    public function updateCoinTransactionSettings(Request $request)
    {
        $request->validate([
            'coin_desc_online_purchase'   => 'nullable|string|max:255',
            'coin_desc_game_win'          => 'nullable|string|max:255',
            'coin_desc_rank_upgrade'      => 'nullable|string|max:255',
            'coin_desc_coupon_exchange'   => 'nullable|string|max:255',
            'coin_desc_avatar_purchase'   => 'nullable|string|max:255',
            'coin_desc_animation_purchase'=> 'nullable|string|max:255',
            'coin_desc_admin_adjustment'  => 'nullable|string|max:255',
        ]);

        $appVersion = AppVersion::firstOrCreate(['id' => 1]);
        $appVersion->update([
            'coin_desc_online_purchase'   => $request->coin_desc_online_purchase ?: 'شراء باقة عملات عبر الدفع الإلكتروني',
            'coin_desc_game_win'          => $request->coin_desc_game_win ?: 'مكافأة الفوز في التحدي',
            'coin_desc_rank_upgrade'      => $request->coin_desc_rank_upgrade ?: 'مكافأة الترقية إلى رتبة جديدة',
            'coin_desc_coupon_exchange'   => $request->coin_desc_coupon_exchange ?: 'استبدال عملات بكوبون خصم',
            'coin_desc_avatar_purchase'   => $request->coin_desc_avatar_purchase ?: 'شراء عنصر من متجر الأفاتار',
            'coin_desc_animation_purchase'=> $request->coin_desc_animation_purchase ?: 'شراء حركة تفاعلية من متجر الحركات',
            'coin_desc_admin_adjustment'  => $request->coin_desc_admin_adjustment ?: 'تعديل رصيد من إدارة التطبيق',
        ]);

        $notification = array(
            'message' => 'تم حفظ وتحديث نصوص عمليات العملات بنجاح',
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    /// Api






    public function getSettingApp($id)
    {

        $answer = AppVersion::where('id', $id)->get()->first();

    return response()->json($answer);
    }
}
