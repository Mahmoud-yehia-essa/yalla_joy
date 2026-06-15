<?php

namespace App\Http\Controllers;

use App\Models\RankingNew;
use App\Models\GameCoin;
use App\Models\User;
use Illuminate\Http\Request;

class RankingNewController extends Controller
{
    public function allRankings()
    {
        $rankings = RankingNew::with(['rankRewardCoin', 'levelRewardCoin'])->oldest()->get();
        return view('admin.rankings_new.all_rankings', compact('rankings'));
    }

    public function addRanking()
    {
        $coins = GameCoin::where('status', 'active')->get();
        return view('admin.rankings_new.add_ranking', compact('coins'));
    }

    public function storeRanking(Request $request)
    {
        $request->validate([
            'rank_name' => 'required|string|max:255',
            'rank_name_en' => 'required|string|max:255',
            'rank_order' => 'required|integer',
            'levels_count' => 'required|integer|min:0',
        ], [
            'rank_name.required' => '⚠️ الرجاء ادخال اسم الرتبة بالعربي',
            'rank_name_en.required' => '⚠️ الرجاء ادخال اسم الرتبة بالانجليزي',
            'rank_order.required' => '⚠️ الرجاء ادخال ترتيب الرتبة',
            'levels_count.required' => '⚠️ الرجاء ادخال عدد مستويات الرتبة',
        ]);

        RankingNew::create([
            'rank_name' => $request->rank_name,
            'rank_name_en' => $request->rank_name_en,
            'rank_description' => $request->rank_description,
            'rank_description_en' => $request->rank_description_en,
            'rank_order' => $request->rank_order,
            'rank_reward_coin_id' => $request->rank_reward_coin_id,
            'rank_reward_amount' => $request->rank_reward_amount,
            'levels_count' => $request->levels_count,
            'wins_to_next_level' => $request->wins_to_next_level ?? 0,
            'is_last' => $request->has('is_last') ? 1 : 0,
            'is_free' => $request->has('is_free') ? 1 : 0,
            'level_reward_coin_id' => $request->level_reward_coin_id,
            'level_reward_amount' => $request->level_reward_amount,
        ]);

        $notification = [
            'message' => 'تم اضافة الرتبة بنجاح',
            'alert-type' => 'success'
        ];

        $this->recalculateTotalWins();

        return redirect()->route('all.rankings.new')->with($notification);
    }

    public function editRanking($id)
    {
        $ranking = RankingNew::findOrFail($id);
        $coins = GameCoin::where('status', 'active')->get();
        return view('admin.rankings_new.edit_ranking', compact('ranking', 'coins'));
    }

    public function updateRanking(Request $request)
    {
        $request->validate([
            'rank_name' => 'required|string|max:255',
            'rank_name_en' => 'required|string|max:255',
            'rank_order' => 'required|integer',
            'levels_count' => 'required|integer|min:0',
        ], [
            'rank_name.required' => '⚠️ الرجاء ادخال اسم الرتبة بالعربي',
            'rank_name_en.required' => '⚠️ الرجاء ادخال اسم الرتبة بالانجليزي',
            'rank_order.required' => '⚠️ الرجاء ادخال ترتيب الرتبة',
            'levels_count.required' => '⚠️ الرجاء ادخال عدد مستويات الرتبة',
        ]);

        RankingNew::findOrFail($request->id)->update([
            'rank_name' => $request->rank_name,
            'rank_name_en' => $request->rank_name_en,
            'rank_description' => $request->rank_description,
            'rank_description_en' => $request->rank_description_en,
            'rank_order' => $request->rank_order,
            'rank_reward_coin_id' => $request->rank_reward_coin_id,
            'rank_reward_amount' => $request->rank_reward_amount,
            'levels_count' => $request->levels_count,
            'wins_to_next_level' => $request->wins_to_next_level ?? 0,
            'is_last' => $request->has('is_last') ? 1 : 0,
            'is_free' => $request->has('is_free') ? 1 : 0,
            'level_reward_coin_id' => $request->level_reward_coin_id,
            'level_reward_amount' => $request->level_reward_amount,
        ]);

        $notification = [
            'message' => 'تم تعديل الرتبة بنجاح',
            'alert-type' => 'success'
        ];

        $this->recalculateTotalWins();

        return redirect()->route('all.rankings.new')->with($notification);
    }

    public function deleteRanking($id)
    {
        RankingNew::findOrFail($id)->delete();

        $notification = [
            'message' => 'تم حذف الرتبة بنجاح',
            'alert-type' => 'success'
        ];

        $this->recalculateTotalWins();

        return redirect()->back()->with($notification);
    }

