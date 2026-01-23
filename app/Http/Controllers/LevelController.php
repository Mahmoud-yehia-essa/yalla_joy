<?php

namespace App\Http\Controllers;

use Monolog\Level;
use App\Models\Levels;
use App\Models\Ranking;
use App\Models\GameCoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LevelController extends Controller
{
    // 🔹 Show all levels
    public function allLevel()
    {
        $levels = Levels::with('gameCoin')->get();
        return view('admin.levels.all_level', compact('levels'));
    }

    // 🔹 Show add level form
    public function addLevel()
    {
        $gameCoins = GameCoin::all();
                $rankings = Ranking::all();

        return view('admin.levels.add_level', compact('gameCoins','rankings'));
    }

    // 🔹 Store level (main insert function)
    public function storeLevel(Request $request)
    {

        // online_points_fixed
        // $request->validate([
        //     'name'          => 'required|string|max:255',
        //     'name_en'       => 'required|string|max:255',
        //     'coins_number'  => 'required|integer|min:0',
        //     'game_coin_id'  => 'required|exists:game_coins,id',
        // ], [
        //     'name.required' => '⚠️ الرجاء إدخال اسم المستوى',
        //     'name_en.required' => '⚠️ الرجاء إدخال اسم المستوى بالإنجليزية',
        //     'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
        //     'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',
        // ]);



         $request->validate([
            'name'          => 'required|string|max:255',
            'name_en'       => 'required|string|max:255',
            'online_points_fixed_start'  => 'required|integer|min:0',
                        'online_points_fixed_end'  => 'required|integer|min:0',

            'ranking_id'  => 'required|exists:rankings,id',

        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم المستوى',
            'name_en.required' => '⚠️ الرجاء إدخال اسم المستوى بالإنجليزية',
            'online_points_fixed.required' => '⚠️ الرجاء إدخال عدد العملات',
            'ranking_id.required' => '⚠️ الرجاء اختيار الرتبة الخاصة بالمستوى',

        ]);

        Levels::create([
            'name'            => $request->name,
            'name_en'         => $request->name_en,
            'description'     => $request->description,
            'description_en'  => $request->description_en,
            'online_points_fixed_start'    => $request->online_points_fixed_start,
            'online_points_fixed_end'    => $request->online_points_fixed_end,

            'ranking_id'    => $request->ranking_id,
            'status'          => 'active',
        ]);

        return redirect()->route('all.level')->with('success', '✅ تم إضافة المستوى بنجاح');
    }

    // 🔹 Alternative save (like your saveGameCoin)
    public function saveLevel(Request $request)
    {
        $this->storeLevel($request); // reuse validation & insert logic
        return redirect()->route('all.level')->with('success', '✅ تم حفظ المستوى بنجاح');
    }

    // 🔹 Edit level form
    public function editLevel($id)
    {
        $level = Levels::findOrFail($id);
        $gameCoins = GameCoin::all();
                        $rankings = Ranking::all();

        return view('admin.levels.edit_level', compact('level', 'gameCoins','rankings'));
    }

    // 🔹 Update level
    public function updateLevel(Request $request)
    {
        // $request->validate([
        //     // 'id'            => 'required|exists:levels,id',
        //     'name'          => 'required|string|max:255',
        //     'name_en'       => 'required|string|max:255',
        //     'coins_number'  => 'required|integer|min:0',
        //     'game_coin_id'  => 'required|exists:game_coins,id',
        // ]);


         $request->validate([
            // 'id'            => 'required|exists:levels,id',
            'name'          => 'required|string|max:255',
            'name_en'       => 'required|string|max:255',
            'online_points_fixed_start'  => 'required|integer|min:0',
                        'online_points_fixed_end'  => 'required|integer|min:0',

            'ranking_id'  => 'required|exists:rankings,id',


        ]);

        $level = Levels::findOrFail($request->id);
        $level->update([
            'name'            => $request->name,
            'name_en'         => $request->name_en,
            'description'     => $request->description,
            'description_en'  => $request->description_en,
        'online_points_fixed_start'    => $request->online_points_fixed_start,

             'online_points_fixed_end'    => $request->online_points_fixed_end,


                                    'ranking_id'    => $request->ranking_id,


        ]);

        return redirect()->route('all.level')->with('success', '✅ تم تعديل المستوى بنجاح');
    }

    // 🔹 Delete level
    public function deleteLevel($id)
    {
        Levels::findOrFail($id)->delete();
        return redirect()->route('all.level')->with('success', '🗑️ تم حذف المستوى بنجاح');
    }

    // 🔹 Make level inactive
    public function levelInactive($id)
    {
        $level = Levels::findOrFail($id);
        $level->status = 'inactive';
        $level->save();

        return redirect()->back()->with('success', '👁️ تم إخفاء المستوى');
    }

    // 🔹 Make level active
    public function levelActive($id)
    {
        $level = Levels::findOrFail($id);
        $level->status = 'active';
        $level->save();

        return redirect()->back()->with('success', '✅ تم إظهار المستوى');
    }


    // API //


// public function getLevelByPoints(Request $request)
// {
//     $request->validate([
//         'online_points' => 'required|integer|min:0',
//     ]);

//     $points = $request->online_points;

//     $level = Levels::where('status', 'active')
//         ->where('online_points_fixed_start', '<=', $points)
//         ->where('online_points_fixed_end', '>=', $points)
//         ->first();

//     if (!$level) {
//         return response()->json([
//             'status' => false,
//             'message' => 'No level found for these points'
//         ], 404);
//     }

//     return response()->json([
//         'status' => true,
//         'data' => $level
//     ]);
// }







// public function getLevelByPoints(Request $request)
// {
//     $request->validate([
//         'online_points' => 'required|integer|min:0',
//     ]);

//     $points = $request->online_points;

//     // المستوى الحالي
//     $level = Levels::with('ranking')
//         ->where('status', 'active')
//         ->where('online_points_fixed_start', '<=', $points)
//         ->where('online_points_fixed_end', '>=', $points)
//         ->first();

//     if (!$level) {
//         return response()->json(null, 404);
//     }

//     // المستوى التالي
//     $nextLevel = Levels::where('status', 'active')
//         ->where('online_points_fixed_start', '>', $level->online_points_fixed_end)
//         ->orderBy('online_points_fixed_start', 'asc')
//         ->first();

//     // حساب النقاط المطلوبة للمستوى التالي
//     if ($nextLevel) {
//         $pointsToNextLevel = $nextLevel->online_points_fixed_start - $points;
//     } else {
//         // آخر مستوى
//         $pointsToNextLevel = 0;
//     }

//     // إضافة القيم للـ response
//     $level->points_to_next_level = $pointsToNextLevel;
//     $level->next_level = $nextLevel; // لو حابب ترجع بياناته كمان

//     return response()->json($level);
// }



public function getLevelByPoints(Request $request)
{
    $request->validate([
        'online_points' => 'required|integer|min:0',
    ]);

    $points = $request->online_points;

    // المستوى الحالي
    $level = Levels::with('ranking')
        ->where('status', 'active')
        ->where('online_points_fixed_start', '<=', $points)
        ->where('online_points_fixed_end', '>=', $points)
        ->first();

    // لو النقاط أكبر من كل الرينجات (تعدّى آخر مستوى)
    if (!$level) {
        $level = Levels::with('ranking')
            ->where('status', 'active')
            ->orderBy('online_points_fixed_end', 'desc')
            ->first();
    }

    if (!$level) {
        return response()->json(null, 404);
    }

    // المستوى التالي
    $nextLevel = Levels::where('status', 'active')
        ->where('online_points_fixed_start', '>', $level->online_points_fixed_end)
        ->orderBy('online_points_fixed_start', 'asc')
        ->first();

    if ($nextLevel) {
        $pointsToNextLevel = $nextLevel->online_points_fixed_start - $points;
        if ($pointsToNextLevel < 0) $pointsToNextLevel = 0;
        $level->next_level = $nextLevel; // اختياري
    } else {
        // آخر مستوى
        $pointsToNextLevel = 0;
        $level->next_level = null; // اختياري
                // $level->next_level = "non"; // اختياري

    }

    $level->points_to_next_level = $pointsToNextLevel;

    return response()->json($level);
}
}
