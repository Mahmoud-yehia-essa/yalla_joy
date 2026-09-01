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
            'des' => 'nullable|string|max:500',
            'whatsapp_number' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:100',
            'online_game_win_points' => 'nullable|integer|min:0',
        ], [
            'version.required' => 'يجب إدخال إصدار التطبيق.',
            'ios.required' => 'يجب إدخال رابط التطبيق على App Store.',
            'ios.url' => 'رابط App Store يجب أن يكون رابطًا صحيحًا.',
            'android.required' => 'يجب إدخال رابط التطبيق على Google Play.',
            'android.url' => 'رابط Google Play يجب أن يكون رابطًا صحيحًا.',
            'des.string' => 'يجب أن يكون الوصف نصًا.',
            'des.max' => 'يجب ألا يتجاوز الوصف 500 حرف.',
            'contact_email.email' => 'البريد الإلكتروني للتواصل يجب أن يكون بريداً صالحاً.',
            'online_game_win_points.integer' => 'نقاط الفائز في لعبة الميدان يجب أن تكون رقماً صحيحاً.',
        ]);

    // Save or update the version in the database (if using a Version model)
    $version = AppVersion::updateOrCreate(
        ['id' => 1], // Assuming a single version record (modify as needed)
        [
            'version' => $request->version,
            'ios' => $request->ios,
            'android' => $request->android,
            'des' => $request->des,
            'app_type' => $request->app_type,
            'update_required' => $request->update_required,
            'font_family_id' => $request->font_family_id,
            'primary_color' => $request->primary_color,
            'font_color_normal' => $request->font_color_normal,
            'app_name' => $request->app_name,
            'whatsapp_number' => $request->whatsapp_number,
            'contact_email' => $request->contact_email,
            'online_game_win_points' => $request->online_game_win_points ?? 6,
        ]
    );
    $notification = array(
        'message' => 'تم تحديث اعدادات التطبيق بنجاح',
        'alert-type' => 'success'
    );


    return back()->with($notification);

    // return redirect()->back()->with('success', 'تم تحديث بيانات الإصدار بنجاح');


    }


    /// Api






    public function getSettingApp($id)
    {

        $answer = AppVersion::where('id', $id)->get()->first();

    return response()->json($answer);
    }
}
