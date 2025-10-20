<?php

namespace App\Http\Controllers;

use App\Models\GameItem;
use App\Models\ItemType;
use App\Models\GameCoin;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GameItemController extends Controller
{
    // ✅ جميع العناصر
    public function allGameItem()
    {
        $gameItems = GameItem::with(['itemType', 'gameCoin'])->latest()->get();
        return view('admin.game_item.all_game_item', compact('gameItems'));
    }

    // ✅ صفحة الإضافة
    public function addGameItem()
    {
        $itemTypes = ItemType::all();
        $gameCoins = GameCoin::all();
        return view('admin.game_item.add_game_item', compact('itemTypes', 'gameCoins'));
    }

    // ✅ حفظ عنصر جديد
    public function storeGameItem(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'item_type_id' => 'required|exists:item_types,id',
            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
            'photo'      => 'required|image|mimes:jpeg,png,jpg,gif',
        ], [
            'name.required'    => '⚠️ الرجاء إدخال اسم العنصر',
            'name_en.required' => '⚠️ الرجاء إدخال اسم العنصر بالإنجليزية',
            'item_type_id.required' => '⚠️ الرجاء اختيار نوع العنصر',
            'game_coin_id.required' => '⚠️ الرجاء اختيار العملة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
            'photo.required'   => '⚠️ الرجاء رفع صورة للعنصر',
            'photo.image'      => '⚠️ يجب أن يكون الملف صورة',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/game_items/');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/game_items/' . $name_gen;
        }

        GameItem::create([
            'item_type_id' => $request->item_type_id,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
            'name'         => $request->name,
            'name_en'      => $request->name_en,
            'description'  => $request->description,
            'description_en' => $request->description_en,
            'photo'        => $save_url ?? null,
        ]);

        return redirect()->route('all.game.item')->with('success', '✅ تم إضافة عنصر اللعبة بنجاح');
    }

    // ✅ صفحة التعديل
    public function editGameItem($id)
    {
        $gameItem = GameItem::findOrFail($id);
        $itemTypes = ItemType::all();
        $gameCoins = GameCoin::all();
        return view('admin.game_item.edit_game_item', compact('gameItem', 'itemTypes', 'gameCoins'));
    }

    // ✅ تحديث عنصر اللعبة
    public function updateGameItem(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'item_type_id' => 'required|exists:item_types,id',
            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
            'photo'      => 'image|mimes:jpeg,png,jpg,gif',
        ], [
            'name.required'    => '⚠️ الرجاء إدخال اسم العنصر',
            'name_en.required' => '⚠️ الرجاء إدخال اسم العنصر بالإنجليزية',
        ]);

        $gameItem = GameItem::findOrFail($id);
        $old_img = $gameItem->photo;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/game_items/');

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/game_items/' . $name_gen;

            if ($old_img && file_exists(public_path($old_img))) {
                unlink(public_path($old_img));
            }

            $gameItem->update([
                'item_type_id' => $request->item_type_id,
                'game_coin_id' => $request->game_coin_id,
                'coins_number' => $request->coins_number,
                'name'         => $request->name,
                'name_en'      => $request->name_en,
                'description'  => $request->description,
                'description_en' => $request->description_en,
                'photo'        => $save_url,
            ]);
        } else {
            $gameItem->update([
                'item_type_id' => $request->item_type_id,
                'game_coin_id' => $request->game_coin_id,
                'coins_number' => $request->coins_number,
                'name'         => $request->name,
                'name_en'      => $request->name_en,
                'description'  => $request->description,
                'description_en' => $request->description_en,
            ]);
        }

        return redirect()->route('all.game.item')->with('success', '✅ تم تعديل عنصر اللعبة بنجاح');
    }

    // ✅ حذف عنصر
    public function deleteGameItem($id)
    {
        $gameItem = GameItem::findOrFail($id);

        if ($gameItem->photo && file_exists(public_path($gameItem->photo))) {
            unlink(public_path($gameItem->photo));
        }

        $gameItem->delete();

        return redirect()->route('all.game.item')->with('success', '🗑️ تم حذف عنصر اللعبة بنجاح');
    }


    // 🔹 Make game item inactive
public function gameItemInactive($id)
{
    $gameItem = GameItem::findOrFail($id);
    $gameItem->status = 'inactive';
    $gameItem->save();

    return redirect()->back()->with('success', '👁️ تم إخفاء عنصر اللعبة');
}

// 🔹 Make game item active
public function gameItemActive($id)
{
    $gameItem = GameItem::findOrFail($id);
    $gameItem->status = 'active';
    $gameItem->save();

    return redirect()->back()->with('success', '✅ تم إظهار عنصر اللعبة');
}
}
