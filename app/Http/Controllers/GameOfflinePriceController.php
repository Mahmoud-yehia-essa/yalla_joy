<?php

namespace App\Http\Controllers;

use App\Models\GameCoin;
use Illuminate\Http\Request;
use App\Models\GameOfflinePrice;
use Illuminate\Support\Carbon;


class GameOfflinePriceController extends Controller
{

//     public function getPriceAttribute($value)
// {
//     return rtrim(rtrim($value, '0'), '.');
// }

       public function allGameOfflinePrice()
    {
        // $price = price::latest()->get();

$price = GameOfflinePrice::latest('id')->get();



        return view('admin.game_offline_prices.all_game_offline_prices',compact('price'));
    }

          public function addGameOfflinePrice()
    {

                $gameCoins = GameCoin::all();

        return view('admin.game_offline_prices.add_game_offline_prices',compact('gameCoins'));
    }



           public function addGameOfflinePriceStore(Request $request)
    {


          $request->validate([
            'title' => 'required',
            // 'price' => 'required|numeric',
            //             'points_number_offline' => 'required|numeric',
            // 'points_number_online' => 'required|numeric',
            'coins_number' => 'required|numeric',

            'games_number' => 'required|numeric',
            'color1' => 'required',
            'color2' => 'required',
            'game_coin_id'  => 'required|exists:game_coins,id',


        ], [
            'title.required' => 'الرجاء اضافة الوصف',

            // 'price.required' => 'الرجاء اضافة السعر',
            //             'price.numeric' => 'الرجاء اضافة السعر رقما',

                          'coins_number.required' => 'الرجاء اضافة عدد العملات',
                        'coins_number.numeric' => 'الرجاء اضافة عدد العملات رقما',


                                'games_number.required' => 'الرجاء اضافة عدد الألعاب',
                        'games_number.numeric' => 'الرجاء اضافة عدد الألعاب رقما',


                        //                         'points_number_offline.numeric' => 'الرجاء اضافة عدد النقاط رقما',
                        // 'points_number_online.numeric' => 'الرجاء اضافة عدد النقاط رقما',


            'color1.required' => 'الرجاء اضافة اللون الأول',
            'color2.required' => 'الرجاء اضافة اللون الثاني',
                        'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',

        ]);

            // Convert colors
    $color1 = $this->convertColorToFlutter($request->color1);
    $color2 = $this->convertColorToFlutter($request->color2);


        // Example return (just for checking)
    GameOfflinePrice::insert([

            'title' => $request->title,
            // 'price' => $request->price,

            'color1' => $color1,
            'color2' => $color2,
            'coins_number' => $request->coins_number,
            'games_number' => $request->games_number,


            'game_coin_id' => $request->game_coin_id,




            'created_at' =>Carbon::now()


        ]);


        $notification = array(
            'message' => 'تم اضافة قيمة جديد',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.offline.price')->with($notification);



    }


   public function editGameOfflinePrice($id)
{
    $price = GameOfflinePrice::findOrFail($id);

    // Convert Flutter color format to standard hex
    $price->color1 = $this->convertFlutterToHex($price->color1);
    $price->color2 = $this->convertFlutterToHex($price->color2);

    //  $price->price = getPriceAttribute($price->price);

                    $gameCoins = GameCoin::all();


    return view('admin.game_offline_prices.edit_game_offline_prices', compact('price','gameCoins'));
}

 public function editGameOfflinePriceStore(Request $request){


          $request->validate([
            'title' => 'required',
            // 'price' => 'required|numeric',
            //             'points_number_offline' => 'required|numeric',
            // 'points_number_online' => 'required|numeric',
            'coins_number' => 'required|numeric',

            'games_number' => 'required|numeric',
            'color1' => 'required',
            'color2' => 'required',
            'game_coin_id'  => 'required|exists:game_coins,id',


        ], [
          'title.required' => 'الرجاء اضافة الوصف',

            // 'price.required' => 'الرجاء اضافة السعر',
            //             'price.numeric' => 'الرجاء اضافة السعر رقما',

                          'coins_number.required' => 'الرجاء اضافة عدد العملات',
                        'coins_number.numeric' => 'الرجاء اضافة عدد العملات رقما',


                                'games_number.required' => 'الرجاء اضافة عدد الألعاب',
                        'games_number.numeric' => 'الرجاء اضافة عدد الألعاب رقما',


                        //                         'points_number_offline.numeric' => 'الرجاء اضافة عدد النقاط رقما',
                        // 'points_number_online.numeric' => 'الرجاء اضافة عدد النقاط رقما',


            'color1.required' => 'الرجاء اضافة اللون الأول',
            'color2.required' => 'الرجاء اضافة اللون الثاني',
                        'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',

        ]);

            // Convert colors
    $color1 = $this->convertColorToFlutter($request->color1);
    $color2 = $this->convertColorToFlutter($request->color2);

        $id = $request->id;

         GameOfflinePrice::findOrFail($id)->update([
             'title' => $request->title,
            // 'price' => $request->price,

            'color1' => $color1,
            'color2' => $color2,
            'coins_number' => $request->coins_number,
            'games_number' => $request->games_number,


            'game_coin_id' => $request->game_coin_id,




            'created_at' =>Carbon::now()



        ]);

       $notification = array(
            'message' => 'تم التعديل ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.offline.price')->with($notification);


    }// E

       public function deleteGameOfflinePrice($id){

        GameOfflinePrice::findOrFail($id)->delete();

         $notification = array(
            'message' => 'تم الحذف ',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


    }// End Method


    private function convertFlutterToHex($flutterColor)
{
    // Remove '0x' or '0X' prefix if present
    $hex = strtoupper(ltrim($flutterColor, '0x'));

    // Remove alpha (first 2 characters: 'FF' for full opacity)
    $rgb = substr($hex, 2);

    // Return with '#' and lowercase format
    return '#' . strtolower($rgb);
}
    private function convertColorToFlutter($hexColor)
{
    // Remove '#' if present
    $hex = ltrim($hexColor, '#');

    // Prepend 'FF' for full opacity alpha
    return '0xFF' . strtoupper($hex);
}



// api




//   public function getAllPrice(Request $request) {




//     $price = Price::latest()->get()->map(function ($item) {
//     $item->price_after_coupon = "0";
//     return $item;
// });

// return response()->json($price);





//     }



// public function getAllPrice(Request $request)
// {
//     $price = Price::with('gameCoin')->latest()->get()->map(function ($item) {

//         $item->price_after_coupon = "0";

//         // إضافة اسم العملة (مثلاً)
//         $item->game_coin_name = $item->gameCoin?->name;

//         $item->game_coin_photo = $item->gameCoin?->photo;


//         // أو أي حقل تاني

//         return $item;
//     });

//     return response()->json($price);
// }





public function getAllGameOfflinePrice(Request $request)
{
    $price = GameOfflinePrice::with('gameCoin')->latest('id')->get()->map(function ($item) {

        $item->game_coin_name = $item->gameCoin?->name;
        $item->game_coin_photo = $item->gameCoin?->photo;

        // حذف العلاقة من الـ JSON
        unset($item->gameCoin);
        unset($item->game_coin); // للـ safety

        return $item;
    });

    return response()->json($price);
}


}
