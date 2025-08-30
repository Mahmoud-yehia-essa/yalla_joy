<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameCoin;
use App\Models\Game;

use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
class GameCoinController extends Controller
{
    //


    public function AddGameCoin(){

        $gameCoin = GameCoin::findOrFail(1);

        return view('admin.game_coin.game_coin_add',compact('gameCoin'));

    }


public function StoreGameCoin(Request $request){



        $request->validate([


            'name' => 'required|string|max:255',
                        'name_en' => 'required|string|max:255',


            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => '⚠️ الرجاء اضافة العملة',
                        'name_en.required' => '⚠️ الرجاء اضافة العملة بالانجليزية',

            'photo.required' => '⚠️ الرجاء اضافة صورة الفئة',
            'photo.image' => '⚠️ تأكد من اضافة صورة',
            'photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',

        ]);

        $id = $request->id;
        $old_img = $request->old_image;
        if ($request->file('photo')) {
        $image = $request->file('photo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();



        $path = public_path('upload/setting/');

        $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
        // Process and save image
        $imageResized = $imageManager->read($image)->resize(364, 176);
        $imageResized->save($path . $name_gen);

        $save_url = 'upload/setting/' . $name_gen;


        if (file_exists($old_img)) {
           unlink($old_img);
        }
        GameCoin::findOrFail($id)->update([

                                    'name' => $request->name,

            'name' => $request->name_en,


            'photo' => $save_url ,
            // 'special'  => $request->special,

        ]);




            $notification = array(
        'message' => 'تم تحديث عملة اللعبة بنجاح',
        'alert-type' => 'success'
    );
        return redirect()->route('add.game.coin')->with($notification);



        } else {
            GameCoin::findOrFail($id)->update([
                                    'name' => $request->name,
                  'name_en' => $request->name_en,


                // 'special'  => $request->special,


        ]);
              $notification = array(
        'message' => 'تم تحديث عملة اللعبة بنجاح',
        'alert-type' => 'success'
    );


        return redirect()->route('add.game.coin')->with($notification);





        } // end else
    }// End Method







}
