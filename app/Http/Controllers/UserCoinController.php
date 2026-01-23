<?php

namespace App\Http\Controllers;

use App\Models\UserCoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;


class UserCoinController extends Controller
{
    // Api






public function getUserCoinsSummary(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
    ]);

    $userId = $request->user_id;

    // جلب الملخص لكل عملة بدون تكرار
    $coins = UserCoin::where('user_id', $userId)
        ->select('game_coin_id', DB::raw('SUM(coins_number) as total_coins'))
        ->groupBy('game_coin_id')
        ->get()
        ->map(function ($item) {
            // جلب بيانات العملة مباشرة
            $coin = DB::table('game_coins')->where('id', $item->game_coin_id)->first();

            if ($coin) {
                return [
                    'game_coin_id' => $item->game_coin_id,
                    'total_coins' => $item->total_coins,
                    'name' => $coin->name,
                    'name_en' => $coin->name_en,
                    'photo' => $coin->photo,
                    'status' => $coin->status,
                    'created_at' => $coin->created_at,
                    'updated_at' => $coin->updated_at,
                ];
            }

            return [
                'game_coin_id' => $item->game_coin_id,
                'total_coins' => $item->total_coins,
            ];
        });

    return response()->json([
        'status' => true,
        'message' => 'User coins summary',
        'data' => $coins,
    ]);
}








// public function getUserCoinDetails(Request $request)
// {
//     $request->validate([
//         'user_id' => 'required|integer|exists:users,id',
//     ]);

//     $userId = $request->user_id;
//     $gameCoinId = $request->game_coin_id;

//     $details = UserCoin::where('user_id', $userId)

//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->map(function ($item) {
//             $coin = DB::table('game_coins')->where('id', $item->game_coin_id)->first();

//             if ($coin) {
//                 return [
//                     'id' => $item->id,
//                     'user_id' => $item->user_id,
//                     'game_coin_id' => $item->game_coin_id,
//                     'coins_number' => $item->coins_number,
//                     'name' => $coin->name,
//                     'name_en' => $coin->name_en,
//                     'photo' => $coin->photo,
//                     'status' => $coin->status,
//                     'created_at' => $item->created_at,
//                     'updated_at' => $item->updated_at,
//                 ];
//             }

//             return $item->toArray();
//         });

//     return response()->json([
//         'status' => true,
//         'message' => 'Coin details',
//         'data' => $details
//     ]);
// }




public function getUserCoinDetails(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
    ]);

    $userId = $request->user_id;

    $details = UserCoin::where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {

            $coin = DB::table('game_coins')->where('id', $item->game_coin_id)->first();

            // لضبط اللغة عربي
            Carbon::setLocale('ar');

            if ($coin) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'game_coin_id' => $item->game_coin_id,
                    'coins_number' => $item->coins_number,
                    'name' => $coin->name,
                    'name_en' => $coin->name_en,
                    'photo' => $coin->photo,
                    'status' => $coin->status,
                    'created_at' => $item->created_at,
                    'created_at_human' => Carbon::parse($item->created_at)->diffForHumans(),
                    'updated_at' => $item->updated_at,
                ];
            }

            $arr = $item->toArray();
            $arr['created_at_human'] = Carbon::parse($item->created_at)->diffForHumans();
            return $arr;
        });

    return response()->json([
        'status' => true,
        'message' => 'Coin details',
        'data' => $details
    ]);
}



//  public function updateCoinsNumbers(Request $request)
//     {
//                     $user_id = $request->user_id;

//                                         $game_coin_id = $request->game_coin_id;


//             $newCoinsNumber = $request->new_coins_number;

//                     $userCoin = UserCoin::where('user_id',$user_id)->where('game_coin_id',$game_coin_id);


//   $userCoin->coins_number = $newCoinsNumber;

//         // $user->address = $request->address;
//         $userCoin->save();





//        return response()->json([
//         'status' => true,
//         'message' => 'Coin details',
//         'data' => $userCoin
//     ]);



//     }






// public function updateCoinsNumbers(Request $request)
// {
//     $request->validate([
//         'user_id' => 'required|integer|exists:users,id',
//         'game_coin_id' => 'required|integer|exists:game_coins,id',
//         'new_coins_number' => 'required|integer',
//     ]);

//     $userId = $request->user_id;
//     $gameCoinId = $request->game_coin_id;
//     $newCoinsNumber = $request->new_coins_number;

//     // ✅ تسجيل عملية جديدة
//     UserCoin::create([
//         'user_id' => $userId,
//         'game_coin_id' => $gameCoinId,
//         'coins_number' => $newCoinsNumber,
//     ]);

//     // ✅ ملخص العملات بعد الإضافة
//     $coins = UserCoin::where('user_id', $userId)
//         ->select('game_coin_id', DB::raw('SUM(coins_number) as total_coins'))
//         ->groupBy('game_coin_id')
//         ->get()
//         ->map(function ($item) {

//             $coin = DB::table('game_coins')->where('id', $item->game_coin_id)->first();

//             if ($coin) {
//                 return [
//                     'game_coin_id' => $item->game_coin_id,
//                     'total_coins' => $item->total_coins,
//                     'name' => $coin->name,
//                     'name_en' => $coin->name_en,
//                     'photo' => $coin->photo,
//                     'status' => $coin->status,
//                     'created_at' => $coin->created_at,
//                     'updated_at' => $coin->updated_at,
//                 ];
//             }

//             return [
//                 'game_coin_id' => $item->game_coin_id,
//                 'total_coins' => $item->total_coins,
//             ];
//         });

//     return response()->json([
//         'status' => true,
//         'message' => 'Coins added successfully',
//         'data' => $coins,
//     ]);
// }


public function updateCoinsNumbers(Request $request)
{
    $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'game_coin_id' => 'required|integer|exists:game_coins,id',
        'new_coins_number' => 'required|integer|min:1',
        'type' => 'required|in:add,withdraw',
    ]);

    $userId = $request->user_id;
    $gameCoinId = $request->game_coin_id;
    $coinsNumber = $request->new_coins_number;
    $type = $request->type;

    // لو سحب نخزن رقم سالب
    if ($type === 'withdraw') {
        $coinsNumber = -abs($coinsNumber);
    }

    // ✅ تسجيل العملية
    UserCoin::create([
        'user_id' => $userId,
        'game_coin_id' => $gameCoinId,
        'coins_number' => $coinsNumber,
        'type' => $type, // add | withdraw
    ]);

    // ✅ ملخص العملات بعد العملية
    $coins = UserCoin::where('user_id', $userId)
        ->select('game_coin_id', DB::raw('SUM(coins_number) as total_coins'))
        ->groupBy('game_coin_id')
        ->get()
        ->map(function ($item) {

            $coin = DB::table('game_coins')->where('id', $item->game_coin_id)->first();

            if ($coin) {
                return [
                    'game_coin_id' => $item->game_coin_id,
                    // 'total_coins' => (int) $item->total_coins,
                                        'total_coins' =>  $item->total_coins,

                    'name' => $coin->name,
                    'name_en' => $coin->name_en,
                    'photo' => $coin->photo,
                    'status' => $coin->status,
                    'created_at' => $coin->created_at,
                    'updated_at' => $coin->updated_at,
                ];
            }

            return [
                'game_coin_id' => $item->game_coin_id,
                'total_coins' => (int) $item->total_coins,
            ];
        });

    return response()->json([
        'status' => true,
        'message' => 'Coin transaction saved successfully',
        'data' => $coins,
    ]);
}



}
