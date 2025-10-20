<?php

namespace App\Http\Controllers;

use App\Models\GameType;
use App\Models\MainCategory;


use Illuminate\Http\Request;

use Intervention\Image\Format;

use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;

class MainCategoryController extends Controller
{
     public function mainCategory()
    {
        $mainCategory = MainCategory::latest()->get();



        return view('admin.main_category.all_main_category',compact('mainCategory'));


    }


      public function addMainCategory()
    {
        // return view('admin.category.add_category');

        $gameType = GameType::latest()->get();

        return view('admin.main_category.add_main_category',compact('gameType'));
    }

      public function storeMainCategory(Request $request)
    {

 $request->validate([

            'game_type_id' => 'required|not_in:non',

            'main_category_name' => 'required|string|max:255',
                        'main_category_name_en' => 'required|string|max:255',

            'main_category_description' => 'nullable|string',
                        'main_category_description_en' => 'nullable|string',

            'main_category_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'main_category_name.required' => '⚠️ الرجاء اضافة الفئة الرئيسية',
            'main_category_name_en.required' => '⚠️ الرجاء اضافة الفئة الرئيسية بالانجليزية',

            'main_category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة الرئيسية بشكل صحيح',
            'main_category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة الرئيسية لا يتجاوز 255 حرف',

            'main_category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'main_category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح',

            'main_category_photo.required' => '⚠️ الرجاء اضافة صورة الفئة الرئيسية',
            'main_category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'main_category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'main_category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',

                'game_type_id.required' => '⚠️ الرجاء اختيار نوع اللعبة.',
        'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',
        ]);





        /// New

        if ($request->hasFile('main_category_photo')) {
            $image = $request->file('main_category_photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            $path = public_path('upload/main_category/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
            // Process and save image
            // $imageResized = $imageManager->read($image)->resize(364, 176);

                        $imageResized = $imageManager->read($image);

            $imageResized->save($path . $name_gen);

            $save_url = 'upload/main_category/' . $name_gen;
        }

        // Insert game type

        MainCategory::create([

                        'game_type_id' => $request->game_type_id,

            'main_category_name' => $request->main_category_name,
                        'main_category_name_en' => $request->main_category_name_en,

            'main_category_description' => $request->main_category_description,
                        'main_category_description_en' => $request->main_category_description_en,

            'main_category_photo' => $save_url ?? null,

            'user_id' => Auth::user()->id,
            // 'special' => $request->special,

        ]);


        $notification = array(
            'message' => 'تم اضافة الفئة الرئيسية ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.main.category')->with($notification);
        ///


    }







    public function mainCategoryInactive($id){
        MainCategory::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => ' غير مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method
      public function mainCategoryActive($id){
        MainCategory::findOrFail($id)->update(['status' => 'active']);
        $notification = array(
            'message' => 'مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



     public function deleteMainCategory($id){
        $mainCategory = MainCategory::findOrFail($id);
        $img = $mainCategory->main_category_photo;

        // unlink($img );

        if ($mainCategory->main_category_photo && file_exists(public_path($mainCategory->main_category_photo))) {
            unlink(public_path($mainCategory->main_category_photo));
        }
        MainCategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف الفئة الرئيسية',
            'alert-type' => 'success'
        );
       // return redirect()->route('all.category')->with($notification);

        return redirect()->route('all.main.category')->with($notification);

        // return redirect()->back()->with($notification);
    }// End Method






    // editMainCategoryStore



     public function editMainCategory($id){
        $main_category = MainCategory::findOrFail($id);
        // return view('admin.category.edit_category',compact('category'));

                $gameType = GameType::latest()->get();

                return view('admin.main_category.edit_main_category',compact('main_category','gameType'));

    }// End Method

       public function editMainCategoryStore(Request $request){




    //    $request->validate([

    //         'game_type_id' => 'required|not_in:non',

    //         'main_category_name' => 'required|string|max:255',
    //                     'main_category_name_en' => 'required|string|max:255',

    //         'main_category_description' => 'nullable|string',
    //                     'main_category_description_en' => 'nullable|string',

    //         'main_category_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

    //     ], [
    //         'main_category_name.required' => '⚠️ الرجاء اضافة الفئة الرئيسية',
    //         'main_category_name_en.required' => '⚠️ الرجاء اضافة الفئة الرئيسية بالانجليزية',

    //         'main_category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة الرئيسية بشكل صحيح',
    //         'main_category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة الرئيسية لا يتجاوز 255 حرف',

    //         'main_category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
    //         'main_category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح',

    //         'main_category_photo.required' => '⚠️ الرجاء اضافة صورة الفئة الرئيسية',
    //         'main_category_photo.image' => '⚠️ تأكد من اضافة صورة',
    //         'main_category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
    //         'main_category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',

    //             'game_type_id.required' => '⚠️ الرجاء اختيار نوع اللعبة.',
    //     'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',
    //     ]);



        $request->validate([

                        'game_type_id' => 'required|not_in:non',

            'main_category_name' => 'required|string|max:255',
                        'main_category_name_en' => 'required|string|max:255',

            'main_category_description' => 'nullable|string',
                        'main_category_description_en' => 'nullable|string',

            'main_category_photo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'main_category_name.required' => '⚠️ الرجاء اضافة الفئة',
                        'main_category_name_en.required' => '⚠️ الرجاء اضافة الفئة بالانجليزية',

            'main_category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'main_category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',

            'main_category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'main_category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح',

            'main_category_photo.required' => '⚠️ الرجاء اضافة صورة الفئة',
            'main_category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'main_category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'main_category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
                    'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',

        ]);

        $main_category_id = $request->id;
        $old_img = $request->old_image;
        if ($request->file('main_category_photo')) {
        $image = $request->file('main_category_photo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();



        $path = public_path('upload/main_category/');

        $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
        // Process and save image
        // $imageResized = $imageManager->read($image)->resize(364, 176);
                $imageResized = $imageManager->read($image);

        $imageResized->save($path . $name_gen);

        $save_url = 'upload/main_category/' . $name_gen;


        if (file_exists($old_img)) {
           unlink($old_img);
        }
        MainCategory::findOrFail($main_category_id)->update([

                                    'game_type_id' => $request->game_type_id,

            'main_category_name' => $request->main_category_name,
            'main_category_name_en' => $request->main_category_name_en,

            'main_category_description' => $request->main_category_description,
            'main_category_description_en' => $request->main_category_description_en,

            'main_category_photo' => $save_url ,
            // 'special'  => $request->special,

        ]);
       $notification = array(
            'message' => 'تم تعديل الفئة الرئيسية',
            'alert-type' => 'success'
        );
        return redirect()->route('all.main.category')->with($notification);
        } else {
            MainCategory::findOrFail($main_category_id)->update([
                                    'game_type_id' => $request->game_type_id,
                  'main_category_name' => $request->main_category_name,
            'main_category_name_en' => $request->main_category_name_en,

            'main_category_description' => $request->main_category_description,
            'main_category_description_en' => $request->main_category_description_en,

                // 'special'  => $request->special,


        ]);
       $notification = array(
            'message' => 'تم تعديل الفئة الرئيسية',
            'alert-type' => 'success'
        );
        return redirect()->route('all.main.category')->with($notification);
        } // end else
    }// End Method





}
