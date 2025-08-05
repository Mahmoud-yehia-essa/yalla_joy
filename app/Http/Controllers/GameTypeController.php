<?php

namespace App\Http\Controllers;

use App\Models\GameType;
use Illuminate\Http\Request;

use Intervention\Image\Format;

use Intervention\Image\ImageManager;

use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver; // Use GD driver (or use Intervention\Image\Drivers\Imagick\Driver for Imagick)


class GameTypeController extends Controller
{
    //
     //
    public function gameType()
    {
        $gameType = GameType::latest()->get();




        return view('admin.game_type.all_game_type',compact('gameType'));
    }

    public function addGameType()
    {

        // return view('admin.category.add_category');
                return view('admin.game_type.add_game_type');

    }

    public function storeGameType(Request $request)
    {



        $request->validate([
            'game_type_name' => 'required|string|max:255',
            'game_type_description' => 'nullable|string',
            'game_type_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'game_type_name.required' => '⚠️ الرجاء اضافة نوع اللعبة',
            'game_type_name.string' => '⚠️ الرجاء التأكد من كتابة نوع اللعبة بشكل صحيح',
            'game_type_name.max' => '⚠️ الرجاء التأكد من عدد احرف نوع اللعبة لا يتجاوز 255 حرف',

            'game_type_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',

            'game_type_photo.required' => '⚠️ الرجاء اضافة صورة نوع للعبة',
            'game_type_photo.image' => '⚠️ تأكد من اضافة صورة',
            'game_type_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'game_type_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
        ]);



        if ($request->hasFile('game_type_photo')) {
            $image = $request->file('game_type_photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            $path = public_path('upload/game_type/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
            // Process and save image
            $imageResized = $imageManager->read($image)->resize(364, 176);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/game_type/' . $name_gen;
        }

        // Insert game type

        GameType::create([
            'type_name' => $request->game_type_name,
            'type_description' => $request->game_type_description,
            'type_photo' => $save_url ?? null,
            // 'special' => $request->special,

        ]);


        $notification = array(
            'message' => 'تم اضافة نوع اللعبة ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.game.type')->with($notification);
    }


    public function editGameType($id){
        $gameType = GameType::findOrFail($id);
        // return view('admin.category.edit_category',compact('category'));
                return view('admin.game_type.edit_game_type',compact('gameType'));

    }// End Method





    public function editGameTypeStore(Request $request){




        $request->validate([
            'game_type_name' => 'required|string|max:255',
            'game_type_description' => 'nullable|string',
            'game_type_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'game_type_name.required' => '⚠️ الرجاء اضافة نوع الفئة',
            'game_type_name.string' => '⚠️ الرجاء التأكد من كتابة نوع الفئة بشكل صحيح',
            'game_type_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',

            'game_type_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',

            'game_type_photo.required' => '⚠️ الرجاء اضافة صورة لنوع الفئة',
            'game_type_photo.image' => '⚠️ تأكد من اضافة صورة',
            'game_type_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'game_type_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
        ]);


        $cate_id = $request->id;
        $old_img = $request->old_image;
        if ($request->file('game_type_photo')) {
        $image = $request->file('game_type_photo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();



        $path = public_path('upload/game_type/');

        $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
        // Process and save image
        $imageResized = $imageManager->read($image)->resize(364, 176);
        $imageResized->save($path . $name_gen);

        $save_url = 'upload/game_type/' . $name_gen;


        if (file_exists($old_img)) {
           unlink($old_img);
        }
        GameType::findOrFail($cate_id)->update([
            'type_name' => $request->game_type_name,
            'type_description' => $request->game_type_description,
            'type_photo' => $save_url ,
            // 'special'  => $request->special,

        ]);
       $notification = array(
            'message' => 'تم تعديل نوع اللعبة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.game.type')->with($notification);
        } else {
            GameType::findOrFail($cate_id)->update([
                'type_name' => $request->game_type_name,
                'type_description' => $request->game_type_description,
                // 'special'  => $request->special,


        ]);
       $notification = array(
            'message' => 'تم تعديل نوع اللعبة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.game.type')->with($notification);
        } // end else
    }// End Method




    public function deleteGameType($id){
        $gameType = GameType::findOrFail($id);
        $img = $gameType->type_photo;

        // unlink($img );

        if ($gameType->type_photo && file_exists(public_path($gameType->type_photo))) {
            unlink(public_path($gameType->type_photo));
        }
        GameType::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف نوع اللعبة',
            'alert-type' => 'success'
        );
       // return redirect()->route('all.category')->with($notification);

                return redirect()->route('all.game.type')->with($notification);

        // return redirect()->back()->with($notification);
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

    public function getCategoryApi(Request $request) {
        // $category = Category::latest()->get()->map(function ($item) {
        //     $item->category_selected = false;
        //     return $item;
        // });


        $category = Category::where('status', 'active')->latest()->get()->map(function ($item) {
            $item->category_selected = false;
            return $item;
        });



        if ($category->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Category retrieval successful',
                'categories' => $category,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid get categories'
        ], 401);
    }

}
