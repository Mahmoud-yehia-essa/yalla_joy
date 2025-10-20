<?php

namespace App\Http\Controllers;

use App\Models\Levels;
use App\Models\GameHelper;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GameHelperController extends Controller
{
    // 🔹 Show all game helpers
    public function allGameHelper()
    {
        $gameHelpers = GameHelper::latest()->get();
        return view('admin.game_helper.all_game_helper', compact('gameHelpers'));
    }

    // 🔹 Show add form
    public function addGameHelper()
    {

                $levels = Levels::latest()->get();

        return view('admin.game_helper.add_game_helper',compact('levels'));
    }

    // 🔹 Store game helper
    public function storeGameHelper(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم المساعد',
            'name_en.required' => '⚠️ الرجاء اضافة الاسم بالانجليزية',
        ]);

        $save_url = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $path = public_path('upload/game_helper/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/game_helper/' . $name_gen;
        }

        GameHelper::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'level_id'=>$request->level_id,
            'photo' => $save_url,
        ]);

        return redirect()->route('all.game.helper')->with('success', '✅ تم إضافة المساعد بنجاح');
    }

    // 🔹 Show edit form
    public function editGameHelper($id)
    {
        $gameHelper = GameHelper::findOrFail($id);
                        $levels = Levels::latest()->get();

        return view('admin.game_helper.edit_game_helper', compact('gameHelper','levels'));
    }

    // 🔹 Update game helper
    public function updateGameHelper(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:game_helpers,id',
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم المساعد',
            'name_en.required' => '⚠️ الرجاء اضافة الاسم بالانجليزية',
        ]);

        $gameHelper = GameHelper::findOrFail($request->id);
        $save_url = $gameHelper->photo;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $path = public_path('upload/game_helper/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            if ($gameHelper->photo && file_exists($gameHelper->photo)) unlink($gameHelper->photo);
            $save_url = 'upload/game_helper/' . $name_gen;
        }

        $gameHelper->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
                        'level_id'=>$request->level_id,

            'photo' => $save_url,
        ]);

        return redirect()->route('all.game.helper')->with('success', '✅ تم تعديل المساعد بنجاح');
    }

    // 🔹 Delete game helper
    public function deleteGameHelper($id)
    {
        $gameHelper = GameHelper::findOrFail($id);
        if ($gameHelper->photo && file_exists($gameHelper->photo)) unlink($gameHelper->photo);
        $gameHelper->delete();

        return redirect()->route('all.game.helper')->with('success', '🗑️ تم حذف المساعد بنجاح');
    }


  // 🔹 Make Game Helper inactive
public function gameHelperInactive($id)
{
    $helper = GameHelper::findOrFail($id);
    $helper->status = 'inactive';
    $helper->save();

    return redirect()->back()->with('success', '👁️ تم إخفاء المساعدة');
}

// 🔹 Make Game Helper active
public function gameHelperActive($id)
{
    $helper = GameHelper::findOrFail($id);
    $helper->status = 'active';
    $helper->save();

    return redirect()->back()->with('success', '✅ تم إظهار المساعدة');
}

}
