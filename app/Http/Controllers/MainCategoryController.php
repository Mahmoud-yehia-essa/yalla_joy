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
        $mainCategory = MainCategory::orderByRaw('order_by IS NULL ASC')->orderBy('order_by', 'asc')->orderBy('id', 'desc')->get();

        return view('admin.main_category.all_main_category',compact('mainCategory'));
    }


      public function addMainCategory()
    {
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
            'display_target' => 'nullable|in:both,session,field',
            'main_category_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order_by' => 'nullable|integer|min:1|unique:main_categories,order_by',
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
            'order_by.integer' => '⚠️ ترتيب الفئة يجب ان يكون رقماً صحيحاً',
            'order_by.min' => '⚠️ ترتيب الفئة يجب ان يكون أكبر من 0',
            'order_by.unique' => '⚠️ رقم الترتيب مكرر بالفعل، يرجى اختيار رقم ترتيب آخر غير مستخدم.',
        ]);

        if ($request->hasFile('main_category_photo')) {
            $image = $request->file('main_category_photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/main_category/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/main_category/' . $name_gen;
        }

        MainCategory::create([
            'game_type_id' => $request->game_type_id,
            'main_category_name' => $request->main_category_name,
            'main_category_name_en' => $request->main_category_name_en,
            'main_category_description' => $request->main_category_description,
            'main_category_description_en' => $request->main_category_description_en,
            'display_target' => $request->display_target ?? 'both',
            'main_category_photo' => $save_url ?? null,
            'order_by' => $request->order_by,
            'user_id' => Auth::user()->id,
        ]);

        $notification = array(
            'message' => 'تم اضافة الفئة الرئيسية ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.main.category')->with($notification);
    }

    public function mainCategoryInactive($id){
        MainCategory::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => ' غير مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

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

        if ($mainCategory->main_category_photo && file_exists(public_path($mainCategory->main_category_photo))) {
            unlink(public_path($mainCategory->main_category_photo));
        }
        MainCategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف الفئة الرئيسية',
            'alert-type' => 'success'
        );

        return redirect()->route('all.main.category')->with($notification);
    }

    public function editMainCategory($id){
        $main_category = MainCategory::findOrFail($id);
        $gameType = GameType::latest()->get();

        return view('admin.main_category.edit_main_category',compact('main_category','gameType'));
    }

    public function editMainCategoryStore(Request $request){
        $main_category_id = $request->id;

        $request->validate([
            'game_type_id' => 'required|not_in:non',
            'main_category_name' => 'required|string|max:255',
            'main_category_name_en' => 'required|string|max:255',
            'main_category_description' => 'nullable|string',
            'main_category_description_en' => 'nullable|string',
            'display_target' => 'nullable|in:both,session,field',
            'main_category_photo' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order_by' => 'nullable|integer|min:1|unique:main_categories,order_by,' . $main_category_id,
        ], [
            'main_category_name.required' => '⚠️ الرجاء اضافة الفئة',
            'main_category_name_en.required' => '⚠️ الرجاء اضافة الفئة بالانجليزية',
            'main_category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'main_category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',
            'main_category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'main_category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح',
            'main_category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'main_category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'main_category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
            'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',
            'order_by.integer' => '⚠️ ترتيب الفئة يجب ان يكون رقماً صحيحاً',
            'order_by.min' => '⚠️ ترتيب الفئة يجب ان يكون أكبر من 0',
            'order_by.unique' => '⚠️ رقم الترتيب مكرر بالفعل، يرجى اختيار رقم ترتيب آخر غير مستخدم.',
        ]);

        $old_img = $request->old_image;
        if ($request->file('main_category_photo')) {
            $image = $request->file('main_category_photo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            $path = public_path('upload/main_category/');
            $imageManager = new ImageManager(new Driver());
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
                'display_target' => $request->display_target ?? 'both',
                'main_category_photo' => $save_url,
                'order_by' => $request->order_by,
            ]);
        } else {
            MainCategory::findOrFail($main_category_id)->update([
                'game_type_id' => $request->game_type_id,
                'main_category_name' => $request->main_category_name,
                'main_category_name_en' => $request->main_category_name_en,
                'main_category_description' => $request->main_category_description,
                'main_category_description_en' => $request->main_category_description_en,
                'display_target' => $request->display_target ?? 'both',
                'order_by' => $request->order_by,
            ]);
        }

        $notification = array(
            'message' => 'تم تعديل الفئة الرئيسية',
            'alert-type' => 'success'
        );
        return redirect()->route('all.main.category')->with($notification);
    }

    public function updateMainCategoryOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:main_categories,id',
            'order_by' => 'nullable|integer|min:1',
            'confirm_swap' => 'nullable|boolean',
        ]);

        $id = $request->id;
        $orderBy = $request->order_by;
        $confirmSwap = $request->boolean('confirm_swap', false);

        $mainCategory = MainCategory::findOrFail($id);
        $oldOrder = $mainCategory->order_by;

        if (!empty($orderBy)) {
            $conflicting = MainCategory::where('order_by', $orderBy)->where('id', '!=', $id)->first();
            if ($conflicting) {
                if (!$confirmSwap) {
                    return response()->json([
                        'status' => false,
                        'is_duplicate' => true,
                        'conflicting_id' => $conflicting->id,
                        'conflicting_name' => $conflicting->main_category_name,
                        'message' => 'الفئة الرئيسية ("' . $conflicting->main_category_name . '") تحمل نفس رقم الترتيب (' . $orderBy . '). هل تريد استبدال المراكز بينهما؟'
                    ]);
                } else {
                    // Perform swap between main categories
                    $conflicting->order_by = $oldOrder;
                    $conflicting->save();

                    $mainCategory->order_by = $orderBy;
                    $mainCategory->save();

                    return response()->json([
                        'status' => true,
                        'swapped' => true,
                        'swapped_id' => $conflicting->id,
                        'swapped_order' => $oldOrder,
                        'message' => 'تم استبدال المراكز بنجاح بين "' . $mainCategory->main_category_name . '" و "' . $conflicting->main_category_name . '"'
                    ]);
                }
            }
        }

        $mainCategory->order_by = $orderBy;
        $mainCategory->save();

        return response()->json([
            'status' => true,
            'swapped' => false,
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }

    /// API

    public function getMainCategoryApi(Request $request) {
        $game_type_id = $request->game_type_id;
        $game_target = $request->game_target ?? $request->display_target;

        $query = MainCategory::where('game_type_id', $game_type_id)
            ->where('status', 'active');

        if (!empty($game_target)) {
            $query->where(function ($q) use ($game_target) {
                $q->where('display_target', 'both')
                  ->orWhere('display_target', $game_target)
                  ->orWhereNull('display_target');
            });
        }

        $gameType = $query->orderByRaw('order_by IS NULL ASC')
            ->orderBy('order_by', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($item) {
                $item->main_category_selected = false;
                return $item;
            });

        return response()->json([
            'success' => true,
            'message' => 'Main Category retrieval successful',
            'gameType' => $gameType,
        ], 200);
    }
}
