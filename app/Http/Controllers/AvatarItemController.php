<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvatarItems;
use App\Models\AvatarCategory;
use App\Models\GameCoin;
use App\Exports\AvatarItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class AvatarItemController extends Controller
{
    // 🔹 All Avatar Items
    public function allAvatarItem(Request $request)
    {
        $query = AvatarItems::with(['category', 'coin']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('price_type')) {
            if ($request->price_type == 'free') {
                $query->where('is_free', 1);
            } elseif ($request->price_type == 'paid') {
                $query->where('is_free', 0);
            }
        }

        $perPage = 20;
        $avatarItems = $query->latest()->paginate($perPage);

        if ($request->ajax()) {
            $categories = AvatarCategory::all();
            $coins = GameCoin::all();
            $startKey = ($avatarItems->currentPage() - 1) * $avatarItems->perPage();

            $html = view('admin.avatar_item.partials.avatar_item_rows', compact('avatarItems', 'categories', 'coins', 'startKey'))->render();

            return response()->json([
                'html' => $html,
                'has_more' => $avatarItems->hasMorePages(),
                'next_page' => $avatarItems->currentPage() + 1,
            ]);
        }

        $categories = AvatarCategory::all();
        $coins = GameCoin::all();

        return view('admin.avatar_item.all_avatar_item', compact('avatarItems', 'categories', 'coins'));
    }

    // 🔹 Export Avatar Items to Excel
    public function exportAvatarItem(Request $request)
    {
        return Excel::download(new AvatarItemsExport($request), 'avatar_items_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    // 🔹 Add Avatar Item
    public function addAvatarItem()
    {
        $categories = AvatarCategory::all();
        $coins = GameCoin::all();
        return view('admin.avatar_item.add_avatar_item', compact('categories', 'coins'));
    }

    // 🔹 Store Avatar Item
    public function storeAvatarItem(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:avatar_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gender' => 'required|in:boy,girl',
        ];

        if ($request->is_free) {
            $rules['game_coin_id'] = 'nullable|exists:game_coins,id';
            $rules['coins_number'] = 'nullable|integer|min:0';
        } else {
            $rules['game_coin_id'] = 'required|exists:game_coins,id';
            $rules['coins_number'] = 'required|integer|min:0';
        }

        $request->validate($rules, [
            'name.required' => '⚠️ الرجاء إدخال اسم العنصر',
            'category_id.required' => '⚠️ الرجاء اختيار التصنيف',
            'category_id.exists' => '⚠️ التصنيف المختار غير موجود',
            'game_coin_id.required' => '⚠️ الرجاء اختيار نوع العملة',
            'game_coin_id.exists' => '⚠️ نوع العملة المختار غير موجود',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
            'coins_number.integer' => '⚠️ عدد العملات يجب أن يكون رقماً صحيحاً',
            'coins_number.min' => '⚠️ عدد العملات لا يمكن أن يكون أقل من 0',
            'image.image' => '⚠️ تأكد من إضافة صورة صحيحة',
            'image.mimes' => '⚠️ الصورة يجب أن تكون jpeg, png, jpg, gif, svg, webp',
            'image.max' => '⚠️ حجم الصورة يجب ألا يتعدى 2MB',
            'gender.required' => '⚠️ الرجاء تحديد نوع الأفاتار (ولد أو بنت)',
            'gender.in' => '⚠️ القيمة المحددة للنوع غير صحيحة',
        ]);

        $avatarItem = new AvatarItems();
        $avatarItem->name = $request->name;
        $avatarItem->category_id = $request->category_id;
        $avatarItem->is_free = $request->is_free ? 1 : 0;
        $avatarItem->game_coin_id = $request->is_free ? null : $request->game_coin_id;
        $avatarItem->coins_number = $request->is_free ? null : $request->coins_number;
        $avatarItem->gender = $request->gender;

        // Image upload
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $path = public_path('upload/avatar_item');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $file->move($path, $filename);
            $avatarItem->image = 'upload/avatar_item/' . $filename;
        }

        $avatarItem->save();

        $notification = [
            'message' => '✅ تم إضافة عنصر الأفاتار بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.avatar.item')->with($notification);
    }

    // 🔹 Edit Avatar Item
    public function editAvatarItem($id)
    {
        $avatarItem = AvatarItems::findOrFail($id);
        $categories = AvatarCategory::all();
        $coins = GameCoin::all();
        return view('admin.avatar_item.edit_avatar_item', compact('avatarItem', 'categories', 'coins'));
    }

    // 🔹 Update Avatar Item
    public function updateAvatarItem(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:avatar_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'gender' => 'required|in:boy,girl',
        ];

        if ($request->is_free) {
            $rules['game_coin_id'] = 'nullable|exists:game_coins,id';
            $rules['coins_number'] = 'nullable|integer|min:0';
        } else {
            $rules['game_coin_id'] = 'required|exists:game_coins,id';
            $rules['coins_number'] = 'required|integer|min:0';
        }

        $request->validate($rules, [
            'name.required' => '⚠️ الرجاء إدخال اسم العنصر',
            'category_id.required' => '⚠️ الرجاء اختيار التصنيف',
            'category_id.exists' => '⚠️ التصنيف المختار غير موجود',
            'game_coin_id.required' => '⚠️ الرجاء اختيار نوع العملة',
            'game_coin_id.exists' => '⚠️ نوع العملة المختار غير موجود',
            'coins_number.required' => '⚠️ الرجاء إدخال عدد العملات',
            'coins_number.integer' => '⚠️ عدد العملات يجب أن يكون رقماً صحيحاً',
            'coins_number.min' => '⚠️ عدد العملات لا يمكن أن يكون أقل من 0',
            'image.image' => '⚠️ تأكد من إضافة صورة صحيحة',
            'image.mimes' => '⚠️ الصورة يجب أن تكون jpeg, png, jpg, gif, svg, webp',
            'image.max' => '⚠️ حجم الصورة يجب ألا يتعدى 2MB',
            'gender.required' => '⚠️ الرجاء تحديد نوع الأفاتار (ولد أو بنت)',
            'gender.in' => '⚠️ القيمة المحددة للنوع غير صحيحة',
        ]);

        $avatarItem = AvatarItems::findOrFail($request->id);
        $avatarItem->name = $request->name;
        $avatarItem->category_id = $request->category_id;
        $avatarItem->is_free = $request->is_free ? 1 : 0;
        $avatarItem->game_coin_id = $request->is_free ? null : $request->game_coin_id;
        $avatarItem->coins_number = $request->is_free ? null : $request->coins_number;
        $avatarItem->gender = $request->gender;

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $path = public_path('upload/avatar_item');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // Remove old image if exists
            if ($avatarItem->image && file_exists(public_path($avatarItem->image))) {
                unlink(public_path($avatarItem->image));
            }

            $file->move($path, $filename);
            $avatarItem->image = 'upload/avatar_item/' . $filename;
        }

        $avatarItem->save();

        $notification = [
            'message' => '✅ تم تحديث عنصر الأفاتار بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.avatar.item')->with($notification);
    }

    // 🔹 Delete Avatar Item
    public function deleteAvatarItem($id)
    {
        $item = AvatarItems::findOrFail($id);

        // Delete user purchased associations
        \Illuminate\Support\Facades\DB::table('user_avatar_items')->where('avatar_item_id', $id)->delete();

        // Remove image file if exists
        if ($item->image && file_exists(public_path($item->image))) {
            @unlink(public_path($item->image));
        }

        $item->delete();

        $notification = [
            'message' => '🗑️ تم حذف عنصر الأفاتار بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // 🔹 API: Fetch Avatar Items by Category ID
    public function getAvatarItemsByCategoryApi(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:avatar_categories,id',
        ], [
            'category_id.required' => '⚠️ الرجاء إرسال معرف التصنيف',
            'category_id.exists' => '⚠️ التصنيف المحدد غير موجود',
        ]);

        $userId = $request->input('user_id');

        $purchasedItemIds = [];
        if ($userId) {
            $purchasedItemIds = \Illuminate\Support\Facades\DB::table('user_avatar_items')
                ->where('user_id', $userId)
                ->pluck('avatar_item_id')
                ->toArray();
        }

        $items = AvatarItems::with('coin')
            ->where('category_id', $request->category_id)
            ->where('status', 'active')
            ->latest()
            ->get();

        $items->each(function ($item) use ($purchasedItemIds) {
            $item->is_buy = in_array($item->id, $purchasedItemIds) ? 1 : 0;
        });

        return response()->json([
            'success' => true,
            'message' => 'Avatar items retrieved successfully',
            'items' => $items
        ], 200);
    }

    // 🔹 Purchased Users
    public function purchasedUsers($id)
    {
        $avatarItem = AvatarItems::findOrFail($id);
        $users = $avatarItem->users()->latest('user_avatar_items.created_at')->get();

        return view('admin.avatar_item.purchased_users', compact('avatarItem', 'users'));
    }

    // 🔹 API: Purchase Avatar Item
    public function buyAvatarItemApi(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'avatar_item_id' => 'required|exists:avatar_items,id',
        ], [
            'user_id.required' => '⚠️ معرف المستخدم مطلوب',
            'user_id.exists' => '⚠️ المستخدم غير موجود',
            'avatar_item_id.required' => '⚠️ معرف عنصر الأفاتار مطلوب',
            'avatar_item_id.exists' => '⚠️ عنصر الأفاتار المختار غير موجود',
        ]);

        $userId = $request->user_id;
        $avatarItemId = $request->avatar_item_id;

        $avatarItem = AvatarItems::findOrFail($avatarItemId);

        // 1. التحقق من أن المستخدم لم يشترِ هذا العنصر مسبقاً
        $alreadyBought = \Illuminate\Support\Facades\DB::table('user_avatar_items')
            ->where('user_id', $userId)
            ->where('avatar_item_id', $avatarItemId)
            ->exists();

        if ($alreadyBought) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بشراء هذا العنصر مسبقاً'
            ], 200);
        }

        // 2. إذا كان العنصر غير مجاني وله تكلفة عملات
        if ($avatarItem->is_free == 0 && $avatarItem->game_coin_id && $avatarItem->coins_number > 0) {
            $gameCoinId = $avatarItem->game_coin_id;
            $cost = $avatarItem->coins_number;

            // حساب الرصيد الحالي من نوع العملة المطلوب
            $totalAdd = \App\Models\UserCoin::where('user_id', $userId)
                ->where('game_coin_id', $gameCoinId)
                ->where('type', 'add')
                ->sum('coins_number');

            $totalDeduct = \App\Models\UserCoin::where('user_id', $userId)
                ->where('game_coin_id', $gameCoinId)
                ->where('type', '!=', 'add')
                ->sum('coins_number');

            $balance = $totalAdd - abs($totalDeduct);

            if ($balance < $cost) {
                return response()->json([
                    'success' => false,
                    'message' => 'عذراً، رصيدك من العملات غير كافٍ لشراء هذا العنصر'
                ], 200);
            }

            // خصم التكلفة وتسجيلها في جدول حركات عملات المستخدم
            \App\Models\UserCoin::create([
                'user_id' => $userId,
                'game_coin_id' => $gameCoinId,
                'coins_number' => -abs($cost),
                'type' => 'withdraw'
            ]);
        }

        // 3. تسجيل عملية الشراء
        \App\Models\userAvatarItems::create([
            'user_id' => $userId,
            'avatar_item_id' => $avatarItemId
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم شراء عنصر الأفاتار بنجاح'
        ], 200);
    }

    public function avatarItemInactive($id)
    {
        AvatarItems::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => 'تم إخفاء عنصر الأفاتار بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function avatarItemActive($id)
    {
        AvatarItems::findOrFail($id)->update(['status' => 'active']);
        $notification = array(
            'message' => 'تم إظهار عنصر الأفاتار بنجاح',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    // 🔹 AJAX Update Item Name
    public function ajaxUpdateName(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:avatar_items,id',
            'name' => 'required|string|max:255',
        ], [
            'name.required' => '⚠️ الرجاء إدخال اسم العنصر',
        ]);

        $item = AvatarItems::findOrFail($request->id);
        $item->name = $request->name;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => '✅ تم تحديث اسم العنصر بنجاح',
            'name' => $item->name,
        ]);
    }

    // 🔹 AJAX Update Item Image
    public function ajaxUpdateImage(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:avatar_items,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'image.required' => '⚠️ الرجاء اختيار صورة',
            'image.image' => '⚠️ ملف غير صحيح',
            'image.mimes' => '⚠️ بصيغ jpeg, png, jpg, gif, svg, webp فقط',
            'image.max' => '⚠️ حجم الصورة أقصاه 2MB',
        ]);

        $item = AvatarItems::findOrFail($request->id);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $path = public_path('upload/avatar_item');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            if ($item->image && file_exists(public_path($item->image))) {
                @unlink(public_path($item->image));
            }

            $file->move($path, $filename);
            $item->image = 'upload/avatar_item/' . $filename;
            $item->save();
        }

        return response()->json([
            'success' => true,
            'message' => '✅ تم تحديث صورة العنصر بنجاح',
            'image_url' => asset($item->image),
        ]);
    }

    // 🔹 AJAX Update Item Currency
    public function ajaxUpdateCurrency(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:avatar_items,id',
            'currency' => 'required',
        ]);

        $item = AvatarItems::findOrFail($request->id);

        if ($request->currency === 'free') {
            $item->is_free = 1;
            $item->game_coin_id = null;
            $item->coins_number = 0;
        } else {
            $coin = GameCoin::findOrFail($request->currency);
            $item->is_free = 0;
            $item->game_coin_id = $coin->id;
        }

        $item->save();
        $item->load('coin');

        return response()->json([
            'success' => true,
            'message' => '✅ تم تحديث نوع العملة بنجاح',
            'is_free' => $item->is_free,
            'coin_id' => $item->game_coin_id,
            'coin_name' => $item->is_free ? 'مجاني' : ($item->coin ? $item->coin->name : '---'),
            'coins_number' => $item->coins_number,
        ]);
    }

    // 🔹 AJAX Update Item Price
    public function ajaxUpdatePrice(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:avatar_items,id',
            'coins_number' => 'required|integer|min:0',
        ], [
            'coins_number.required' => '⚠️ الرجاء إدخال السعر',
            'coins_number.integer' => '⚠️ السعر يجب أن يكون رقماً صحيحاً',
            'coins_number.min' => '⚠️ السعر لا يمكن أن يكون أقل من 0',
        ]);

        $item = AvatarItems::findOrFail($request->id);
        $item->coins_number = (int)$request->coins_number;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => '✅ تم تحديث سعر العنصر بنجاح',
            'coins_number' => $item->coins_number,
        ]);
    }
}
