<?php

namespace App\Http\Controllers;

use App\Models\GameType;
use App\Models\GameCoin;
use Illuminate\Http\Request;

use Intervention\Image\Format;

use Intervention\Image\ImageManager;

use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver; // Use GD driver (or use Intervention\Image\Drivers\Imagick\Driver for Imagick)
use Illuminate\Support\Facades\Auth;


class GameTypeController extends Controller
{

    public function gameType()
    {
        $gameType = GameType::with([
            'gameCoin',
            'offlineGameCoin',
            'onlineSearchGameCoin',
            'onlineCreateGameCoin',
            'onlineChallengeGameCoin',
            'topScorersGameCoin',
            'mainCategories'
        ])->latest()->get();

        return view('admin.game_type.all_game_type', compact('gameType'));
    }

    public function addGameType()
    {
        $gameCoins = GameCoin::all();
        return view('admin.game_type.add_game_type', compact('gameCoins'));
    }

    public function storeGameType(Request $request)
    {
        $request->validate([
            'game_type_name' => 'required|string|max:255',
            'game_type_name_en' => 'required|string|max:255',
            'game_type_description' => 'nullable|string',
            'game_type_description_en' => 'nullable|string',
            'game_type_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'game_coin_id' => 'nullable|exists:game_coins,id',
            'coins_number' => 'nullable|numeric|min:0',
            'offline_game_coin_id' => 'nullable|exists:game_coins,id',
            'offline_coins_number' => 'nullable|numeric|min:0',
            'online_search_game_coin_id' => 'nullable|exists:game_coins,id',
            'online_search_coins_number' => 'nullable|numeric|min:0',
            'online_create_game_coin_id' => 'nullable|exists:game_coins,id',
            'online_create_coins_number' => 'nullable|numeric|min:0',
            'online_challenge_game_coin_id' => 'nullable|exists:game_coins,id',
            'online_challenge_coins_number' => 'nullable|numeric|min:0',
            'top_scorers_game_coin_id' => 'nullable|exists:game_coins,id',
            'top_scorers_coins_number' => 'nullable|numeric|min:0',
        ], [
            'game_type_name.required' => '⚠️ الرجاء اضافة نوع اللعبة',
            'game_type_name_en.required' => '⚠️ الرجاء اضافة نوع اللعبة بالانجليزية',
            'game_type_name.string' => '⚠️ الرجاء التأكد من كتابة نوع اللعبة بشكل صحيح',
            'game_type_name.max' => '⚠️ الرجاء التأكد من عدد احرف نوع اللعبة لا يتجاوز 255 حرف',
            'game_type_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'game_type_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح',
            'game_type_photo.required' => '⚠️ الرجاء اضافة صورة نوع للعبة',
            'game_type_photo.image' => '⚠️ تأكد من اضافة صورة',
            'game_type_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'game_type_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
            'coins_number.numeric' => '⚠️ عدد العملات يجب أن يكون رقماً',
            'coins_number.min' => '⚠️ عدد العملات لا يمكن أن يكون سالباً',
            'offline_coins_number.numeric' => '⚠️ عدد عملات لعبة الجلسة يجب أن يكون رقماً',
            'offline_coins_number.min' => '⚠️ عدد عملات لعبة الجلسة لا يمكن أن يكون سالباً',
            'online_search_coins_number.numeric' => '⚠️ عدد عملات البحث عن لاعبين يجب أن يكون رقماً',
            'online_search_coins_number.min' => '⚠️ عدد عملات البحث عن لاعبين لا يمكن أن يكون سالباً',
            'online_create_coins_number.numeric' => '⚠️ عدد عملات إنشاء لعبة كاملة يجب أن يكون رقماً',
            'online_create_coins_number.min' => '⚠️ عدد عملات إنشاء لعبة كاملة لا يمكن أن يكون سالباً',
            'online_challenge_coins_number.numeric' => '⚠️ عدد عملات تحدي صديق يجب أن يكون رقماً',
            'online_challenge_coins_number.min' => '⚠️ عدد عملات تحدي صديق لا يمكن أن يكون سالباً',
            'top_scorers_coins_number.numeric' => '⚠️ عدد عملات التحديات وأعلى الدرجات يجب أن يكون رقماً',
            'top_scorers_coins_number.min' => '⚠️ عدد عملات التحديات وأعلى الدرجات لا يمكن أن يكون سالباً',
        ]);

        if ($request->hasFile('game_type_photo')) {
            $image = $request->file('game_type_photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            $path = public_path('upload/game_type/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/game_type/' . $name_gen;
        }

        // Insert game type
        GameType::create([
            'type_name' => $request->game_type_name,
            'type_name_en' => $request->game_type_name_en,
            'type_description' => $request->game_type_description,
            'type_description_en' => $request->game_type_description_en,
            'type_photo' => $save_url ?? null,
            'user_id' => Auth::user()->id,
            'is_kids' => $request->is_kids ?? 0,
            'is_term' => $request->is_term ?? 0,
            'game_coin_id' => $request->game_coin_id ?: null,
            'coins_number' => $request->coins_number ?? 0,
            'offline_game_coin_id' => $request->offline_game_coin_id ?: null,
            'offline_coins_number' => $request->offline_coins_number ?? 0,
            'online_search_game_coin_id' => $request->online_search_game_coin_id ?: null,
            'online_search_coins_number' => $request->online_search_coins_number ?? 0,
            'online_create_game_coin_id' => $request->online_create_game_coin_id ?: null,
            'online_create_coins_number' => $request->online_create_coins_number ?? 0,
            'online_challenge_game_coin_id' => $request->online_challenge_game_coin_id ?: null,
            'online_challenge_coins_number' => $request->online_challenge_coins_number ?? 0,
            'top_scorers_game_coin_id' => $request->top_scorers_game_coin_id ?: null,
            'top_scorers_coins_number' => $request->top_scorers_coins_number ?? 0,
        ]);

        $notification = array(
            'message' => 'تم اضافة نوع اللعبة بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.type')->with($notification);
    }

    public function editGameType($id){
        $gameType = GameType::findOrFail($id);
        $gameCoins = GameCoin::all();
        return view('admin.game_type.edit_game_type', compact('gameType', 'gameCoins'));
    }// End Method

    public function editGameTypeStore(Request $request){
        $request->validate([
            'game_type_name' => 'required|string|max:255',
            'game_type_name_en' => 'required|string|max:255',
            'game_type_description' => 'nullable|string',
            'game_type_description_en' => 'nullable|string',
            'game_type_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'game_coin_id' => 'nullable|exists:game_coins,id',
            'coins_number' => 'nullable|numeric|min:0',
            'offline_game_coin_id' => 'nullable|exists:game_coins,id',
            'offline_coins_number' => 'nullable|numeric|min:0',
            'online_search_game_coin_id' => 'nullable|exists:game_coins,id',
            'online_search_coins_number' => 'nullable|numeric|min:0',
            'online_create_game_coin_id' => 'nullable|exists:game_coins,id',
            'online_create_coins_number' => 'nullable|numeric|min:0',
            'online_challenge_game_coin_id' => 'nullable|exists:game_coins,id',
            'online_challenge_coins_number' => 'nullable|numeric|min:0',
            'top_scorers_game_coin_id' => 'nullable|exists:game_coins,id',
            'top_scorers_coins_number' => 'nullable|numeric|min:0',
        ], [
            'game_type_name.required' => '⚠️ الرجاء اضافة نوع الفئة',
            'game_type_name_en.required' => '⚠️ الرجاء اضافة نوع الفئة بالانجليزية',
            'game_type_name.string' => '⚠️ الرجاء التأكد من كتابة نوع الفئة بشكل صحيح',
            'game_type_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',
            'game_type_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'game_type_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح',
            'game_type_photo.image' => '⚠️ تأكد من اضافة صورة',
            'game_type_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'game_type_photo.max' => '⚠️ 2MB حجم الصورة يجب الا يتعدى',
            'coins_number.numeric' => '⚠️ عدد العملات يجب أن يكون رقماً',
            'coins_number.min' => '⚠️ عدد العملات لا يمكن أن يكون سالباً',
            'offline_coins_number.numeric' => '⚠️ عدد عملات لعبة الجلسة يجب أن يكون رقماً',
            'offline_coins_number.min' => '⚠️ عدد عملات لعبة الجلسة لا يمكن أن يكون سالباً',
            'online_search_coins_number.numeric' => '⚠️ عدد عملات البحث عن لاعبين يجب أن يكون رقماً',
            'online_search_coins_number.min' => '⚠️ عدد عملات البحث عن لاعبين لا يمكن أن يكون سالباً',
            'online_create_coins_number.numeric' => '⚠️ عدد عملات إنشاء لعبة كاملة يجب أن يكون رقماً',
            'online_create_coins_number.min' => '⚠️ عدد عملات إنشاء لعبة كاملة لا يمكن أن يكون سالباً',
            'online_challenge_coins_number.numeric' => '⚠️ عدد عملات تحدي صديق يجب أن يكون رقماً',
            'online_challenge_coins_number.min' => '⚠️ عدد عملات تحدي صديق لا يمكن أن يكون سالباً',
            'top_scorers_coins_number.numeric' => '⚠️ عدد عملات التحديات وأعلى الدرجات يجب أن يكون رقماً',
            'top_scorers_coins_number.min' => '⚠️ عدد عملات التحديات وأعلى الدرجات لا يمكن أن يكون سالباً',
        ]);

        $cate_id = $request->id;
        $old_img = $request->old_image;

        $updateData = [
            'type_name' => $request->game_type_name,
            'type_name_en' => $request->game_type_name_en,
            'type_description' => $request->game_type_description,
            'type_description_en' => $request->game_type_description_en,
            'is_kids' => $request->is_kids ?? 0,
            'is_term' => $request->is_term ?? 0,
            'game_coin_id' => $request->game_coin_id ?: null,
            'coins_number' => $request->coins_number ?? 0,
            'offline_game_coin_id' => $request->offline_game_coin_id ?: null,
            'offline_coins_number' => $request->offline_coins_number ?? 0,
            'online_search_game_coin_id' => $request->online_search_game_coin_id ?: null,
            'online_search_coins_number' => $request->online_search_coins_number ?? 0,
            'online_create_game_coin_id' => $request->online_create_game_coin_id ?: null,
            'online_create_coins_number' => $request->online_create_coins_number ?? 0,
            'online_challenge_game_coin_id' => $request->online_challenge_game_coin_id ?: null,
            'online_challenge_coins_number' => $request->online_challenge_coins_number ?? 0,
            'top_scorers_game_coin_id' => $request->top_scorers_game_coin_id ?: null,
            'top_scorers_coins_number' => $request->top_scorers_coins_number ?? 0,
        ];

        if ($request->file('game_type_photo')) {
            $image = $request->file('game_type_photo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            $path = public_path('upload/game_type/');
            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);
            $save_url = 'upload/game_type/' . $name_gen;

            if ($old_img && file_exists(public_path($old_img))) {
                unlink(public_path($old_img));
            }

            $updateData['type_photo'] = $save_url;
        }

        GameType::findOrFail($cate_id)->update($updateData);

        $notification = array(
            'message' => 'تم تعديل نوع اللعبة بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->route('all.game.type')->with($notification);
    }// End Method

    public function deleteGameType($id){
        $gameType = GameType::findOrFail($id);

        if ($gameType->type_photo && file_exists(public_path($gameType->type_photo))) {
            unlink(public_path($gameType->type_photo));
        }
        GameType::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف نوع اللعبة',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.type')->with($notification);
    }// End Method

    public function gameTypeInactive($id){
        GameType::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => ' غير مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method

    public function gameTypeActive($id){
        GameType::findOrFail($id)->update(['status' => 'active']);
        $notification = array(
            'message' => 'مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    // API
    public function getGameTypeApi(Request $request) {
        $is_kids = $request->is_kids ?? 0;

        $gameType = GameType::with([
            'gameCoin',
            'offlineGameCoin',
            'onlineSearchGameCoin',
            'onlineCreateGameCoin',
            'onlineChallengeGameCoin',
            'topScorersGameCoin'
        ])
            ->where('status', 'active')
            ->where('is_kids', $is_kids)
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->game_type_selected = false;
                return $item;
            });

        if ($gameType->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'game type retrieval successful',
                'gameType' => $gameType,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid get game type'
        ], 401);
    }

}
