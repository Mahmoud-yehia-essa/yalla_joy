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

        $previousWins = (int) $user->online_game_wins;
        // إضافة فوز جديد
        $user->increment('online_game_wins');
        $wins = (int) $user->online_game_wins;

        // إضافة نقاط الفوز المحددة في الإعدادات للفائز في لعبة الميدان كبديل لنقاط الأسئلة
        $appSetting = \App\Models\AppVersion::first();
        $winPoints = $appSetting && isset($appSetting->online_game_win_points)
            ? (int) $appSetting->online_game_win_points
            : 6;

        if ($winPoints > 0) {
            $user->increment('online_points', $winPoints);
            $user->increment('online_points_fixed', $winPoints);
        }

        // جلب الرتب مع العلاقات مرتبة تصاعدياً
        $rankings = \App\Models\RankingNew::with(['rankRewardCoin', 'levelRewardCoin'])
            ->orderBy('rank_order', 'asc')
            ->get();

        if ($rankings->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Online win added successfully',
                'online_game_wins' => $wins,
                'upgrade_type' => 'none',
                'current_rank' => null,
                'current_level' => null,
                'rewards_received' => []
            ]);
        }

        // تحديد الرتبة التي كان فيها المستخدم قبل هذا الفوز (الرتبة التي ساهم هذا الفوز فيها)
        $activeRank = null;
        $activeRankBaselineWins = 0;

        foreach ($rankings as $rank) {
            if ($previousWins < $rank->total_wins_to_next_rank) {
                $activeRank = $rank;
                break;
            }
            $activeRankBaselineWins = $rank->total_wins_to_next_rank;
        }

        if (!$activeRank) {
            $activeRank = $rankings->last();
            if ($rankings->count() > 1) {
                $activeRankBaselineWins = $rankings[$rankings->count() - 2]->total_wins_to_next_rank;
            } else {
                $activeRankBaselineWins = 0;
            }
        }

        // حساب التقدم داخل الرتبة
        $winsToNextLevel = max(1, (int) $activeRank->wins_to_next_level);
        $levelRewardAmount = (int) $activeRank->level_reward_amount;
        $levelsCount = max(1, (int) $activeRank->levels_count);

        $winsInRankBefore = $previousWins - $activeRankBaselineWins;
        $levelNumberBefore = (int) floor($winsInRankBefore / $winsToNextLevel) + 1;
        if ($levelNumberBefore > $levelsCount) {
            $levelNumberBefore = $levelsCount;
        }

        // ترتيب هذا الفوز داخل المستوى الحالي (من 1 إلى $winsToNextLevel)
        $winInCurrentLevel = ($winsInRankBefore % $winsToNextLevel) + 1;

        // حساب نصيب هذا الفوز من عملات المستوى بالتوزيع الدقيق والعادل
        $cumulativeBefore = (int) floor(($levelRewardAmount * ($winInCurrentLevel - 1)) / $winsToNextLevel);
        $cumulativeAfter = (int) floor(($levelRewardAmount * $winInCurrentLevel) / $winsToNextLevel);
        $coinsForThisWin = max(0, $cumulativeAfter - $cumulativeBefore);

        $isLevelCompleted = ($winInCurrentLevel == $winsToNextLevel);

        $responseMessage = 'Online win added successfully';
        $rewardsReceived = [];
        $upgradeType = 'none';

        // 1. إضافة عملات الفوز الحالي للمستوى
        if ($coinsForThisWin > 0 && $activeRank->level_reward_coin_id) {
            \App\Models\UserCoin::create([
                'user_id' => $user->id,
                'game_coin_id' => $activeRank->level_reward_coin_id,
                'coins_number' => $coinsForThisWin,
                'type' => 'add'
            ]);

            $rewardsReceived[] = [
                'reward_type' => $isLevelCompleted ? 'level_upgrade' : 'level_reward',
                'amount' => $coinsForThisWin,
                'coin_info' => $activeRank->levelRewardCoin
            ];
        }

        // 2. التحقق من اكتمال المستوى أو اكتمال الرتبة
        if ($isLevelCompleted) {
            if ($levelNumberBefore < $levelsCount) {
                // ترقية لمستوى جديد داخل نفس الرتبة
                $upgradeType = 'level_upgrade';
                $nextLevelNum = $levelNumberBefore + 1;
                $responseMessage = 'مبروك! لقد انتقلت إلى المستوى ' . $nextLevelNum . ' في رتبة ' . $activeRank->rank_name;
            } else {
                // إتمام آخر مستوى في الرتبة والانتقال للرتبة التالية
                $nextRank = $rankings->where('rank_order', '>', $activeRank->rank_order)->first();

                if ($nextRank) {
                    $upgradeType = 'rank_upgrade';
                    $responseMessage = 'مبروك! لقد انتقلت إلى رتبة جديدة: ' . $nextRank->rank_name;

                    // مكافأة الوصول للرتبة الجديدة (العملات / النقاط المقررة للرتبة)
                    if ($nextRank->rank_reward_amount > 0 && $nextRank->rank_reward_coin_id) {
                        \App\Models\UserCoin::create([
                            'user_id' => $user->id,
                            'game_coin_id' => $nextRank->rank_reward_coin_id,
                            'coins_number' => $nextRank->rank_reward_amount,
                            'type' => 'add'
                        ]);

                        $rewardsReceived[] = [
                            'reward_type' => 'rank_upgrade',
                            'amount' => $nextRank->rank_reward_amount,
                            'coin_info' => $nextRank->rankRewardCoin
                        ];
                    }
                } else {
                    $upgradeType = 'level_upgrade';
                    $responseMessage = 'مبروك! لقد أتممت جميع المستويات في رتبة ' . $activeRank->rank_name;
                }
            }
        } else {
            // فوز مرحلي داخل المستوى
            $responseMessage = 'مبروك! فوز جديد وحصلت على ' . $coinsForThisWin . ' عملة';
        }

        // 3. حساب الرتبة والمستوى الحالي بعد الفوز
        $currentRank = null;
        $baselineWinsAfter = 0;
        foreach ($rankings as $rank) {
            if ($wins < $rank->total_wins_to_next_rank) {
                $currentRank = $rank;
                break;
            }
            $baselineWinsAfter = $rank->total_wins_to_next_rank;
        }

        if (!$currentRank) {
            $currentRank = $rankings->last();
            if ($rankings->count() > 1) {
                $baselineWinsAfter = $rankings[$rankings->count() - 2]->total_wins_to_next_rank;
            } else {
                $baselineWinsAfter = 0;
            }
        }

        $winsInCurrentRankAfter = $wins - $baselineWinsAfter;
        $winsPerLevelAfter = max(1, (int) $currentRank->wins_to_next_level);
        $currentLevelNum = (int) floor($winsInCurrentRankAfter / $winsPerLevelAfter) + 1;
        if ($currentLevelNum > $currentRank->levels_count) {
            $currentLevelNum = $currentRank->levels_count;
        }

        return response()->json([
            'status' => true,
            'message' => $responseMessage,
            'online_game_wins' => $wins,
            'points_awarded' => $winPoints,
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
