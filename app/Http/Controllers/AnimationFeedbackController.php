<?php

namespace App\Http\Controllers;

use App\Models\AnimationFeedback;
use App\Models\RankingNew;
use App\Models\GameCoin;
use App\Models\AnimationUserLibrary;
use Illuminate\Http\Request;
use App\Exports\AnimationFeedbackExport;
use Maatwebsite\Excel\Facades\Excel;
use File;

class AnimationFeedbackController extends Controller
{
    public function allAnimation()
    {
        $animations = AnimationFeedback::with(['rankingNew', 'coin'])->latest()->get();
        return view('admin.animation_feedback.all_animation_feedback', compact('animations'));
    }

    public function addAnimation()
    {
        $rankings = RankingNew::all();
        $coins = GameCoin::where('status', 'active')->get();
        return view('admin.animation_feedback.add_animation_feedback', compact('rankings', 'coins'));
    }

    public function storeAnimation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => 'required|in:positive,negative',
            'ranking_new_id' => 'required|exists:rankings_new,id',
            'file_path' => 'required|file',
        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم الحركة',
            'name_en.required' => '⚠️ الرجاء اضافة اسم الحركة بالانجليزية',
            'type.required' => '⚠️ الرجاء تحديد نوع الحركة',
            'ranking_new_id.required' => '⚠️ الرجاء اختيار الرتبة',
            'file_path.required' => '⚠️ الرجاء رفع ملف الحركة',
        ]);

        $save_url = null;
        if ($request->file('file_path')) {
            $file = $request->file('file_path');
            $name_gen = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/animations'), $name_gen);
            $save_url = 'upload/animations/' . $name_gen;
        }

        $audio_url = null;
        if ($request->file('audio')) {
            $file = $request->file('audio');
            $name_gen = hexdec(uniqid()) . '_audio.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/animations_audio'), $name_gen);
            $audio_url = 'upload/animations_audio/' . $name_gen;
        }

        AnimationFeedback::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'type' => $request->type,
            'ranking_new_id' => $request->ranking_new_id,
            'is_free' => $request->has('is_free') ? 1 : 0,
            'coin_id' => $request->has('is_free') ? null : $request->coin_id,
            'coin_amount' => $request->has('is_free') ? null : $request->coin_amount,
            'file_path' => $save_url,
            'audio' => $audio_url,
        ]);

        $notification = [
            'message' => 'تم اضافة الحركة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.animation')->with($notification);
    }

    public function editAnimation($id)
    {
        $animation = AnimationFeedback::findOrFail($id);
        $rankings = RankingNew::all();
        $coins = GameCoin::where('status', 'active')->get();
        return view('admin.animation_feedback.edit_animation_feedback', compact('animation', 'rankings', 'coins'));
    }

    public function updateAnimation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => 'required|in:positive,negative',
            'ranking_new_id' => 'required|exists:rankings_new,id',
        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم الحركة',
            'name_en.required' => '⚠️ الرجاء اضافة اسم الحركة بالانجليزية',
            'type.required' => '⚠️ الرجاء تحديد نوع الحركة',
            'ranking_new_id.required' => '⚠️ الرجاء اختيار الرتبة',
        ]);

        $animation = AnimationFeedback::findOrFail($request->id);

        if ($request->file('file_path')) {
            // Delete old file
            if (File::exists(public_path($animation->file_path))) {
                File::delete(public_path($animation->file_path));
            }

            $file = $request->file('file_path');
            $name_gen = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/animations'), $name_gen);
            $save_url = 'upload/animations/' . $name_gen;
            
            $animation->update(['file_path' => $save_url]);
        }

        if ($request->file('audio')) {
            // Delete old file
            if (File::exists(public_path($animation->audio))) {
                File::delete(public_path($animation->audio));
            }

            $file = $request->file('audio');
            $name_gen = hexdec(uniqid()) . '_audio.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/animations_audio'), $name_gen);
            $audio_url = 'upload/animations_audio/' . $name_gen;
            
            $animation->update(['audio' => $audio_url]);
        }

        $animation->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'type' => $request->type,
            'ranking_new_id' => $request->ranking_new_id,
            'is_free' => $request->has('is_free') ? 1 : 0,
            'coin_id' => $request->has('is_free') ? null : $request->coin_id,
            'coin_amount' => $request->has('is_free') ? null : $request->coin_amount,
        ]);

        $notification = [
            'message' => 'تم تعديل الحركة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.animation')->with($notification);
    }

    public function deleteAnimation($id)
    {
        $animation = AnimationFeedback::findOrFail($id);
        
        if (File::exists(public_path($animation->file_path))) {
            File::delete(public_path($animation->file_path));
        }

        if (File::exists(public_path($animation->audio))) {
            File::delete(public_path($animation->audio));
        }

        $animation->delete();

        $notification = [
            'message' => 'تم حذف الحركة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // ============================================================
    //  API
    // ============================================================

    /**
     * POST /api/animations/by-rank
     * body: { "ranking_new_id": 2 }
     *
     * يُعيد جميع حركات الأنيميشن التابعة للرتبة المحددة.
     */
    public function getAnimationsByRankApi(Request $request)
    {
        $request->validate([
            'ranking_new_id' => 'required|integer|exists:rankings_new,id',
        ]);

        $animations = AnimationFeedback::with('coin')->where('ranking_new_id', $request->ranking_new_id)->get();

        if ($animations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No animations found for this rank',
                'data'    => [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data'    => $animations,
        ], 200);
    }

    /**
     * POST /api/user/animations
     * body: { "user_id": 5 }
     *
     * يُعيد جميع الحركات مع حالة إتاحتها للمستخدم بناءً على رتبته الحالية.
     */
    public function getUserAnimationsApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        $wins = (int) $user->online_game_wins;

        // Get all rankings ordered by rank_order to determine the user's current rank
        $rankings = \App\Models\RankingNew::with(['rankRewardCoin', 'levelRewardCoin'])
            ->orderBy('rank_order', 'asc')
            ->get();

        if ($rankings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No ranks found',
            ], 200);
        }

        $currentRank = null;
        foreach ($rankings as $rank) {
            if ($wins < $rank->total_wins_to_next_rank) {
                $currentRank = $rank;
                break;
            }
        }

        if (!$currentRank) {
            $currentRank = $rankings->last();
        }

        // Get all animations
        $animations = AnimationFeedback::with('coin')->get();

        // Get user library animation IDs
        $userLibraryIds = AnimationUserLibrary::where('user_id', $user->id)
            ->pluck('animation_feedback_id')
            ->toArray();

        // Add availability flag and animations to each rank
        $rankings->map(function ($rank) use ($currentRank, $animations, $userLibraryIds) {
            $isAvailable = $rank->rank_order <= $currentRank->rank_order;
            $isCurrentRank = $rank->id === $currentRank->id;
            
            $rank->setAttribute('is_available', $isAvailable);
            $rank->setAttribute('is_current_rank', $isCurrentRank);
            
            // Get animations for this rank and add is_in_library flag
            $rankAnimations = $animations->where('ranking_new_id', $rank->id)->values();
            
            $rankAnimations->each(function ($animation) use ($userLibraryIds) {
                $animation->setAttribute('is_in_library', in_array($animation->id, $userLibraryIds));
            });

            $rank->setAttribute('animations', $rankAnimations);
            
            return $rank;
        });

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'data'    => $rankings,
        ], 200);
    }

    public function previewAnimation($id)
    {
        $animation = AnimationFeedback::with(['rankingNew', 'coin'])->findOrFail($id);
        return view('admin.animation_feedback.preview', compact('animation'));
    }

    public function exportAnimation()
    {
        return Excel::download(new AnimationFeedbackExport, 'animations_export_' . date('Y_m_d_His') . '.xlsx');
    }
}
