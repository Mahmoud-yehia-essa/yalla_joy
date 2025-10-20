<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ranking;
use App\Models\Levels;

use Intervention\Image\Format;

use Intervention\Image\ImageManager;

use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver; // Use GD driver (or use Intervention\Image\Drivers\Imagick\Driver for Imagick)


class RankingController extends Controller
{
    // 🔹 Show all rankings
    public function allRanking()
    {
        $rankings = Ranking::with('level')->latest()->get();
        return view('admin.ranking.all_ranking', compact('rankings'));
    }

    // 🔹 Show add form
    public function addRanking()
    {
        $levels = Levels::all();
        return view('admin.ranking.add_ranking', compact('levels'));
    }

    // 🔹 Store ranking
    public function storeRanking(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'name_en'       => 'required|string|max:255',
            'level_id'      => 'required|exists:levels,id',
            'photo'         => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $save_url = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/ranking/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/ranking/' . $name_gen;
        }

        Ranking::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'level_id' => $request->level_id,
            'photo' => $save_url,
        ]);

        return redirect()->route('all.ranking')->with('success', '✅ تم إضافة الرتبة بنجاح');
    }

    // 🔹 Show edit form
    public function editRanking($id)
    {
        $ranking = Ranking::findOrFail($id);
        $levels = Levels::all();
        return view('admin.ranking.edit_ranking', compact('ranking', 'levels'));
    }

    // 🔹 Update ranking
    public function updateRanking(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:rankings,id',
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $ranking = Ranking::findOrFail($request->id);
        $save_url = $ranking->photo;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/ranking/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            if (file_exists($ranking->photo)) unlink($ranking->photo);
            $save_url = 'upload/ranking/' . $name_gen;
        }

        $ranking->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'level_id' => $request->level_id,
            'photo' => $save_url,
        ]);

        return redirect()->route('all.ranking')->with('success', '✅ تم تعديل الرتبة بنجاح');
    }

    // 🔹 Delete
    public function deleteRanking($id)
    {
        $ranking = Ranking::findOrFail($id);
        if ($ranking->photo && file_exists($ranking->photo)) unlink($ranking->photo);
        $ranking->delete();
        return redirect()->route('all.ranking')->with('success', '🗑️ تم حذف الرتبة بنجاح');
    }

    // 🔹 Make ranking inactive
public function rankingInactive($id)
{
    $ranking = Ranking::findOrFail($id);
    $ranking->status = 'inactive';
    $ranking->save();

    return redirect()->back()->with('success', '👁️ تم إخفاء الرتبة');
}

// 🔹 Make ranking active
public function rankingActive($id)
{
    $ranking = Ranking::findOrFail($id);
    $ranking->status = 'active';
    $ranking->save();

    return redirect()->back()->with('success', '✅ تم إظهار الرتبة');
}
}
