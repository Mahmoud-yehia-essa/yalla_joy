<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandPageController extends Controller
{
    //
     public function comingSoon()
    {
        return view('frontend.landing.land_page');
    }
}
