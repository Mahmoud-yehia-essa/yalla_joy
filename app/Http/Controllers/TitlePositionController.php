<?php

namespace App\Http\Controllers;

use App\Models\TitlePosition;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TitlePositionController extends Controller
{
    // Show all title positions
    public function titlePosition()
    {
        $titlePositions = TitlePosition::latest()->get();
        return view('admin.title_position.all_title_position', compact('titlePositions'));
    }

    // Show add form
    public function addTitlePosition()
    {
        return view('admin.title_position.add_title_position');
    }

    // Store new title position
    public function storeTitlePosition(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'name_en'   => 'required|string|max:255',
            'photo'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'coins'     => 'nullable|integer',
            'points'    => 'nullable|integer',
            'type' => 'required|not_in:non',

        ], [
            'name.required'    => '⚠️ الرجاء اضافة الاسم',
            'name_en.required' => '⚠️ الرجاء اضافة الاسم بالانجليزية',
            'photo.required'   => '⚠️ الرجاء اضافة صورة',
            'photo.image'      => '⚠️ تأكد من رفع صورة صحيحة',
            'photo.mimes'      => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, أو gif',
            'photo.max'        => '⚠️ حجم الصورة يجب الا يتعدى 2MB',
            'type.required' => '⚠️ الرجاء اختيار نوع العنصر.',

                        'type.not_in' => '⚠️ الرجاء اختيار نوع العنصر.',




        ]);

        $save_url = null;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/title_position/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/title_position/' . $name_gen;
        }

        TitlePosition::create([
            'name'      => $request->name,
            'name_en'   => $request->name_en,
            'photo'     => $save_url,
            'coins'     => $request->coins,
            'points'    => $request->points,
            'type'    => $request->type,

        ]);

        return redirect()->route('all.title.position')->with([
            'message' => 'تم اضافة المركز بنجاح',
            'alert-type' => 'success'
        ]);
    }

    // Edit form
    public function editTitlePosition($id)
    {
        $titlePosition = TitlePosition::findOrFail($id);
        return view('admin.title_position.edit_title_position', compact('titlePosition'));
    }

    // Store edit
    public function editTitlePositionStore(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'name_en'   => 'required|string|max:255',
            'photo'     => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'coins'     => 'nullable|integer',
            'points'    => 'nullable|integer',
            'type' => 'required|not_in:non',

        ],[
            'name.required'    => '⚠️ الرجاء اضافة الاسم',
            'name_en.required' => '⚠️ الرجاء اضافة الاسم بالانجليزية',
            'photo.required'   => '⚠️ الرجاء اضافة صورة',
            'photo.image'      => '⚠️ تأكد من رفع صورة صحيحة',
            'photo.mimes'      => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, أو gif',
            'photo.max'        => '⚠️ حجم الصورة يجب الا يتعدى 2MB',
            'type.required' => '⚠️ الرجاء اختيار نوع العنصر.',
                                    'type.not_in' => '⚠️ الرجاء اختيار نوع العنصر.',



        ]);

        $id = $request->id;
        $old_img = $request->old_image;
        $titlePosition = TitlePosition::findOrFail($id);

        $save_url = $titlePosition->photo;

        if ($request->file('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/title_position/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            if ($old_img && file_exists(public_path($old_img))) {
                unlink(public_path($old_img));
            }

            $save_url = 'upload/title_position/' . $name_gen;
        }

        $titlePosition->update([
            'name'      => $request->name,
            'name_en'   => $request->name_en,
            'photo'     => $save_url,
            'coins'     => $request->coins,
            'points'    => $request->points,
            'type'    => $request->type,

        ]);

        return redirect()->route('all.title.position')->with([
            'message' => 'تم تعديل المركز بنجاح',
            'alert-type' => 'success'
        ]);
    }

    // Delete
    public function deleteTitlePosition($id)
    {
        $titlePosition = TitlePosition::findOrFail($id);

        if ($titlePosition->photo && file_exists(public_path($titlePosition->photo))) {
            unlink(public_path($titlePosition->photo));
        }

        $titlePosition->delete();

        return redirect()->route('all.title.position')->with([
            'message' => 'تم حذف المركز',
            'alert-type' => 'success'
        ]);
    }

    // Inactive
    public function titlePositionInactive($id)
    {
        TitlePosition::findOrFail($id)->update(['status' => 'inactive']);
        return redirect()->back()->with([
            'message' => 'تم إلغاء تفعيل المركز',
            'alert-type' => 'success'
        ]);
    }

    // Active
    public function titlePositionActive($id)
    {
        TitlePosition::findOrFail($id)->update(['status' => 'active']);
        return redirect()->back()->with([
            'message' => 'تم تفعيل المركز',
            'alert-type' => 'success'
        ]);
    }
}
