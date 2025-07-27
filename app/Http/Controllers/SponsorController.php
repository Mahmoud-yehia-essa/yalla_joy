<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;

use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver; // Use GD driver (or use Intervention\Image\Drivers\Imagick\Driver for Imagick)


class SponsorController extends Controller
{
        public function addSponsorHomeCate(){

            $getSponsorHome = Sponsor::findOrFail(1);

        return view('admin.sponsor.sponsor_home_cate_add',compact('getSponsorHome'));
    }


       public function addSponsorQuestion(){

            $getSponsorHome = Sponsor::findOrFail(2);

        return view('admin.sponsor.sponsor_home_cate_add',compact('getSponsorHome'));
    }



       public function editHomeCateStore(Request $request){




        $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => '⚠️ الرجاء اضافة اسم الراعي',
            'title.string' => '⚠️ الرجاء التأكد من كتابة الراعي بشكل صحيح',


            'photo.required' => '⚠️ الرجاء اضافة صورة ',
            'photo.image' => '⚠️ تأكد من اضافة صورة',
            'photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
        ]);


        $id = $request->id;
        $old_img = $request->old_image;
        if ($request->file('photo')) {
        $image = $request->file('photo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();



        $path = public_path('upload/sponsor/');

        $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
        // Process and save image
        // $imageResized = $imageManager->read($image)->resize(364, 176);
        $imageResized = $imageManager->read($image);

        $imageResized->save($path . $name_gen);

        $save_url = 'upload/sponsor/' . $name_gen;


        if (file_exists($old_img)) {
           unlink($old_img);
        }
        Sponsor::findOrFail($id)->update([
            'title' => $request->title,
            'des' => $request->des,
            'photo' => $save_url ,
            'link'  => $request->link,

        ]);
       $notification = array(
            'message' => 'تم تعديل الراعي',
            'alert-type' => 'success'
        );
        // return redirect()->route('sponsor.add.cate')->with($notification);
                return redirect()->back()->with($notification);

        } else {
            Sponsor::findOrFail($id)->update([
               'title' => $request->title,
            'des' => $request->des,
            'link'  => $request->link,


        ]);
       $notification = array(
            'message' => 'تم تعديل الراعي',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
        } // end else
    }// End Method


    /// API

      public function getSponsor()
    {


    $sponsor = Sponsor::latest()->get();



    return response()->json($sponsor);
    }

}
