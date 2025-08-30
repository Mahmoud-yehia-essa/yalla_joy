<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GameType;
use App\Models\Question;
use App\Models\MainCategory;

use Illuminate\Http\Request;

use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver; // Use GD driver (or use Intervention\Image\Drivers\Imagick\Driver for Imagick)

class CategoryController extends Controller
{
    //
    public function category()
    {
        $category = Category::latest()->get();
        return view('admin.category.all_category',compact('category'));
    }

    public function addCategory()
    {
        $gameType = GameType::latest()->get();

        return view('admin.category.add_category',compact('gameType'));
    }

/// For json ajax getMainCategory
    public function getMainCategories($game_type_id)
{
    $mainCategories = \App\Models\MainCategory::where('game_type_id', $game_type_id)->get();

    return response()->json($mainCategories);
}

    public function getSubCategories($main_category_id)
{
    $subCategories = \App\Models\Category::where('main_category_id', $main_category_id)->get();

    return response()->json($subCategories);
}

    public function storeCategory(Request $request)
    {



        $request->validate([


                        'main_category_id' => 'required|not_in:non',
                        'game_type_id' => 'required|not_in:non',

            'category_name' => 'required|string|max:255',
                        'category_name_en' => 'required|string|max:255',

            'category_description' => 'nullable|string',
                        'category_description_en' => 'nullable|string',

            'category_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_name.required' => '⚠️ الرجاء اضافة اسم الفئة',
            'category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',


             'category_name_en.required' => '⚠️ الرجاء اضافة اسم الفئة بالانجليزية',
            'category_name_en.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name_en.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',


            'category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح ',


            'category_photo.required' => '⚠️ الرجاء اضافة صورة للفئة',
            'category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
                            'game_type_id.required' => '⚠️ الرجاء اختيار نوع اللعبة.',
        'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',


                             'main_category_id.required' => '⚠️ الرجاء اختيار الفئة الرئيسية.',
        'main_category_id.not_in' => '⚠️ الرجاء اختيار الفئة الرئيسية.',


        ]);



        if ($request->hasFile('category_photo')) {
            $image = $request->file('category_photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            $path = public_path('upload/category/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
            // Process and save image
            $imageResized = $imageManager->read($image);

                        // $imageResized = $imageManager->read($image)->resize(364, 176);

            $imageResized->save($path . $name_gen);

            $save_url = 'upload/category/' . $name_gen;
        }

        // Insert category
        Category::create([

             'game_type_id' => $request->game_type_id,
                          'main_category_id' => $request->main_category_id,


            'category_name' => $request->category_name,
                        'category_name_en' => $request->category_name_en,

            'category_description' => $request->category_description,
                        'category_description_en' => $request->category_description_en,

            'category_photo' => $save_url ?? null,
            'special' => $request->special,

        ]);


        $notification = array(
            'message' => 'تم اضافة الفئة ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }


    public function editCategort($id){


        $category = Category::findOrFail($id);
        $gameType = GameType::latest()->get();

            $mainCategories = MainCategory::latest()->get();

 $mainCategories = MainCategory::where('game_type_id', $category->game_type_id)
                                  ->latest()
                                  ->get();

        return view('admin.category.edit_category',compact('category','gameType','mainCategories'));
    }// End Method





    public function editCategortStore(Request $request){




        $request->validate([
                       'main_category_id' => 'required|not_in:non',
                        'game_type_id' => 'required|not_in:non',
            'category_name' => 'required|string|max:255',
                        'category_name_en' => 'required|string|max:255',

            'category_description' => 'nullable|string',
                        'category_description_en' => 'nullable|string',

            'category_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_name.required' => '⚠️ الرجاء اضافة اسم الفئة',
            'category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',

               'category_name_en.required' => '⚠️  الرجاء اضافة اسم الفئة بالانجليزية',
            'category_name_en.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name_en.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',

            'category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',


            'category_photo.required' => '⚠️ الرجاء اضافة صورة للفئة',
            'category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
                               'game_type_id.required' => '⚠️ الرجاء اختيار نوع اللعبة.',
        'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',


                             'main_category_id.required' => '⚠️ الرجاء اختيار الفئة الرئيسية.',
        'main_category_id.not_in' => '⚠️ الرجاء اختيار الفئة الرئيسية.',

        ]);


        $cate_id = $request->id;
        $old_img = $request->old_image;
        if ($request->file('category_photo')) {
        $image = $request->file('category_photo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();



        $path = public_path('upload/category/');

        $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
        // Process and save image
        // $imageResized = $imageManager->read($image)->resize(364, 176);
        $imageResized = $imageManager->read($image);


        $imageResized->save($path . $name_gen);

        $save_url = 'upload/category/' . $name_gen;


        if (file_exists($old_img)) {
           unlink($old_img);
        }
        Category::findOrFail($cate_id)->update([

            'game_type_id' => $request->game_type_id,
                          'main_category_id' => $request->main_category_id,
            'category_name' => $request->category_name,
                        'category_name_en' => $request->category_name_en,

            'category_description_en' => $request->category_description_en,

            'category_photo' => $save_url ,
            'special'  => $request->special,

        ]);
       $notification = array(
            'message' => 'تم تعديل الفئة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);
        } else {
            Category::findOrFail($cate_id)->update([

                'game_type_id' => $request->game_type_id,
                          'main_category_id' => $request->main_category_id,
                'category_name' => $request->category_name,
                 'category_name_en' => $request->category_name_en,

            'category_description_en' => $request->category_description_en,
                'category_description' => $request->category_description,
                'special'  => $request->special,


        ]);
       $notification = array(
            'message' => 'تم تعديل الفئة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);
        } // end else
    }// End Method




    public function deleteCategory($id){
        $category = Category::findOrFail($id);
        $img = $category->category_photo;

        // unlink($img );

        if ($category->category_photo && file_exists(public_path($category->category_photo))) {
            unlink(public_path($category->category_photo));
        }
        Category::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف الفئة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);

        // return redirect()->back()->with($notification);
    }// End Method




    public function categoryInactive($id){
        Category::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => ' غير مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }// End Method
      public function categoryActive($id){
        Category::findOrFail($id)->update(['status' => 'active']);
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
