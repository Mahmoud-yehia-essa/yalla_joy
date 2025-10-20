<?php

namespace App\Http\Controllers;

use App\Models\GameGuide;
use App\Models\GameHelper;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GameGuideController extends Controller
{
    // عرض كل دلائل اللعبة
    public function AllGameGuide()
    {
        $guides = GameGuide::latest()->get();

        $gameHelpers = GameHelper::latest()->get();

        return view('admin.game_guide.all_game_guide', compact('guides','gameHelpers'));
    }

    // صفحة إضافة دليل جديد
    public function AddGameGuide()
    {

                $gameHelpers = GameHelper::latest()->get();

        return view('admin.game_guide.add_game_guide',compact('gameHelpers'));
    }

    // حفظ دليل جديد
    public function StoreGameGuide(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:20000', // 20MB
            'type' => 'required|in:intro,help',
        ]);

        // رفع الصورة
        $savePhoto = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/game_guide/images/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $savePhoto = 'upload/game_guide/images/' . $name_gen;
        }

        // رفع الفيديو
        $saveVideo = null;
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $video_name = hexdec(uniqid()) . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('upload/game_guide/videos/'), $video_name);
            $saveVideo = 'upload/game_guide/videos/' . $video_name;
        }

        GameGuide::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'photo' => $savePhoto,
            'video' => $saveVideo,
            'type' => $request->type,
            'game_helper_id' => $request->game_helper_id
        ]);

        $notification = [
            'message' => 'تم إضافة دليل اللعبة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.game.guide')->with($notification);
    }

    // صفحة تعديل الدليل
    public function EditGameGuide($id)
    {
        $guide = GameGuide::findOrFail($id);
                        $gameHelpers = GameHelper::latest()->get();

        return view('admin.game_guide.edit_game_guide', compact('guide','gameHelpers'));
    }

    // تحديث الدليل
    public function UpdateGameGuide(Request $request)
    {
        $guide_id = $request->id;
        $guide = GameGuide::findOrFail($guide_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:20000',
            'type' => 'required|in:intro,help',
        ]);

        // رفع الصورة إذا تم اختيارها
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/game_guide/images/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            // حذف الصورة القديمة
            if ($guide->photo && file_exists(public_path($guide->photo))) {
                unlink(public_path($guide->photo));
            }

            $guide->photo = 'upload/game_guide/images/' . $name_gen;
        }

        // رفع الفيديو إذا تم اختيار جديد
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $video_name = hexdec(uniqid()) . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('upload/game_guide/videos/'), $video_name);

            // حذف الفيديو القديم
            if ($guide->video && file_exists(public_path($guide->video))) {
                unlink(public_path($guide->video));
            }

            $guide->video = 'upload/game_guide/videos/' . $video_name;
        }

        $guide->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'type' => $request->type,
                        'game_helper_id' => $request->game_helper_id

        ]);

        $notification = [
            'message' => 'تم تعديل دليل اللعبة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.game.guide')->with($notification);
    }

    // حذف الدليل
    public function DeleteGameGuide($id)
    {
        $guide = GameGuide::findOrFail($id);

        if ($guide->photo && file_exists(public_path($guide->photo))) unlink(public_path($guide->photo));
        if ($guide->video && file_exists(public_path($guide->video))) unlink(public_path($guide->video));

        $guide->delete();

        $notification = [
            'message' => 'تم حذف دليل اللعبة',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.game.guide')->with($notification);
    }
}
