<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification()
    {
return view('admin.notification.send_notification');
    }

    public function alldNotification()
    {
return view('admin.notification.all_notification');
    }
}
