<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use App\Models\rewardsSponsor;

use Illuminate\Support\Carbon;

use Intervention\Image\Format;

use App\Models\FollowUserRewards;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;

class RewardsSponsorController extends Controller
{

     public function AllRewardsSponsors()
    {
        $rewardsSponsor = rewardsSponsor::latest()->get();
        return view('admin.rewards_sponsors.rewards_sponsors_all', compact('rewardsSponsor'));
    } // End Method

    public function AddRewardsSponsors()
    {
                $sponsor = Sponsor::latest()->get();

        return view('admin.rewards_sponsors.rewards_sponsors_add',compact('sponsor'));

    }


    public function StoreRewardsSponsors(Request $request)
    {

          $request->validate([
                        'sponsor_id' => 'required|not_in:non',

            'title' => 'required',

             'title_en' => 'required',


            'coupon_name' => 'required',
            'number_of_points' => 'required|numeric',

            'coupon_validity' => 'required',

                        // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',


        ], [

                                'sponsor_id.not_in' => ' رجاء الاختيار.',

        'title.required' => 'الرجاء اضافة عنوان المكافأة',
        'title_en.required' => 'الرجاء اضافة عنوان المكافأة بالانجليزية',


            'coupon_name.required' => 'الرجاء اضافة اسم الكوبون',

            'coupon_discount.required' => 'الرجاء اضافة نسبة الخصم',
            'coupon_discount.numeric' => 'نسبة الخصم يجب أن تكون رقمًا',


            'number_of_points.required' => 'الرجاء اضافة عدد النقاط',
            'number_of_points.numeric' => 'الرجاء اضافة عدد النقاط أرقام',

            'coupon_validity.required' => 'الرجاء تحديد صلاحية الكوبون',


            //    'photo.required' => ' الرجاء اضافة الصورة  ',
            // 'photo.image' => '⚠️ تأكد من اضافة صورة',
            // 'photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            // 'photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',

        ]);


        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            $path = public_path('upload/rewards_sponsor/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
            // Process and save image
            // $imageResized = $imageManager->read($image)->resize(364, 176);
                        $imageResized = $imageManager->read($image);

            $imageResized->save($path . $name_gen);

            $save_url = 'upload/rewards_sponsor/' . $name_gen;
        }





        rewardsSponsor::insert([



                        'sponsor_id' => $request->sponsor_id,

            'title' => $request->title,
            'title_en' => $request->title_en,
            'number_of_points' => $request->number_of_points,

            'coupon_name' => strtoupper($request->coupon_name),
            'des' => $request->des,
                        'des_en' => $request->des_en,

                                    'latitude' => $request->latitude,
                                    'longitude' => $request->longitude,


            'coupon_validity' => $request->coupon_validity,
                        'photo' => $save_url ?? null,

            'created_at' =>Carbon::now()


        ]);


        $notification = array(
            'message' => 'تمت اضافة المكافأة',
            'alert-type' => 'success'
        );

        return redirect()->route('all.rewards.sponsors')->with($notification);



    }

    public function EditRewardsSponsors($id){






                        $sponsor = Sponsor::latest()->get();

        $rewardsSponsor = rewardsSponsor::findOrFail($id);
        return view('admin.rewards_sponsors.rewards_sponsors_edit',compact('rewardsSponsor','sponsor'));

    }// End Method


    public function UpdateRewardsSponsors(Request $request){

            $request->validate([
                        'sponsor_id' => 'required|not_in:non',

            'title' => 'required',

             'title_en' => 'required',


            'coupon_name' => 'required',
            'number_of_points' => 'required|numeric',

            'coupon_validity' => 'required',

                        // 'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',


        ], [

                                'sponsor_id.not_in' => ' رجاء الاختيار.',

        'title.required' => 'الرجاء اضافة عنوان المكافأة',
        'title_en.required' => 'الرجاء اضافة عنوان المكافأة بالانجليزية',


            'coupon_name.required' => 'الرجاء اضافة اسم الكوبون',

            'coupon_discount.required' => 'الرجاء اضافة نسبة الخصم',
            'coupon_discount.numeric' => 'نسبة الخصم يجب أن تكون رقمًا',


            'number_of_points.required' => 'الرجاء اضافة عدد النقاط',
            'number_of_points.numeric' => 'الرجاء اضافة عدد النقاط أرقام',

            'coupon_validity.required' => 'الرجاء تحديد صلاحية الكوبون',


            //    'photo.required' => ' الرجاء اضافة الصورة  ',
            // 'photo.image' => '⚠️ تأكد من اضافة صورة',
            // 'photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            // 'photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',

        ]);

     $id = $request->id;
        $old_img = $request->old_image;

        if ($request->file('photo')) {
        $image = $request->file('photo');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();



        $path = public_path('upload/rewards_sponsor/');

        $imageManager = new ImageManager(new Driver()); // Use new Imagick\Driver() for Imagick
        // Process and save image
        $imageResized = $imageManager->read($image);
        $imageResized->save($path . $name_gen);

        $save_url = 'upload/rewards_sponsor/' . $name_gen;


        if (file_exists($old_img)) {
           unlink($old_img);
        }
        rewardsSponsor::findOrFail($id)->update([

                                   'sponsor_id' => $request->sponsor_id,

            'title' => $request->title,
            'title_en' => $request->title_en,
            'number_of_points' => $request->number_of_points,

            'coupon_name' => strtoupper($request->coupon_name),
            'des' => $request->des,
                        'des_en' => $request->des_en,

                                    'latitude' => $request->latitude,
                                    'longitude' => $request->longitude,


            'coupon_validity' => $request->coupon_validity,
                        'photo' => $save_url,

            // 'special'  => $request->special,

        ]);
       $notification = array(
            'message' => 'تم تعديل المكافأة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.rewards.sponsors')->with($notification);
        } else {
            rewardsSponsor::findOrFail($id)->update([
                                               'sponsor_id' => $request->sponsor_id,

            'title' => $request->title,
            'title_en' => $request->title_en,
            'number_of_points' => $request->number_of_points,

            'coupon_name' => strtoupper($request->coupon_name),
            'des' => $request->des,
                        'des_en' => $request->des_en,

                                    'latitude' => $request->latitude,
                                    'longitude' => $request->longitude,


            'coupon_validity' => $request->coupon_validity,

                // 'special'  => $request->special,


        ]);
       $notification = array(
            'message' => 'تم تعديل المكافأة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.rewards.sponsors')->with($notification);
        } //


    }// End Method

     public function DeleteRewardsSponsors($id){



          $rewardsSponsor = rewardsSponsor::findOrFail($id);
        $img = $rewardsSponsor->photo;

        // unlink($img );

        if ($rewardsSponsor->photo && file_exists(public_path($rewardsSponsor->photo))) {
            unlink(public_path($rewardsSponsor->photo));
        }
        rewardsSponsor::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف المكافأة ',
            'alert-type' => 'success'
        );
       // return redirect()->route('all.category')->with($notification);

        return redirect()->route('all.rewards.sponsors')->with($notification);





    }// End Method


    public function getAllRewardsUsers() {

        $followUserRewards = FollowUserRewards::latest()->get();

        return view('admin.rewards_sponsors.rewards_sponsors_all_users_rewards',compact('followUserRewards'));


    }


     public function deleteRewardsUsers($id){




        // unlink($img );


        FollowUserRewards::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم الحذف  ',
            'alert-type' => 'success'
        );
       // return redirect()->route('all.category')->with($notification);

        return redirect()->route('get.all.rewards.users')->with($notification);





    }// End Method


}