    // ============================================================
    //  API
    // ============================================================

    /**
     * POST /api/user/rank
     * body: { "user_id": 5 }
     *
     * يجلب online_game_wins للمستخدم ثم يعيد الرتبة المناسبة
     * بناءً على total_wins_to_next_rank من جدول rankings_new.
     *
     * منطق الاختيار:
     *   - نرتّب الرتب تصاعدياً بـ rank_order.
     *   - الرتبة الحالية هي أول رتبة يكون إجمالي الانتصارات المطلوبة لتخطيها أكبر من عدد انتصارات المستخدم.
     *   - إذا تخطى المستخدم جميع الرتب نعطيه الرتبة الأخيرة.
     */
    public function getUserRankApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $wins = (int) $user->online_game_wins;

        // جلب كل الرتب مرتّبة تصاعدياً
        $rankings = RankingNew::with(['rankRewardCoin', 'levelRewardCoin'])
            ->orderBy('rank_order', 'asc')
            ->get();

        if ($rankings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No ranks found',
            ], 200);
        }

        // الرتبة المناسبة: أول رتبة يكون عدد الانتصارات أقل من total_wins_to_next_rank الخاص بها
        $currentRank = null;
        $previousRankTotalWins = 0;
        foreach ($rankings as $rank) {
            if ($wins < $rank->total_wins_to_next_rank) {
                $currentRank = $rank;
                break;
            }
            $previousRankTotalWins = $rank->total_wins_to_next_rank;
        }

        // إذا تخطى المستخدم كل الرتب (أو لم يجد أي رتبة تطابق الشرط)
        if (!$currentRank) {
            $currentRank = $rankings->last();
            // في حالة الرتبة الأخيرة، نحتاج إجمالي الانتصارات قبلها
            if ($rankings->count() > 1) {
                $previousRankTotalWins = $rankings[$rankings->count() - 2]->total_wins_to_next_rank;
            } else {
                $previousRankTotalWins = 0;
            }
        }

        // حساب المستوى داخل الرتبة
        $userWinsInRank = $wins - $previousRankTotalWins;
        $winsPerLevel = (int) $currentRank->wins_to_next_level;
        
        $currentLevel = 1;
        $winsInCurrentLevel = 0;
        $winsToNextLevelInCurrentLevel = 0;

        if ($winsPerLevel > 0) {
            $currentLevel = (int) floor($userWinsInRank / $winsPerLevel) + 1;
            
            // التأكد من عدم تجاوز عدد المستويات المتاحة في الرتبة
            if ($currentLevel > $currentRank->levels_count) {
                $currentLevel = $currentRank->levels_count;
                $winsInCurrentLevel = $winsPerLevel; 
                $winsToNextLevelInCurrentLevel = 0;
            } else {
                $winsInCurrentLevel = $userWinsInRank % $winsPerLevel;
                $winsToNextLevelInCurrentLevel = $winsPerLevel - $winsInCurrentLevel;
            }
        }

        // الرتبة التالية (الرتبة ذات rank_order أكبر مباشرةً)
        $nextRank = $rankings->where('rank_order', '>', $currentRank->rank_order)->first();

        // الانتصارات المطلوبة للوصول للرتبة التالية
        if ($nextRank) {
            $winsToNextRank = max(0, $currentRank->total_wins_to_next_rank - $wins);
        } else {
            $winsToNextRank = 0;
        }

        // ترتيب المستخدم بناءً على النقاط
        $userPosition = User::where('online_points_fixed', '>', (int)$user->online_points_fixed)->count() + 1;

        return response()->json([
            'success'               => true,
            'user_id'               => $user->id,
            'wins_count'            => $wins,
            'current_rank'          => $currentRank,
            'current_level'         => $currentLevel,
            'wins_in_current_level' => $winsInCurrentLevel,
            'wins_to_next_level'    => $winsToNextLevelInCurrentLevel,
            'next_rank'             => $nextRank,
            'wins_to_next_rank'     => $winsToNextRank,
            'online_play_count'     => (int) $user->online_play_count,
            'online_game_wins'      => (int) $user->online_game_wins,
            'user_position'         => $userPosition,
        ], 200);
    }

    private function recalculateTotalWins()
    {
        $rankings = RankingNew::orderBy('rank_order', 'asc')->get();
        $accumulatedWins = 0;

        foreach ($rankings as $rank) {
            $rankWins = $rank->levels_count * $rank->wins_to_next_level;
            $accumulatedWins += $rankWins;

            $rank->update(['total_wins_to_next_rank' => $accumulatedWins]);
        }
    }
}

