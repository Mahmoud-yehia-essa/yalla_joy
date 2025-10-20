<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\GameCoin;
use App\Models\GameItem;
use App\Models\GameBundle;
use App\Models\GameHelper;
use Illuminate\Http\Request;
use App\Models\GameBundleCoin;
use App\Models\GameBundleItem;
use App\Models\GameBundleHelper;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GameBundleController extends Controller
{
public function bundleDetails($id)
{
    $bundle = GameBundle::with(['bundleCoins.coin', 'bundleItems.item', 'bundleHelpers.helper'])->findOrFail($id);

    return view('admin.game_bundle.bundle_details_modal', compact('bundle'));
}

// public function bundleDetails($id)
// {
//     // جلب الحزمة مع العملات، عناصر اللعبة، المساعدات
//     $bundle = GameBundle::with([
//         'bundleCoins.gameCoin',
//         'bundleItems.gameItem',
//         'bundleHelpers.gameHelper'
//     ])->findOrFail($id);

//     // إعادة view صغير فقط للـ Modal
//     return view('admin.game_bundle.bundle_details', compact('bundle'));
// }
    // 🔹 عرض جميع الحزم
    public function allGameBundle()
    {
        $bundles = GameBundle::latest()->get();


        return view('admin.game_bundle.all_game_bundle', compact('bundles'));
    }

    // 🔹 صفحة إضافة حزمة جديدة
    public function addGameBundle()
    {
        $gameCoins = GameCoin::all();
        $gameItems = GameItem::all();
        // $gameHelpers = GameHelper::all();
$gameHelpers = GameHelper::where('level_id', '!=', '')->get();

                $ranking = Ranking::latest()->get();


        return view('admin.game_bundle.add_game_bundle', compact('gameCoins','gameItems','gameHelpers','ranking'));
    }

    // 🔹 تخزين الحزمة الجديدة
    // public function storeGameBundle(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'name_en' => 'required|string|max:255',
    //         'bundle_type' => 'required|string',
    //         'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     // حفظ الصورة
    //     $save_url = null;
    //     if ($request->hasFile('photo')) {
    //         $image = $request->file('photo');
    //         $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
    //         $path = public_path('upload/game_bundle/');
    //         if (!file_exists($path)) mkdir($path, 0777, true);

    //         $imageManager = new ImageManager(new Driver());
    //         $imageResized = $imageManager->read($image);
    //         $imageResized->save($path . $name_gen);

    //         $save_url = 'upload/game_bundle/' . $name_gen;
    //     }

    //     // إنشاء الحزمة
    //     $bundle = GameBundle::create([
    //         'name' => $request->name,
    //         'name_en' => $request->name_en,
    //         'description' => $request->description,
    //         'description_en' => $request->description_en,
    //         'hint' => $request->hint,
    //         'hint_en' => $request->hint_en,
    //         'bundle_type' => $request->bundle_type,
    //         'ranking_id' => $request->ranking_id,
    //         'photo' => $save_url,
    //     ]);

    //     // حفظ العملات المرتبطة
    //     if($request->coins_id && $request->coins_number) {
    //         foreach($request->coins_id as $index => $coin_id){
    //             if($coin_id){
    //                 GameBundleCoin::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_coin_id' => $coin_id,
    //                     'number' => $request->coins_number[$index] ?? 0,
    //                 ]);
    //             }
    //         }
    //     }

    //     // حفظ عناصر اللعبة
    //     if($request->items_id && $request->items_number) {

    //         foreach($request->items_id as $index => $item_id){


    //             if($item_id){


    //     //              $data = [
    //     //     'game_bundle_id' => $bundle->id,
    //     //     'game_item_id' => $item_id,
    //     //     'number' => $request->items_number[$index] ?? 0,
    //     // ];

    //     // عرض البيانات لكل عنصر


    //                 GameBundleItem::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_item_id' => $item_id,
    //                     'number' => $request->items_number[$index] ?? 0,
    //                 ]);
    //             }
    //         }
    //     }

    //     // حفظ المساعدات
    //     if($request->helpers_id && $request->helpers_number) {
    //         foreach($request->helpers_id as $index => $helper_id){
    //             if($helper_id){
    //                 GameBundleHelper::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_helper_id' => $helper_id,
    //                     'number' => $request->helpers_number[$index] ?? 0,
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('all.game.bundle')->with('success','✅ تم إضافة الحزمة بنجاح');
    // }

    public function storeGameBundle(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'bundle_type' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // حفظ الصورة
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

    // إنشاء الحزمة
    $bundle = GameBundle::create([
        'name' => $request->name,
        'name_en' => $request->name_en,
        'description' => $request->description,
        'description_en' => $request->description_en,
        'hint' => $request->hint,
        'hint_en' => $request->hint_en,
        'bundle_type' => $request->bundle_type,
        'ranking_id' => $request->ranking_id,
        'photo' => $save_url,
    ]);

    // حفظ العملات المرتبطة (now using associative array coins[id][selected|number])
    $coins = $request->input('coins', []); // array keyed by coin id
    if (is_array($coins) && count($coins) > 0) {
        foreach ($coins as $coinId => $coinData) {
            if (!empty($coinData['selected'])) {
                $number = isset($coinData['number']) && is_numeric($coinData['number']) ? (int)$coinData['number'] : 0;
                GameBundleCoin::create([
                    'game_bundle_id' => $bundle->id,
                    'game_coin_id' => $coinId,
                    'number' => $number,
                ]);
            }
        }
    }

    // حفظ عناصر اللعبة (items[id][selected|number])
    $items = $request->input('items', []);
    if (is_array($items) && count($items) > 0) {
        foreach ($items as $itemId => $itemData) {
            if (!empty($itemData['selected'])) {
                $number = isset($itemData['number']) && is_numeric($itemData['number']) ? (int)$itemData['number'] : 0;
                GameBundleItem::create([
                    'game_bundle_id' => $bundle->id,
                    'game_item_id' => $itemId,
                    'number' => $number,
                ]);
            }
        }
    }

    // حفظ المساعدات (helpers[id][selected|number])
    $helpers = $request->input('helpers', []);
    if (is_array($helpers) && count($helpers) > 0) {
        foreach ($helpers as $helperId => $helperData) {
            if (!empty($helperData['selected'])) {
                $number = isset($helperData['number']) && is_numeric($helperData['number']) ? (int)$helperData['number'] : 0;
                GameBundleHelper::create([
                    'game_bundle_id' => $bundle->id,
                    'game_helper_id' => $helperId,
                    'number' => $number,
                ]);
            }
        }
    }

    return redirect()->route('all.game.bundle')->with('success','✅ تم إضافة الحزمة بنجاح');
}


    // 🔹 صفحة تعديل الحزمة
    public function editGameBundle($id)
    {
        $bundle = GameBundle::with('bundleCoins','bundleItems','bundleHelpers')->findOrFail($id);
        $gameCoins = GameCoin::all();
        $gameItems = GameItem::all();
        // $gameHelpers = GameHelper::all();
        $gameHelpers = GameHelper::where('level_id', '!=', '')->get();

                        $ranking = Ranking::latest()->get();


        return view('admin.game_bundle.edit_game_bundle', compact('bundle','gameCoins','gameItems','gameHelpers','ranking'));
    }

    // 🔹 تحديث الحزمة
    // public function updateGameBundle(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|exists:game_bundles,id',
    //         'name' => 'required|string|max:255',
    //         'name_en' => 'required|string|max:255',
    //         'bundle_type' => 'required|string',
    //         'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

    //         if(file_exists($bundle->photo)) unlink($bundle->photo);
    //         $save_url = 'upload/game_bundle/' . $name_gen;
    //     }

    //     $bundle->update([
    //         'name' => $request->name,
    //         'name_en' => $request->name_en,
    //         'description' => $request->description,
    //         'description_en' => $request->description_en,
    //         'hint' => $request->hint,
    //         'hint_en' => $request->hint_en,
    //         'bundle_type' => $request->bundle_type,
    //                     'ranking_id' => $request->ranking_id,

    //         'photo' => $save_url,
    //     ]);

    //     // مسح البيانات القديمة
    //     GameBundleCoin::where('game_bundle_id',$bundle->id)->delete();
    //     GameBundleItem::where('game_bundle_id',$bundle->id)->delete();
    //     GameBundleHelper::where('game_bundle_id',$bundle->id)->delete();

    //     // إعادة إضافة البيانات الجديدة
    //     if($request->coins_id && $request->coins_number) {
    //         foreach($request->coins_id as $index => $coin_id){
    //             if($coin_id){
    //                 GameBundleCoin::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_coin_id' => $coin_id,
    //                     'number' => $request->coins_number[$index] ?? 0,
    //                 ]);
    //             }
    //         }
    //     }

    //     if($request->items_id && $request->items_number) {
    //         foreach($request->items_id as $index => $item_id){
    //             if($item_id){
    //                 GameBundleItem::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_item_id' => $item_id,
    //                     'number' => $request->items_number[$index] ?? 0,
    //                 ]);
    //             }
    //         }
    //     }

    //     if($request->helpers_id && $request->helpers_number) {
    //         foreach($request->helpers_id as $index => $helper_id){
    //             if($helper_id){
    //                 GameBundleHelper::create([
    //                     'game_bundle_id' => $bundle->id,
    //                     'game_helper_id' => $helper_id,
    //                     'number' => $request->helpers_number[$index] ?? 0,
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('all.game.bundle')->with('success','✅ تم تعديل الحزمة بنجاح');
    // }


public function updateGameBundle(Request $request)
{
    $request->validate([
        'id' => 'required|exists:game_bundles,id',
        'name' => 'required|string|max:255',
        'name_en' => 'required|string|max:255',
        'bundle_type' => 'required|string',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $bundle = GameBundle::findOrFail($request->id);

    $save_url = $bundle->photo;
    if ($request->hasFile('photo')) {
        $image = $request->file('photo');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        $path = public_path('upload/game_bundle/');
        if (!file_exists($path)) mkdir($path, 0777, true);

        $imageManager = new ImageManager(new Driver());
        $imageResized = $imageManager->read($image);
        $imageResized->save($path . $name_gen);

        if(file_exists($bundle->photo)) unlink($bundle->photo);
        $save_url = 'upload/game_bundle/' . $name_gen;
    }

    $bundle->update([
        'name' => $request->name,
        'name_en' => $request->name_en,
        'description' => $request->description,
        'description_en' => $request->description_en,
        'hint' => $request->hint,
        'hint_en' => $request->hint_en,
        'bundle_type' => $request->bundle_type,
        'ranking_id' => $request->ranking_id,
        'photo' => $save_url,
    ]);

    // مسح البيانات القديمة
    GameBundleCoin::where('game_bundle_id',$bundle->id)->delete();
    GameBundleItem::where('game_bundle_id',$bundle->id)->delete();
    GameBundleHelper::where('game_bundle_id',$bundle->id)->delete();

    // إضافة العملات
    if ($request->coins) {
        foreach ($request->coins as $coin) {
            if (!empty($coin['id'])) {
                GameBundleCoin::create([
                    'game_bundle_id' => $bundle->id,
                    'game_coin_id' => $coin['id'],
                    'number' => $coin['number'] ?? 0,
                ]);
            }
        }
    }

    // إضافة عناصر اللعبة
    if ($request->items) {
        foreach ($request->items as $item) {
            if (!empty($item['id'])) {
                GameBundleItem::create([
                    'game_bundle_id' => $bundle->id,
                    'game_item_id' => $item['id'],
                    'number' => $item['number'] ?? 0,
                ]);
            }
        }
    }

    // إضافة المساعدات
    if ($request->helpers) {
        foreach ($request->helpers as $helper) {
            if (!empty($helper['id'])) {
                GameBundleHelper::create([
                    'game_bundle_id' => $bundle->id,
                    'game_helper_id' => $helper['id'],
                    'number' => $helper['number'] ?? 0,
                ]);
            }
        }
    }

    return redirect()->route('all.game.bundle')->with('success','✅ تم تعديل الحزمة بنجاح');
}





    // 🔹 حذف الحزمة
    public function deleteGameBundle($id)
    {
        $bundle = GameBundle::findOrFail($id);

        if($bundle->photo && file_exists($bundle->photo)) unlink($bundle->photo);

        // حذف العناصر المرتبطة تلقائيًا بفضل cascadeOnDelete
        $bundle->delete();

        return redirect()->route('all.game.bundle')->with('success','🗑️ تم حذف الحزمة بنجاح');
    }


    // 🔹 Make Game Helper inactive
public function gameBundleInactive($id)
{
    $helper = GameBundle::findOrFail($id);
    $helper->status = 'inactive';
    $helper->save();

    return redirect()->back()->with('success', '👁️ تم الإخفاء ');
}

// 🔹 Make Game Helper active
public function gameBundleActive($id)
{
    $helper = GameBundle::findOrFail($id);
    $helper->status = 'active';
    $helper->save();

    return redirect()->back()->with('success', '✅ تم الاخفاء ');
}
}
