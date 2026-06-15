<?php

namespace App\Http\Controllers;

use App\Models\GameCoin;
use App\Models\CreateFreePlansTable;
use Illuminate\Http\Request;

class FreePlanController extends Controller
{
    public function allFreePlan()
    {
        $freePlans = CreateFreePlansTable::with('gameCoin')->latest()->get();
        return view('admin.free_plan.all_free_plan', compact('freePlans'));
    }

    public function addFreePlan()
    {
        $gameCoins = GameCoin::all();
        return view('admin.free_plan.add_free_plan', compact('gameCoins'));
    }

    public function storeFreePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم الخطة',
            'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
        ]);

        CreateFreePlansTable::create([
            'name' => $request->name,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
        ]);

        $notification = array(
            'message' => 'تم إضافة الخطة المجانية بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.free.plan')->with($notification);
    }

    public function editFreePlan($id)
    {
        $freePlan = CreateFreePlansTable::findOrFail($id);
        $gameCoins = GameCoin::all();
        return view('admin.free_plan.edit_free_plan', compact('freePlan', 'gameCoins'));
    }

    public function updateFreePlan(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required|string|max:255',
            'game_coin_id' => 'required|exists:game_coins,id',
            'coins_number' => 'required|integer|min:0',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم الخطة',
            'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
        ]);

        CreateFreePlansTable::findOrFail($id)->update([
            'name' => $request->name,
            'game_coin_id' => $request->game_coin_id,
            'coins_number' => $request->coins_number,
        ]);

        $notification = array(
            'message' => 'تم تحديث الخطة المجانية بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.free.plan')->with($notification);
    }

    public function deleteFreePlan($id)
    {
        CreateFreePlansTable::findOrFail($id)->delete();

        $notification = array(
            'message' => 'تم حذف الخطة المجانية بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.free.plan')->with($notification);
    }

    public function getFreePlansApi()
    {
        $freePlans = CreateFreePlansTable::with('gameCoin')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Free plans retrieved successfully',
            'data' => $freePlans
        ], 200);
    }
}
