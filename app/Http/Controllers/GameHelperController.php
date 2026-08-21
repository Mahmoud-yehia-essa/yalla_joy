<?php

namespace App\Http\Controllers;

use App\Models\Levels;
use App\Models\GameHelper;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GameHelperController extends Controller
{
    // 🔹 Show all game helpers
    public function allGameHelper()
    {
        $gameHelpers = GameHelper::orderBy('order_num', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.game_helper.all_game_helper', compact('gameHelpers'));
    }

    // 🔹 Show add form
    public function addGameHelper()
    {
        $levels = Levels::latest()->get();
        return view('admin.game_helper.add_game_helper', compact('levels'));
    }

    // 🔹 Store game helper
    public function storeGameHelper(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tool_key' => 'nullable|string|max:100',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم وسيلة المساعدة',
            'photo.required' => '⚠️ الرجاء اختيار أيقونة وسيلة المساعدة',
        ]);

        $save_url = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/game_helper/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            try {
                $imageManager = new ImageManager(new Driver());
                $imageResized = $imageManager->read($image);
                $imageResized->save($path . $name_gen);
            } catch (\Exception $e) {
                $image->move($path, $name_gen);
            }

            $save_url = 'upload/game_helper/' . $name_gen;
        }

        GameHelper::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'tool_key' => $request->tool_key ?? strtolower(str_replace(' ', '_', $request->name_en ?? $request->name)),
            'description' => $request->description,
            'description_en' => $request->description_en,
            'note' => $request->note,
            'use_before_question' => $request->has('use_before_question') ? 1 : 0,
            'order_num' => $request->order_num ?? 0,
            'level_id' => $request->level_id,
            'status' => 'active',
            'photo' => $save_url,
        ]);

        return redirect()->route('all.game.helper')->with('success', '✅ تم إضافة وسيلة المساعدة بنجاح');
    }

    // 🔹 Show edit form
    public function editGameHelper($id)
    {
        $gameHelper = GameHelper::findOrFail($id);
        $allHelpers = GameHelper::where('id', '!=', $id)->get(['id', 'name', 'order_num']);
        $levels = Levels::latest()->get();
        return view('admin.game_helper.edit_game_helper', compact('gameHelper', 'allHelpers', 'levels'));
    }

    // 🔹 Update game helper
    public function updateGameHelper(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:game_helpers,id',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
        ], [
            'name.required' => '⚠️ الرجاء اضافة اسم وسيلة المساعدة',
        ]);

        $gameHelper = GameHelper::findOrFail($request->id);
        $save_url = $gameHelper->photo;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/game_helper/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            try {
                $imageManager = new ImageManager(new Driver());
                $imageResized = $imageManager->read($image);
                $imageResized->save($path . $name_gen);
            } catch (\Exception $e) {
                $image->move($path, $name_gen);
            }

            if ($gameHelper->photo && file_exists(public_path($gameHelper->photo))) {
                @unlink(public_path($gameHelper->photo));
            }
            $save_url = 'upload/game_helper/' . $name_gen;
        }

        $newOrder = $request->order_num !== null ? (int)$request->order_num : (int)$gameHelper->order_num;
        $oldOrder = (int)$gameHelper->order_num;
        $swapMsg = '';

        if ($newOrder !== $oldOrder) {
            $conflictingHelper = GameHelper::where('order_num', $newOrder)
                ->where('id', '!=', $gameHelper->id)
                ->first();

            if ($conflictingHelper && $request->has('swap_order') && $request->swap_order == '1') {
                $conflictingHelper->update(['order_num' => $oldOrder]);
                $swapMsg = " وتم تبديل الترتيب مع وسيلة ({$conflictingHelper->name}) لتصبح بترتيب ($oldOrder)";
            }
        }

        $gameHelper->update([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'tool_key' => $gameHelper->tool_key,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'note' => $gameHelper->note,
            'use_before_question' => $gameHelper->use_before_question,
            'order_num' => $newOrder,
            'level_id' => $request->level_id,
            'photo' => $save_url,
        ]);

        return redirect()->route('all.game.helper')->with('success', '✅ تم تعديل وسيلة المساعدة بنجاح' . $swapMsg);
    }

    // 🔹 Delete game helper
    public function deleteGameHelper($id)
    {
        $gameHelper = GameHelper::findOrFail($id);
        if ($gameHelper->photo && file_exists(public_path($gameHelper->photo))) {
            @unlink(public_path($gameHelper->photo));
        }
        $gameHelper->delete();

        return redirect()->route('all.game.helper')->with('success', '🗑️ تم حذف وسيلة المساعدة بنجاح');
    }

    // 🔹 Make Game Helper inactive (إخفاء)
    public function gameHelperInactive($id)
    {
        $helper = GameHelper::findOrFail($id);
        $helper->status = 'inactive';
        $helper->save();

        return redirect()->back()->with('success', '👁️ تم إخفاء وسيلة المساعدة');
    }

    // 🔹 Make Game Helper active (إظهار)
    public function gameHelperActive($id)
    {
        $helper = GameHelper::findOrFail($id);
        $helper->status = 'active';
        $helper->save();

        return redirect()->back()->with('success', '✅ تم إظهار وسيلة المساعدة');
    }

    // 🔹 API: Get all active game helpers
    public function getGameHelperApi()
    {
        $gameHelper = GameHelper::where('status', 'active')
            ->orderBy('order_num', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'name_en' => $item->name_en,
                    'tool_key' => $item->tool_key,
                    'description' => $item->description,
                    'description_en' => $item->description_en,
                    'note' => $item->note,
                    'use_before_question' => (bool) $item->use_before_question,
                    'photo' => $item->photo ? asset($item->photo) : null,
                    'raw_photo' => $item->photo,
                    'status' => $item->status,
                    'game_helper_selected' => false,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'gameHelper retrieval successful',
            'gameHelper' => $gameHelper,
        ], 200);
    }
}
