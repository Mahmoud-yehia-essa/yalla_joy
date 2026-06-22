<?php

namespace App\Http\Controllers;

use App\Models\GameCoin;
use App\Models\OfflineGameCoins;
use Illuminate\Http\Request;

class OfflineGameCoinsController extends Controller
{
    public function allOfflineGameCoins()
    {
        $offlineCoins = OfflineGameCoins::with('gameCoin')->latest()->get();
        return view('admin.offline_game_coins.all', compact('offlineCoins'));
    }

    public function addOfflineGameCoins()
    {
        $gameCoins = GameCoin::all();
        return view('admin.offline_game_coins.add', compact('gameCoins'));
    }

    public function storeOfflineGameCoins(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم البند',
            'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
        ]);

        OfflineGameCoins::create([
            'name' => $request->name,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
        ]);

        $notification = array(
            'message' => 'تم إضافة عملات فائز في لعبة الجلسة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.offline.game.coins')->with($notification);
    }

    public function editOfflineGameCoins($id)
    {
        $offlineCoin = OfflineGameCoins::findOrFail($id);
        $gameCoins = GameCoin::all();
        return view('admin.offline_game_coins.edit', compact('offlineCoin', 'gameCoins'));
    }

    public function updateOfflineGameCoins(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|string|max:255',
            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم البند',
            'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
        ]);

        OfflineGameCoins::findOrFail($id)->update([
            'name' => $request->name,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
        ]);

        $notification = array(
            'message' => 'تم تحديث عملات فائز في لعبة الجلسة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.offline.game.coins')->with($notification);
    }

    public function deleteOfflineGameCoins($id)
    {
        OfflineGameCoins::findOrFail($id)->delete();

        $notification = array(
            'message' => 'تم حذف عملات فائز في لعبة الجلسة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.offline.game.coins')->with($notification);
    }

    public function getOfflineGameCoinsApi()
    {
        $offlineCoins = OfflineGameCoins::with('gameCoin')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Offline game coins retrieved successfully',
            'data' => $offlineCoins
        ], 200);
    }
}
