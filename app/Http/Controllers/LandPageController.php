<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandPageController extends Controller
{
    //
     public function landingPage()
    {
        return view('frontend.landing.land_page');
    }

    public function comingSoon()
    {
        return view('frontend.landing.land_page');
    }

    public function smartDownload(Request $request)
    {
        $iosUrl = "https://apps.apple.com/us/app/feek-tahadi-فيك-تحدي/id6780262870";
        $androidUrl = "https://play.google.com/store/apps/details?id=net.fiktahadi.fiktahadi_app";
        $fallbackUrl = url('/');

        $userAgent = strtolower($request->userAgent() ?? '');

        $isIos = str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad') || str_contains($userAgent, 'ipod');
        $isAndroid = str_contains($userAgent, 'android');

        if ($isIos) {
            return redirect()->away($iosUrl);
        } elseif ($isAndroid) {
            return redirect()->away($androidUrl);
        }

        return redirect()->away($fallbackUrl);
    }

    public function privacyPolicy()
    {
        return view('frontend.privacy_policy');
    }
}
