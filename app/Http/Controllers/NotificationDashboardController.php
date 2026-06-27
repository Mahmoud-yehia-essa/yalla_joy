<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationDashboardController extends Controller
{
      public function setNotificationRead($notificationId)
    {



       $current_user_id = Auth::User()->id;

       $user = User::find($current_user_id);


$notification = $user->unreadNotifications()->where('id', $notificationId)->first();
if ($notification) {
    $notification->markAsRead();


    if($notification->type == "App\Notifications\UserGameNotification")
    {

       // return "OrderComplete";
    //    $order_id = $notification->data['order_id'];


        return redirect()->route('all.user.games');

    }
    else if($notification->type == "App\Notifications\NewUserRegisterNotification")
    {
        $userId = $notification->data['user_id'] ?? null;
        if ($userId) {
            return redirect()->route('edit.user', $userId);
        }
        return redirect()->route('dashboard');
    }
    else if($notification->type == "App\Notifications\NewProblemReportNotification")
    {
        return redirect()->route('all.problem.reports');
    }
    else if($notification->type == "App\Notifications\NewGamePlayedNotification")
    {
        return redirect()->route('all.games');
    }
    else if($notification->type == "App\Notifications\ContactUsNotification")
    {


        // $vendorId = $notification->data['vendorId'];


        return redirect()->route('all.contact.us');


        //$this->vendorId
    }
    else
    {
        // $userId = $notification->data['userId'];


        return redirect()->route('dashboard');


    }

  //  return $notification->type;





} else {
    // Handle case where the notification with the specified ID was not found
    // This could be logging an error, showing a message to the user, etc.
}









    }

    public function notificationSettings()
    {
        $user = Auth::user();
        return view('admin.notification.settings', compact('user'));
    }

    public function updateNotificationSettings(Request $request)
    {
        $user = User::find(Auth::id());
        $user->update([
            'notify_new_user' => $request->has('notify_new_user'),
            'notify_problem_report' => $request->has('notify_problem_report'),
            'notify_game_played' => $request->has('notify_game_played'),
        ]);

        $notification = array(
            'message' => 'تم حفظ إعدادات الإشعارات بنجاح.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
