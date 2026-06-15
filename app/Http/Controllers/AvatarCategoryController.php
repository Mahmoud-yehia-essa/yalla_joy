<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvatarCategory;

class AvatarCategoryController extends Controller
{
    // 🔹 All Avatar Categories
    public function allAvatarCategory()
    {
        $avatarCategories = AvatarCategory::latest()->get();
        return view('admin.avatar_category.all_avatar_category', compact('avatarCategories'));
    }

    // 🔹 Add Avatar Category
    public function addAvatarCategory()
    {
        return view('admin.avatar_category.add_avatar_category');
    }

    // 🔹 Store Avatar Category
    public function storeAvatarCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم التصنيف',
            'image.image' => '⚠️ تأكد من إضافة صورة صحيحة',
            'image.mimes' => '⚠️ الصورة يجب أن تكون jpeg, png, jpg, gif, svg, webp',
            'image.max' => '⚠️ حجم الصورة يجب ألا يتعدى 2MB',
        ]);

        $avatarCategory = new AvatarCategory();
        $avatarCategory->name = $request->name;

        // Image upload
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $path = public_path('upload/avatar_category');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file->move($path, $filename);
            $avatarCategory->image = 'upload/avatar_category/' . $filename;
        }

        $avatarCategory->save();

        $notification = [
            'message' => '✅ تم إضافة تصنيف الأفاتار بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.avatar.category')->with($notification);
    }

    // 🔹 Edit Avatar Category
    public function editAvatarCategory($id)
    {
        $avatarCategory = AvatarCategory::findOrFail($id);
        return view('admin.avatar_category.edit_avatar_category', compact('avatarCategory'));
    }

    // 🔹 Update Avatar Category
    public function updateAvatarCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم التصنيف',
            'image.image' => '⚠️ تأكد من إضافة صورة صحيحة',
            'image.mimes' => '⚠️ الصورة يجب أن تكون jpeg, png, jpg, gif, svg, webp',
            'image.max' => '⚠️ حجم الصورة يجب ألا يتعدى 2MB',
        ]);

        $avatarCategory = AvatarCategory::findOrFail($request->id);
        $avatarCategory->name = $request->name;

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $path = public_path('upload/avatar_category');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // Remove old image if exists
            if ($avatarCategory->image && file_exists(public_path($avatarCategory->image))) {
                unlink(public_path($avatarCategory->image));
            }

            $file->move($path, $filename);
            $avatarCategory->image = 'upload/avatar_category/' . $filename;
        }

        $avatarCategory->save();

        $notification = [
            'message' => '✅ تم تحديث تصنيف الأفاتار بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.avatar.category')->with($notification);
    }

    // 🔹 Delete Avatar Category
    public function deleteAvatarCategory($id)
    {
        $avatarCategory = AvatarCategory::findOrFail($id);

        // Remove image if exists
        if ($avatarCategory->image && file_exists(public_path($avatarCategory->image))) {
            unlink(public_path($avatarCategory->image));
        }

        $avatarCategory->delete();

        $notification = [
            'message' => '🗑️ تم حذف تصنيف الأفاتار بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.avatar.category')->with($notification);
    }

    // 🔹 API: Fetch All Avatar Categories with nested Items and Coins
    public function getAvatarCategoriesApi(Request $request)
    {
        $userId = $request->input('user_id');

        $purchasedItemIds = [];
        if ($userId) {
            $purchasedItemIds = \Illuminate\Support\Facades\DB::table('user_avatar_items')
                ->where('user_id', $userId)
                ->pluck('avatar_item_id')
                ->toArray();
        }

        $categories = AvatarCategory::with('items.coin')->latest()->get();

        $categories->each(function ($category) use ($purchasedItemIds) {
            $category->items->each(function ($item) use ($purchasedItemIds) {
                $item->is_buy = in_array($item->id, $purchasedItemIds) ? 1 : 0;
            });
        });

        return response()->json([
            'success' => true,
            'message' => 'Avatar categories retrieved successfully',
            'categories' => $categories
        ], 200);
    }
}
