<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Levels;
use App\Models\GameCoin;

class LevelController extends Controller
{
    // 🔹 Show all levels
    public function allLevel()
    {
        $levels = Levels::with('gameCoin')->latest()->get();
        return view('admin.levels.all_level', compact('levels'));
    }

    // 🔹 Show add level form
    public function addLevel()
    {
        $gameCoins = GameCoin::all();
        return view('admin.levels.add_level', compact('gameCoins'));
    }

    // 🔹 Store level (main insert function)
    public function storeLevel(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'name_en'       => 'required|string|max:255',
            'coins_number'  => 'required|integer|min:0',
            'game_coin_id'  => 'required|exists:game_coins,id',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم المستوى',
            'name_en.required' => '⚠️ الرجاء إدخال اسم المستوى بالإنجليزية',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
            'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',
        ]);

        Levels::create([
            'name'            => $request->name,
            'name_en'         => $request->name_en,
            'description'     => $request->description,
            'description_en'  => $request->description_en,
            'coins_number'    => $request->coins_number,
            'game_coin_id'    => $request->game_coin_id,
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
        return view('admin.levels.edit_level', compact('level', 'gameCoins'));
    }

    // 🔹 Update level
    public function updateLevel(Request $request)
    {
        $request->validate([
            // 'id'            => 'required|exists:levels,id',
            'name'          => 'required|string|max:255',
            'name_en'       => 'required|string|max:255',
            'coins_number'  => 'required|integer|min:0',
            'game_coin_id'  => 'required|exists:game_coins,id',
        ]);

        $level = Levels::findOrFail($request->id);
        $level->update([
            'name'            => $request->name,
            'name_en'         => $request->name_en,
            'description'     => $request->description,
            'description_en'  => $request->description_en,
            'coins_number'    => $request->coins_number,
            'game_coin_id'    => $request->game_coin_id,
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
}
