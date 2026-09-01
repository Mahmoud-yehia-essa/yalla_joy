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
        // Cleanup waiting games that are older than 5 minutes
        OnlineGameInfo::where('game_online_state', 'waiting')
            ->where('created_at', '<=', now()->subMinutes(5))
            ->update(['game_online_state' => 'finished']);

        // Search for an existing waiting search game
        $existingGame = OnlineGameInfo::where('game_online_state', 'waiting')
            ->where('game_online_type', 'search')
            ->first();

        if ($existingGame) {
            // Update the existing game to 'matched'
            $existingGame->update([
                'game_online_state' => 'matched'
            ]);

            return response()->json([
                'onlineGameInfo_id' => $existingGame->id,
                'game_session_name' => $existingGame->game_session_name
            ], 200);
        }

        // If no waiting search game is found, create a new one
        $onlineGameInfo = OnlineGameInfo::create([
            'created_user_id' => $request->created_user_id,
            'online_game_name' => $request->online_game_name,
            'users_count' => $request->users_count,
            'game_session_name' => $request->game_session_name,
            'game_online_type' => $request->game_online_type,
            'game_online_state' => $request->game_online_state,
        ]);

        return response()->json([
            'onlineGameInfo_id' => $onlineGameInfo->id,
            'game_session_name' => $onlineGameInfo->game_session_name
        ], 200);
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
        $limit = min((int)($request->limit ?? 100), 100); // حد أقصى 100 متصدر

        $users = User::where('status', 'active')
            ->where('online_points', '>', 0)
            ->where('role', '!=', 'admin')
            ->orderByDesc('online_points')
            ->take($limit)
            ->get();

        return response()->json([
            'status' => true,
            'total'  => $users->count(),
            'data'   => $users
        ]);
    }
    public function addOnlineWin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // إضافة فوز جديد
        $user->increment('online_game_wins');
        $wins = $user->online_game_wins;

        // جلب الرتب مع العلاقات
        $rankings = \App\Models\RankingNew::with(['rankRewardCoin', 'levelRewardCoin'])->orderBy('rank_order', 'asc')->get();

        $currentRank = null;
        $previousRank = null;
        $baselineWins = 0;

        foreach ($rankings as $rank) {
            if ($wins < $rank->total_wins_to_next_rank) {
                $currentRank = $rank;
                break;
            }
            $previousRank = $rank;
            $baselineWins = $rank->total_wins_to_next_rank;
        }

        if (!$currentRank) {
            $currentRank = $rankings->last();
        }

        $winsInCurrentRank = $wins - $baselineWins;

        $responseMessage = 'Online win added successfully';
        $rewardsReceived = [];
        $currentLevelNum = 1;
        $upgradeType = 'none';

        // تحقق من الترقية لرتبة جديدة أو مستوى جديد
        if ($winsInCurrentRank == 0 && $baselineWins > 0) {
            // ترقية رتبة: أتم الرتبة السابقة للتو ووصل للرتبة الحالية
            $currentLevelNum = 1;
            $upgradeType = 'rank_upgrade';
            
            if ($previousRank) {
                // مكافأة إتمام المستوى الأخير في الرتبة السابقة
                if ($previousRank->level_reward_amount > 0 && $previousRank->level_reward_coin_id) {
                    $rewardsReceived[] = [
                        'reward_type' => 'level_upgrade',
                        'amount' => $previousRank->level_reward_amount,
                        'coin_info' => $previousRank->levelRewardCoin
                    ];
                    
                    \App\Models\UserCoin::create([
                        'user_id' => $user->id,
                        'game_coin_id' => $previousRank->level_reward_coin_id,
                        'coins_number' => $previousRank->level_reward_amount,
                        'type' => 'add'
                    ]);
                }
            }

            // مكافأة الوصول للرتبة الجديدة
            if ($currentRank->rank_reward_amount > 0 && $currentRank->rank_reward_coin_id) {
                $rewardsReceived[] = [
                    'reward_type' => 'rank_upgrade',
                    'amount' => $currentRank->rank_reward_amount,
                    'coin_info' => $currentRank->rankRewardCoin
                ];
                
                \App\Models\UserCoin::create([
                    'user_id' => $user->id,
                    'game_coin_id' => $currentRank->rank_reward_coin_id,
                    'coins_number' => $currentRank->rank_reward_amount,
                    'type' => 'add'
                ]);
            }
            
            $responseMessage = 'مبروك! لقد انتقلت إلى رتبة جديدة: ' . $currentRank->rank_name;
            
        } else {
            // في نفس الرتبة
            $winsToNextLevel = max(1, $currentRank->wins_to_next_level);
            $currentLevelNum = floor($winsInCurrentRank / $winsToNextLevel) + 1;

            if ($winsInCurrentRank > 0 && ($winsInCurrentRank % $winsToNextLevel) == 0) {
                // ترقية مستوى داخل الرتبة
                $upgradeType = 'level_upgrade';

                if ($currentRank->level_reward_amount > 0 && $currentRank->level_reward_coin_id) {
                    $rewardsReceived[] = [
                        'reward_type' => 'level_upgrade',
                        'amount' => $currentRank->level_reward_amount,
                        'coin_info' => $currentRank->levelRewardCoin
                    ];
                    
                    \App\Models\UserCoin::create([
                        'user_id' => $user->id,
                        'game_coin_id' => $currentRank->level_reward_coin_id,
                        'coins_number' => $currentRank->level_reward_amount,
                        'type' => 'add'
                    ]);
                }
                
                $responseMessage = 'مبروك! لقد انتقلت إلى المستوى ' . $currentLevelNum . ' في رتبة ' . $currentRank->rank_name;
            }
        }

        return response()->json([
            'status' => true,
            'message' => $responseMessage,
            'online_game_wins' => $wins,
            'upgrade_type' => $upgradeType,
            'current_rank' => $currentRank,
            'current_level' => [
                'level_number' => $currentLevelNum,
                'wins_required_for_next_level' => $currentRank->wins_to_next_level
            ],
            'rewards_received' => $rewardsReceived
        ]);
    }

    public function addOnlinePlayCount(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // إضافة عدد الألعاب الملعوبة
        $user->increment('online_play_count');

        return response()->json([
            'status' => true,
            'message' => 'Online play count added successfully',
            'online_play_count' => $user->online_play_count
        ]);
    }

}
