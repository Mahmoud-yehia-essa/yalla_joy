<?php

namespace App\Http\Controllers;

use App\Models\price;
use App\Models\GameCoin;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PriceController extends Controller
{


//     public function getPriceAttribute($value)
// {
//     return rtrim(rtrim($value, '0'), '.');
// }

       public function allPrice()
    {
        // $price = price::latest()->get();

$price = Price::latest('id')->get();



        return view('admin.price.all_price',compact('price'));
    }

          public function addPrice()
    {

                $gameCoins = GameCoin::all();

        return view('admin.price.add_price',compact('gameCoins'));
    }





           public function addPriceStore(Request $request)
    {


          $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            //             'points_number_offline' => 'required|numeric',
            // 'points_number_online' => 'required|numeric',

            'coins_number' => 'required|numeric',
            'color1' => 'required',
            'color2' => 'required',
            'game_coin_id'  => 'required|exists:game_coins,id',


        ], [
            'title.required' => 'الرجاء اضافة الوصف',
            'price.required' => 'الرجاء اضافة السعر',
                        'price.numeric' => 'الرجاء اضافة السعر رقما',

                          'coins_number.required' => 'الرجاء اضافة عدد الألعاب',
                        'coins_number.numeric' => 'الرجاء اضافة عدد الألعاب رقما',



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
    Price::insert([

            'title' => $request->title,
            'price' => $request->price,

            'color1' => $color1,
            'color2' => $color2,
            'coins_number' => $request->coins_number,
            'game_coin_id' => $request->game_coin_id,

            'points_number_online' => $request->points_number_online,
            'points_number_offline' => $request->points_number_offline,


            'created_at' =>Carbon::now()


        ]);


        $notification = array(
            'message' => 'تم اضافة سعر جديد',
            'alert-type' => 'success'
        );

        return redirect()->route('all.price')->with($notification);



    }


   public function editPrice($id)
{
    $price = Price::findOrFail($id);

    // Convert Flutter color format to standard hex
    $price->color1 = $this->convertFlutterToHex($price->color1);
    $price->color2 = $this->convertFlutterToHex($price->color2);

    //  $price->price = getPriceAttribute($price->price);

                    $gameCoins = GameCoin::all();


    return view('admin.price.edit_price', compact('price','gameCoins'));
}

 public function editPriceStore(Request $request){


          $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'color1' => 'required',
            'color2' => 'required',
                        'coins_number' => 'required|numeric',

            'game_coin_id'  => 'required|exists:game_coins,id',


        ], [
            'title.required' => 'الرجاء اضافة الوصف',
            'price.required' => 'الرجاء اضافة السعر',
                        'price.numeric' => 'الرجاء اضافة السعر رقما',
                              'coins_number.required' => 'الرجاء اضافة عدد الألعاب',
                        'coins_number.numeric' => 'الرجاء اضافة عدد الألعاب رقما',

            'color1.required' => 'الرجاء اضافة اللون الأول',
            'color2.required' => 'الرجاء اضافة اللون الثاني',
                        'game_coin_id.required' => '⚠️ الرجاء اختيار عملة اللعبة',

        ]);

            // Convert colors
    $color1 = $this->convertColorToFlutter($request->color1);
    $color2 = $this->convertColorToFlutter($request->color2);

        $price_id = $request->id;

         Price::findOrFail($price_id)->update([
                  'title' => $request->title,
            'price' => $request->price,
            'color1' => $color1,
            'color2' => $color2,
                              'coins_number' => $request->coins_number,
            'game_coin_id' => $request->game_coin_id,


               'points_number_online' => $request->points_number_online,
            'points_number_offline' => $request->points_number_offline,


            'created_at' =>Carbon::now()



        ]);

       $notification = array(
            'message' => 'تم تعديل السعر',
            'alert-type' => 'success'
        );

        return redirect()->route('all.price')->with($notification);


    }// E

       public function deletePrice($id){

        Price::findOrFail($id)->delete();

         $notification = array(
            'message' => 'تم حذف السعر',
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



public function getAllPrice(Request $request)
{
    $price = Price::with('gameCoin')->latest('id')->get()->map(function ($item) {

        $item->price_after_coupon = "0";
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
