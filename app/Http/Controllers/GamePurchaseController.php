<?php

namespace App\Http\Controllers;

use App\Models\GamePurchase;
use Illuminate\Http\Request;

class GamePurchaseController extends Controller
{
    public function allGamePurchase()
    {
        $purchases = GamePurchase::latest()->get();
        return view('admin.game_purchase.all_game_purchase', compact('purchases'));
    }

    public function addGamePurchase()
    {
        return view('admin.game_purchase.add_game_purchase');
    }

    public function storeGamePurchase(Request $request)
    {
        $request->validate([
            'games_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ], [
            'games_count.required' => '⚠️ الرجاء إدخال عدد الألعاب',
            'games_count.integer' => '⚠️ عدد الألعاب يجب أن يكون رقمًا صحيحًا',
            'games_count.min' => '⚠️ عدد الألعاب يجب أن يكون لعبة واحدة على الأقل',
            'price.required' => '⚠️ الرجاء إدخال السعر النقدى',
            'price.numeric' => '⚠️ السعر يجب أن يكون قيمة رقمية',
            'price.min' => '⚠️ السعر لا يمكن أن يكون أقل من 0',
        ]);

        GamePurchase::create([
            'games_count' => $request->games_count,
            'price' => $request->price,
        ]);

        $notification = array(
            'message' => 'تم إضافة القيمة النقدية للألعاب بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.purchase')->with($notification);
    }

    public function editGamePurchase($id)
    {
        $purchase = GamePurchase::findOrFail($id);
        return view('admin.game_purchase.edit_game_purchase', compact('purchase'));
    }

    public function updateGamePurchase(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'games_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ], [
            'games_count.required' => '⚠️ الرجاء إدخال عدد الألعاب',
            'games_count.integer' => '⚠️ عدد الألعاب يجب أن يكون رقمًا صحيحًا',
            'games_count.min' => '⚠️ عدد الألعاب يجب أن يكون لعبة واحدة على الأقل',
            'price.required' => '⚠️ الرجاء إدخال السعر النقدى',
            'price.numeric' => '⚠️ السعر يجب أن يكون قيمة رقمية',
            'price.min' => '⚠️ السعر لا يمكن أن يكون أقل من 0',
        ]);

        GamePurchase::findOrFail($id)->update([
            'games_count' => $request->games_count,
            'price' => $request->price,
        ]);

        $notification = array(
            'message' => 'تم تحديث القيمة النقدية للألعاب بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.purchase')->with($notification);
    }

    public function deleteGamePurchase($id)
    {
        GamePurchase::findOrFail($id)->delete();

        $notification = array(
            'message' => 'تم حذف القيمة النقدية للألعاب بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.purchase')->with($notification);
    }

    public function getGamePurchasesApi()
    {
        $purchases = GamePurchase::where('status', 'active')->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Game purchases retrieved successfully',
            'data' => $purchases
        ], 200);
    }

    public function gamePurchaseInactive($id)
    {
        GamePurchase::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => 'تم إخفاء الباقة بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function gamePurchaseActive($id)
    {
        GamePurchase::findOrFail($id)->update(['status' => 'active']);
        $notification = array(
            'message' => 'تم إظهار الباقة بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
