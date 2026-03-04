<?php

namespace App\Http\Controllers;

use App\Models\OnlineGameCategory;
use App\Models\OnlineGameInfo;
use App\Models\OnlineGameUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OnlineGameController extends Controller
{

//api
    public function addGameOnlineInfo(Request $request)
    {




        $onlineGameInfoId =  OnlineGameInfo::create([
            'created_user_id' => $request->created_user_id,
            'online_game_name'=>$request->online_game_name,
            'users_count' => $request->users_count,
            'game_session_name' => $request->game_session_name,]);

            $onlineGameInfoId = $onlineGameInfoId->id;

            // Get questionsRegisterId
            return response()->json(['onlineGameInfo_id' => $onlineGameInfoId], 200);

    }



    public function addOnlineGameCategory(Request $request)
    {




        $onlineGameCategory =  OnlineGameCategory::create([
            'category_id' => $request->category_id,
            'online_game_info_id' => $request->online_game_info_id,]);

            $onlineGameCategoryId = $onlineGameCategory->id;

            // Get questionsRegisterId
            return response()->json(['onlineGameCategoryId' => $onlineGameCategoryId], 200);

    }



    // public function addOnlineGameUsers(Request $request)
    // {




    //     $onlineGameUser =  OnlineGameUser::create([
    //         'user_id' => $request->user_id,
    //         'online_game_info_id' => $request->online_game_info_id,]);

    //         $onlineGameUserId = $onlineGameUser->id;

    //         // Get questionsRegisterId
    //         return response()->json(['OnlineGameUserId' => $onlineGameUserId], 200);

    // }

    public function addOnlineGameUsers(Request $request)
{
    // تحقق هل المستخدم موجود مسبقاً في نفس اللعبة
    $existingUser = OnlineGameUser::where('user_id', $request->user_id)
        ->where('online_game_info_id', $request->online_game_info_id)
        ->first();

    // إذا موجود مسبقاً
    if ($existingUser) {
        return response()->json([
            'OnlineGameUserId' => 0
        ], 200);
    }

    // إذا غير موجود يتم الإنشاء
    $onlineGameUser = OnlineGameUser::create([
        'user_id' => $request->user_id,
        'online_game_info_id' => $request->online_game_info_id,
        'role' => $request->role
    ]);

    return response()->json([
        'OnlineGameUserId' => $onlineGameUser->id
    ], 200);
}












    //  public function getGameOnlineInfoApi(Request $request)
    // {

    // $gameSessionName = $request->game_session_name;


    //     $user = OnlineGameInfo::where('game_session_name', $gameSessionName)->first(); // Returns true or false


    // }





// public function getGameOnlineInfoApi(Request $request)
// {
//     $gameSessionName = $request->game_session_name;

//     $gameInfo = OnlineGameInfo::where('game_session_name', $gameSessionName)->first();

//     if (!$gameInfo) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Game session not found',
//             'data' => null
//         ], 404);
//     }

//     return response()->json([
//         'status' => true,
//         'message' => 'Game session retrieved successfully',
//         'data' => $gameInfo
//     ], 200);
// }



public function getGameOnlineInfoApi(Request $request)
{
    $gameSessionName = $request->game_session_name;

    $gameInfo = OnlineGameInfo::with('user')
        ->where('game_session_name', $gameSessionName)
        ->first();

    if (!$gameInfo) {
        return response()->json([
            'status' => false,
            'message' => 'Game session not found',
            'data' => null
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Game session retrieved successfully',
        'data' => $gameInfo
    ], 200);
}





public function getCategoryApiByOnlineGameInfoId(Request $request)
{
    $gameInfoId = $request->game_info_id;

    $onlineGameCategory = OnlineGameCategory
        ::where('online_game_info_id', $gameInfoId)
        ->get();

    if (!$onlineGameCategory) {
        return response()->json([
            'status' => false,
            'message' => 'online_game_categories not found',
            'data' => null
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'online_game_categories retrieved successfully',
        'data' => $onlineGameCategory
    ], 200);
}




/// ForFrontEnd bladefile
public function getOnlineGameInfo($gameSessionName)
{

    $gameInfo = OnlineGameInfo::with([
        'user',
        'categories.category' // relation nested
    ])
    ->where('game_session_name', $gameSessionName)
    ->firstOrFail();

    return view(
        'frontend.online.user_joined_session_name',
        compact('gameInfo')
    );
}




public function addPoints(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'points' => 'required|integer|min:1'
    ]);

    $user = User::findOrFail($request->user_id);

    // إضافة النقاط
    $user->increment('online_points', $request->points);
    $user->increment('online_points_fixed', $request->points);

    // تحديث البيانات
    $user->refresh();

    return response()->json([
        'status' => true,
        'message' => 'Points added successfully',
        'user' => $user
    ]);
}

public function topUsersByOnlinePoints(Request $request)
{
    $limit = $request->limit ?? 10; // افتراضي 10 مستخدمين

    $users = User::where('status', 'active')
        ->orderByDesc('online_points')
        ->take($limit)
        ->get();

    return response()->json([
        'status' => true,
        'total' => $users->count(),
        'data' => $users
    ]);
}



}
