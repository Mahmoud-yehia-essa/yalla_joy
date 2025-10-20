<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameBundle;
use App\Models\GameBundleCoin;
use App\Models\GameBundleItem;
use App\Models\GameBundleHelper;
use App\Models\GameCoin;
use App\Models\GameItem;
use App\Models\GameHelper;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GameBundleController extends Controller
{
    // 🔹 Show all bundles
    public function allGameBundle()
    {
        $bundles = GameBundle::latest()->get();
        return view('admin.game_bundle.all_game_bundle', compact('bundles'));
    }


      public function addGameBundle()
    {
        $gameCoins   = GameCoin::all();
        $gameItems   = GameItem::all();
        $gameHelpers = GameHelper::all();

        return view('admin.game_bundle.add_game_bundle', compact('gameCoins','gameItems','gameHelpers'));
    }
    // // 🔹 Show add form
    // public function addGameBundle()
    // {
    //     $coins = GameCoin::all();
    //     $items = GameItem::all();
    //     $helpers = GameHelper::all();
    //     return view('admin.game_bundle.add_game_bundle', compact('coins', 'items', 'helpers'));
    // }

    // 🔹 Store bundle
    public function storeGameBundle(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'bundle_type' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Image upload
        $save_url = null;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $path = public_path('upload/game_bundle/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/game_bundle/' . $name_gen;
        }

        // Create GameBundle
        $bundle = GameBundle::create([
            'name' => $request->name,
            'name_en' => $request->name_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'hint' => $request->hint,
            'hint_en' => $request->hint_en,
            'bundle_type' => $request->bundle_type,
            'photo' => $save_url,
        ]);

        // Save selected coins
        if ($request->coin_id && $request->coin_number) {
            foreach ($request->coin_id as $key => $coin_id) {
                if ($request->coin_number[$key] > 0) {
                    GameBundleCoin::create([
                        'game_bundle_id' => $bundle->id,
                        'game_coin_id' => $coin_id,
                        'number' => $request->coin_number[$key],
                    ]);
                }
            }
        }

        // Save selected items
        if ($request->item_id && $request->item_number) {
            foreach ($request->item_id as $key => $item_id) {
                if ($request->item_number[$key] > 0) {
                    GameBundleItem::create([
                        'game_bundle_id' => $bundle->id,
                        'game_item_id' => $item_id,
                        'number' => $request->item_number[$key],
                    ]);
                }
            }
        }

        // Save selected helpers
        if ($request->helper_id && $request->helper_number) {
            foreach ($request->helper_id as $key => $helper_id) {
                if ($request->helper_number[$key] > 0) {
                    GameBundleHelper::create([
                        'game_bundle_id' => $bundle->id,
                        'game_helper_id' => $helper_id,
                        'number' => $request->helper_number[$key],
                    ]);
                }
            }
        }

        return redirect()->route('all.game.bundle')->with('success', '✅ تم إضافة الحزمة بنجاح');
    }

    // 🔹 Show edit form
    // public function editGameBundle($id)
    // {
    //     $bundle = GameBundle::findOrFail($id);
    //     $coins = GameCoin::all();
    //     $items = GameItem::all();
    //     $helpers = GameHelper::all();


    //     // Load selected
    //     $bundleCoins = GameBundleCoin::where('game_bundle_id', $id)->get()->keyBy('game_coin_id');
    //     $bundleItems = GameBundleItem::where('game_bundle_id', $id)->get()->keyBy('game_item_id');
    //     $bundleHelpers = GameBundleHelper::where('game_bundle_id', $id)->get()->keyBy('game_helper_id');

    //     return view('admin.game_bundle.edit_game_bundle', compact(
    //         'bundle','coins','items','helpers','bundleCoins','bundleItems','bundleHelpers'
    //     ));
    // }



    public function editGameBundle($id)
{
    $bundle = GameBundle::with(['bundleCoins', 'bundleItems', 'bundleHelpers'])->findOrFail($id);

    $coins = GameCoin::all();
    $items = GameItem::all();
    $helpers = GameHelper::all();

    return view('admin.game_bundle.edit_game_bundle', compact('bundle', 'coins', 'items', 'helpers'));
}

    // 🔹 Update bundle
    // public function updateGameBundle(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:game_bundles,id',
    //         'name' => 'required|string|max:255',
    //         'name_en' => 'required|string|max:255',
    //         'bundle_type' => 'required|string',
    //         'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $bundle = GameBundle::findOrFail($request->id);
    //     $save_url = $bundle->photo;

    //     if ($request->hasFile('photo')) {
    //         $image = $request->file('photo');
    //         $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    //         $path = public_path('upload/game_bundle/');
    //         if (!file_exists($path)) mkdir($path, 0777, true);

    //         $imageManager = new ImageManager(new Driver());
    //         $imageResized = $imageManager->read($image);
    //         $imageResized->save($path . $name_gen);

    //         if (file_exists($bundle->photo)) unlink($bundle->photo);
    //         $save_url = 'upload/game_bundle/' . $name_gen;
    //     }

    //     // Update main bundle
    //     $bundle->update([
    //         'name' => $request->name,
    //         'name_en' => $request->name_en,
    //         'description' => $request->description,
    //         'description_en' => $request->description_en,
    //         'hint' => $request->hint,
    //         'hint_en' => $request->hint_en,
    //         'bundle_type' => $request->bundle_type,
    //         'photo' => $save_url,
    //     ]);

    //     // Update selected coins
    //     GameBundleCoin::where('game_bundle_id', $bundle->id)->delete();
    //     if ($request->coin_id && $request->coin_number) {
    //         foreach ($request->coin_id as $key => $coin_id) {
    //             if ($request->coin_number[$key] > 0) {
    //                 GameBundleCoin::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_coin_id' => $coin_id,
    //                     'number' => $request->coin_number[$key],
    //                 ]);
    //             }
    //         }
    //     }

    //     // Update selected items
    //     GameBundleItem::where('game_bundle_id', $bundle->id)->delete();
    //     if ($request->item_id && $request->item_number) {
    //         foreach ($request->item_id as $key => $item_id) {
    //             if ($request->item_number[$key] > 0) {
    //                 GameBundleItem::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_item_id' => $item_id,
    //                     'number' => $request->item_number[$key],
    //                 ]);
    //             }
    //         }
    //     }

    //     // Update selected helpers
    //     GameBundleHelper::where('game_bundle_id', $bundle->id)->delete();
    //     if ($request->helper_id && $request->helper_number) {
    //         foreach ($request->helper_id as $key => $helper_id) {
    //             if ($request->helper_number[$key] > 0) {
    //                 GameBundleHelper::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_helper_id' => $helper_id,
    //                     'number' => $request->helper_number[$key],
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('all.game.bundle')->with('success', '✅ تم تعديل الحزمة بنجاح');
    // }


//     public function updateGameBundle(Request $request)
// {
//     $request->validate([
//         'id' => 'required|exists:game_bundles,id',
//         'name' => 'required|string|max:255',
//         'name_en' => 'required|string|max:255',
//         'bundle_type' => 'required|string',
//         'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
//     ]);

//     $bundle = GameBundle::findOrFail($request->id);
//     $save_url = $bundle->photo;

//     // 🔹 معالجة الصورة إذا تم رفع صورة جديدة
//     if ($request->hasFile('photo')) {
//         $image = $request->file('photo');
//         $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
//         $path = public_path('upload/game_bundle/');
//         if (!file_exists($path)) mkdir($path, 0777, true);

//         // استخدم Intervention Image
//         $img = Image::make($image);
//         $img->save($path . $name_gen);

//         if ($bundle->photo && file_exists($bundle->photo)) unlink($bundle->photo);
//         $save_url = 'upload/game_bundle/' . $name_gen;
//     }

//     // 🔹 تحديث بيانات الحزمة الأساسية
//     $bundle->update([
//         'name' => $request->name,
//         'name_en' => $request->name_en,
//         'description' => $request->description,
//         'description_en' => $request->description_en,
//         'hint' => $request->hint,
//         'hint_en' => $request->hint_en,
//         'bundle_type' => $request->bundle_type,
//         'photo' => $save_url,
//     ]);

//     // 🔹 تحديث العملات المرتبطة
//     $existingCoins = $bundle->bundleCoins()->pluck('game_coin_id')->toArray();
//     $newCoins = $request->coins_id ?? [];

//     // حذف العملات الغير مختارة
//     $bundle->bundleCoins()->whereNotIn('game_coin_id', $newCoins)->delete();

//     if($request->coins_id) {
//         foreach($request->coins_id as $key => $coin_id){
//             GameBundleCoin::updateOrCreate(
//                 ['game_bundle_id' => $bundle->id, 'game_coin_id' => $coin_id],
//                 ['number' => $request->coins_number[$key] ?? 0]
//             );
//         }
//     }

//     // 🔹 تحديث عناصر اللعبة المرتبطة
//     $existingItems = $bundle->bundleItems()->pluck('game_item_id')->toArray();
//     $newItems = $request->items_id ?? [];
//     $bundle->bundleItems()->whereNotIn('game_item_id', $newItems)->delete();

//     if($request->items_id) {
//         foreach($request->items_id as $key => $item_id){
//             GameBundleItem::updateOrCreate(
//                 ['game_bundle_id' => $bundle->id, 'game_item_id' => $item_id],
//                 ['number' => $request->items_number[$key] ?? 0]
//             );
//         }
//     }

//     // 🔹 تحديث عناصر المساعدة المرتبطة
//     $existingHelpers = $bundle->bundleHelpers()->pluck('game_helper_id')->toArray();
//     $newHelpers = $request->helpers_id ?? [];
//     $bundle->bundleHelpers()->whereNotIn('game_helper_id', $newHelpers)->delete();

//     if($request->helpers_id) {
//         foreach($request->helpers_id as $key => $helper_id){
//             GameBundleHelper::updateOrCreate(
//                 ['game_bundle_id' => $bundle->id, 'game_helper_id' => $helper_id],
//                 ['number' => $request->helpers_number[$key] ?? 0]
//             );
//         }
//     }

//     return redirect()->route('all.game.bundle')->with('success', '✅ تم تعديل الحزمة بنجاح');
// }


public function updateGameBundle(Request $request)
{
    $bundle = GameBundle::findOrFail($request->id);

    // تحديث البيانات الأساسية
    $bundle->name = $request->name;
    $bundle->name_en = $request->name_en;
    $bundle->description = $request->description;
    $bundle->description_en = $request->description_en;
    $bundle->hint = $request->hint;
    $bundle->hint_en = $request->hint_en;
    $bundle->bundle_type = $request->bundle_type;

    // تحديث الصورة
    if ($request->file('photo')) {
        @unlink($request->old_image);
        $image = $request->file('photo');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $image->move('upload/game_bundle/', $name_gen);
        $bundle->photo = 'upload/game_bundle/' . $name_gen;
    }

    $bundle->save();

    /** ==============================
     * 🪙 العملات
     * ============================== */
    $bundle->bundleCoins()->delete(); // امسح القديم
    if ($request->coins_id) {
        foreach ($request->coins_id as $coinId) {
            $number = $request->coins_number[$coinId] ?? 0;
            $bundle->bundleCoins()->create([
                'game_coin_id' => $coinId,
                'number' => $number,
            ]);
        }
    }


    // مسح القيم القديمة
$bundle->bundleCoins()->delete();

// إعادة الحفظ بناءً على ما اختاره المستخدم
$coinIds = $request->coin_ids ?? [];
$coinNumbers = $request->coin_numbers ?? [];

foreach ($coinIds as $coinId) {
    $bundle->bundleCoins()->create([
        'game_coin_id' => $coinId,
        'number' => $coinNumbers[$coinId] ?? 0,
    ]);
}

    /** ==============================
     * 🎮 العناصر
     * ============================== */
    $bundle->bundleItems()->delete();
    if ($request->items_id) {
        foreach ($request->items_id as $itemId) {
            $number = $request->items_number[$itemId] ?? 0;
            $bundle->bundleItems()->create([
                'game_item_id' => $itemId,
                'number' => $number,
            ]);
        }
    }

    /** ==============================
     * 🛠 المساعدات
     * ============================== */
    $bundle->bundleHelpers()->delete();
    if ($request->helpers_id) {
        foreach ($request->helpers_id as $helperId) {
            $number = $request->helpers_number[$helperId] ?? 0;
            $bundle->bundleHelpers()->create([
                'game_helper_id' => $helperId,
                'number' => $number,
            ]);
        }
    }

    $notification = [
        'message' => 'تم تعديل الحزمة بنجاح',
        'alert-type' => 'success'
    ];

    return redirect()->route('all.game.bundle')->with($notification);
}


    // 🔹 Delete bundle
    public function deleteGameBundle($id)
    {
        $bundle = GameBundle::findOrFail($id);

        if ($bundle->photo && file_exists($bundle->photo)) unlink($bundle->photo);

        $bundle->delete();

        return redirect()->route('all.game.bundle')->with('success', '🗑️ تم حذف الحزمة بنجاح');
    }
}
