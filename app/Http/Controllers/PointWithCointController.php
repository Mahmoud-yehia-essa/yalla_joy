<?php

namespace App\Http\Controllers;

use App\Models\GameCoin;
use Illuminate\Http\Request;
use App\Models\PointWithCoin;

class PointWithCointController extends Controller
{
     // ✅ جميع العناصر
    public function allPointCoin()
    {
        $pointCoin = PointWithCoin::with('gameCoin')->latest()->get();
        return view('admin.point_coin.all_point_coin', compact('pointCoin'));
    }

    // ✅ صفحة الإضافة
    public function addPointCoin()
    {
        // $itemTypes = ItemType::all();
        $gameCoins = GameCoin::all();
        return view('admin.point_coin.add_point_coin', compact('gameCoins'));
    }

    // ✅ حفظ عنصر جديد
    public function storePointCoin(Request $request)
    {
        $request->validate([



            'type' => 'required|not_in:non',

            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
            'points_number' => 'required|integer|min:0',
        ], [

                        'type.required' => '⚠️ الرجاء اختيار نوع اللعبة',

            'game_coin_id.required' => '⚠️ الرجاء اختيار العملة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
                        'points_number.required' => '⚠️ الرجاء إدخال عدد النقاط',


        ]);


        PointWithCoin::create([
            'type' => $request->type,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
            'points_number'         => $request->points_number,

        ]);

        return redirect()->route('all.point.coin')->with('success', '✅ تم إضافةالعملات مع نقاط اللعبة بنجاح');
    }

    // ✅ صفحة التعديل
    public function editPointCoin($id)
    {
        $pointWithCoin = PointWithCoin::findOrFail($id);
        $gameCoins = GameCoin::all();
        return view('admin.point_coin.edit_point_coin_new', compact('pointWithCoin', 'gameCoins'));
    }

    // ✅ تحديث عنصر اللعبة
    public function updatePointCoin(Request $request, $id)
    {
      $request->validate([



            'type' => 'required|not_in:non',

            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
            'points_number' => 'required|integer|min:0',
        ], [

                        'type.required' => '⚠️ الرجاء اختيار نوع اللعبة',

            'game_coin_id.required' => '⚠️ الرجاء اختيار العملة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
                        'points_number.required' => '⚠️ الرجاء إدخال عدد النقاط',


        ]);

        $pointWithCoin = PointWithCoin::findOrFail($id);


            $pointWithCoin->update([
                   'type' => $request->type,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
            'points_number'         => $request->points_number,
            ]);


        return redirect()->route('all.point.coin')->with('success', '✅ تم تعديل بنجاح');
    }

    // ✅ حذف عنصر
    public function deletePointCoin($id)
    {
        $gameItem = GameItem::findOrFail($id);

        if ($gameItem->photo && file_exists(public_path($gameItem->photo))) {
            unlink(public_path($gameItem->photo));
        }

        $gameItem->delete();

        return redirect()->route('all.game.item')->with('success', '🗑️ تم حذف عنصر اللعبة بنجاح');
    }


    // 🔹 Make game item inactive
public function pointCoinInactive($id)
{
    $gameItem = GameItem::findOrFail($id);
    $gameItem->status = 'inactive';
    $gameItem->save();

    return redirect()->back()->with('success', '👁️ تم إخفاء عنصر اللعبة');
}

// 🔹 Make game item active
public function pointCoinActive($id)
{
    $gameItem = GameItem::findOrFail($id);
    $gameItem->status = 'active';
    $gameItem->save();

    return redirect()->back()->with('success', '✅ تم إظهار عنصر اللعبة');
}
}
