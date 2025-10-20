<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemType;

class ItemTypeController extends Controller
{
    // 🔹 All Item Types
    public function allItemType()
    {
        $itemTypes = ItemType::latest()->get();
        return view('admin.item_type.all_item_type', compact('itemTypes'));
    }

    // 🔹 Add Item Type
    public function addItemType()
    {
        return view('admin.item_type.add_item_type');
    }

    // 🔹 Store Item Type
    public function storeItemType(Request $request)
    {

        $request->validate([
    'name' => 'required|string|max:255',
    'name_en' => 'required|string|max:255',
], [
    'name.required' => '⚠️ الرجاء إدخال اسم النوع',
    'name_en.required' => '⚠️ الرجاء إدخال اسم النوع بالإنجليزية',
]);
        $itemType = new ItemType();
        $itemType->name = $request->name;
        $itemType->name_en = $request->name_en;
        $itemType->description = $request->description;
        $itemType->description_en = $request->description_en;

        // صورة
        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/item_type'), $filename);
            $itemType->photo = 'upload/item_type/'.$filename;
        }

        $itemType->save();

        return redirect()->route('all.item.type')->with('success','✅ تم إضافة نوع العنصر بنجاح');
    }

    // 🔹 Edit Item Type
    public function editItemType($id)
    {
        $itemType = ItemType::findOrFail($id);
        return view('admin.item_type.edit_item_type', compact('itemType'));
    }

    // 🔹 Update Item Type
    public function updateItemType(Request $request)
    {

        $request->validate([
    'name' => 'required|string|max:255',
    'name_en' => 'required|string|max:255',
], [
    'name.required' => '⚠️ الرجاء إدخال اسم النوع',
    'name_en.required' => '⚠️ الرجاء إدخال اسم النوع بالإنجليزية',
]);
        $itemType = ItemType::findOrFail($request->id);
        $itemType->name = $request->name;
        $itemType->name_en = $request->name_en;
        $itemType->description = $request->description;
        $itemType->description_en = $request->description_en;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/item_type'), $filename);
            $itemType->photo = 'upload/item_type/'.$filename;
        }

        $itemType->save();

        return redirect()->route('all.item.type')->with('success','✅ تم تحديث نوع العنصر');
    }

    // 🔹 Delete Item Type
    public function deleteItemType($id)
    {
        $itemType = ItemType::findOrFail($id);

        if ($itemType->photo && file_exists(public_path($itemType->photo))) {
            unlink(public_path($itemType->photo));
        }

        $itemType->delete();

        return redirect()->back()->with('success','🗑️ تم حذف نوع العنصر');
    }

    // 🔹 Active / Inactive
    public function itemTypeInactive($id)
    {
        $itemType = ItemType::findOrFail($id);
        $itemType->status = 'inactive';
        $itemType->save();

        return redirect()->back()->with('success','👁️ تم إخفاء نوع العنصر');
    }

    public function itemTypeActive($id)
    {
        $itemType = ItemType::findOrFail($id);
        $itemType->status = 'active';
        $itemType->save();

        return redirect()->back()->with('success','✅ تم إظهار نوع العنصر');
    }
}
