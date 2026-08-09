<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GameType;
use App\Models\Question;
use App\Models\MainCategory;

use Illuminate\Http\Request;

use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver; // Use GD driver (or use Intervention\Image\Drivers\Imagick\Driver for Imagick)
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    //




    public function fillterCategorySearch(Request $request)
    {


 $query = Category::query();

    // فلترة game_type_id
    if ($request->game_type_id !== "non") {
        $query->where('game_type_id', $request->game_type_id);
    }

    // فلترة main_category_id
    if ($request->main_category_id !== "non") {
        $query->where('main_category_id', $request->main_category_id);
    }

    // فلترة category_id (id)
    if ($request->category_id !== "non") {
        $query->where('id', $request->category_id);
    }

    // البحث بالكتابة
    // if ($request->search && $request->search !== "") {
    //     $query->where(function($q) use ($request) {
    //         $q->where('category_name', 'LIKE', "%{$request->search}%")
    //           ->orWhere('category_name_en', 'LIKE', "%{$request->search}%")
    //           ->orWhere('category_description', 'LIKE', "%{$request->search}%");
    //     });
    // }

    $category = $query->get();

            return view('admin.category.all_category_filter',compact('category'));


    // return $results;

    }

       public function fillterCategory()
    {
        $category = Category::orderByRaw('order_by IS NULL ASC')->orderBy('order_by', 'asc')->orderBy('id', 'desc')->get();
        $gameType = GameType::latest()->get();

        return view('admin.category.filter_category',compact('category','gameType'));
    }


    public function category()
    {
        $category = Category::orderByRaw('order_by IS NULL ASC')->orderBy('order_by', 'asc')->orderBy('id', 'desc')->get();
        return view('admin.category.all_category',compact('category'));
    }

    public function addCategory()
    {
        $gameType = GameType::latest()->get();

        return view('admin.category.add_category',compact('gameType'));
    }

/// For json ajax getMainCategory
    public function getMainCategories($game_type_id)
{
    $mainCategories = \App\Models\MainCategory::where('game_type_id', $game_type_id)->get();

    return response()->json($mainCategories);
}

    public function getSubCategories($main_category_id)
{
    $subCategories = \App\Models\Category::where('main_category_id', $main_category_id)->get();

    return response()->json($subCategories);
}

    public function storeCategory(Request $request)
    {

        $request->validate([
            'main_category_id' => 'required|not_in:non',
            'game_type_id' => 'required|not_in:non',
            'category_name' => 'required|string|max:255',
            'category_name_en' => 'required|string|max:255',
            'category_description' => 'nullable|string',
            'category_description_en' => 'nullable|string',
            'category_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order_by' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('categories', 'order_by')->where(function ($query) use ($request) {
                    return $query->where('main_category_id', $request->main_category_id);
                }),
            ],
        ], [
            'category_name.required' => '⚠️ الرجاء اضافة اسم الفئة',
            'category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',
            'category_name_en.required' => '⚠️ الرجاء اضافة اسم الفئة بالانجليزية',
            'category_name_en.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name_en.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',
            'category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بالانجليزية بشكل صحيح ',
            'category_photo.required' => '⚠️ الرجاء اضافة صورة للفئة',
            'category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
            'game_type_id.required' => '⚠️ الرجاء اختيار نوع اللعبة.',
            'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',
            'main_category_id.required' => '⚠️ الرجاء اختيار الفئة الرئيسية.',
            'main_category_id.not_in' => '⚠️ الرجاء اختيار الفئة الرئيسية.',
            'order_by.integer' => '⚠️ ترتيب الفئة يجب ان يكون رقماً صحيحاً',
            'order_by.min' => '⚠️ ترتيب الفئة يجب ان يكون أكبر من 0',
            'order_by.unique' => '⚠️ رقم الترتيب مكرر بالفعل لهذه الفئة الرئيسية، يرجى اختيار رقم ترتيب آخر.',
        ]);

        if ($request->hasFile('category_photo')) {
            $image = $request->file('category_photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/category/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/category/' . $name_gen;
        }

        Category::create([
            'game_type_id' => $request->game_type_id,
            'main_category_id' => $request->main_category_id,
            'category_name' => $request->category_name,
            'category_name_en' => $request->category_name_en,
            'category_description' => $request->category_description,
            'category_description_en' => $request->category_description_en,
            'category_photo' => $save_url ?? null,
            'special' => $request->special,
            'order_by' => $request->order_by,
            'user_id' => Auth::user()->id,
        ]);

        $notification = array(
            'message' => 'تم اضافة الفئة ',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }


    public function editCategort($id){


        $category = Category::findOrFail($id);
        $gameType = GameType::latest()->get();

            $mainCategories = MainCategory::latest()->get();

 $mainCategories = MainCategory::where('game_type_id', $category->game_type_id)
                                  ->latest()
                                  ->get();

        return view('admin.category.edit_category',compact('category','gameType','mainCategories'));
    }// End Method





    public function editCategortStore(Request $request){
        $cate_id = $request->id;

        $request->validate([
            'main_category_id' => 'required|not_in:non',
            'game_type_id' => 'required|not_in:non',
            'category_name' => 'required|string|max:255',
            'category_name_en' => 'required|string|max:255',
            'category_description' => 'nullable|string',
            'category_description_en' => 'nullable|string',
            'category_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'order_by' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('categories', 'order_by')->ignore($cate_id)->where(function ($query) use ($request) {
                    return $query->where('main_category_id', $request->main_category_id);
                }),
            ],
        ], [
            'category_name.required' => '⚠️ الرجاء اضافة اسم الفئة',
            'category_name.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',
            'category_name_en.required' => '⚠️  الرجاء اضافة اسم الفئة بالانجليزية',
            'category_name_en.string' => '⚠️ الرجاء التأكد من كتابة الفئة بشكل صحيح',
            'category_name_en.max' => '⚠️ الرجاء التأكد من عدد احرف الفئة لا يتجاوز 255 حرف',
            'category_description.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'category_description_en.string' => '⚠️ الرجاء التأكد من كتابة الوصف بشكل صحيح',
            'category_photo.image' => '⚠️ تأكد من اضافة صورة',
            'category_photo.mimes' => '⚠️ الصورة يجب ان تكون jpeg, png, jpg, or gif ',
            'category_photo.max' => '⚠️  2MB حجم الصورة يجب الا يتعدى',
            'game_type_id.not_in' => '⚠️ الرجاء اختيار نوع اللعبة.',
            'main_category_id.not_in' => '⚠️ الرجاء اختيار الفئة الرئيسية.',
            'order_by.integer' => '⚠️ ترتيب الفئة يجب ان يكون رقماً صحيحاً',
            'order_by.min' => '⚠️ ترتيب الفئة يجب ان يكون أكبر من 0',
            'order_by.unique' => '⚠️ رقم الترتيب مكرر بالفعل لهذه الفئة الرئيسية، يرجى اختيار رقم ترتيب آخر.',
        ]);

        $old_img = $request->old_image;
        if ($request->file('category_photo')) {
            $image = $request->file('category_photo');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            $path = public_path('upload/category/');
            $imageManager = new ImageManager(new Driver());
            $imageResized = $imageManager->read($image);
            $imageResized->save($path . $name_gen);

            $save_url = 'upload/category/' . $name_gen;

            if (file_exists($old_img)) {
                unlink($old_img);
            }
            Category::findOrFail($cate_id)->update([
                'game_type_id' => $request->game_type_id,
                'main_category_id' => $request->main_category_id,
                'category_name' => $request->category_name,
                'category_name_en' => $request->category_name_en,
                'category_description_en' => $request->category_description_en,
                'category_photo' => $save_url,
                'special'  => $request->special,
                'order_by' => $request->order_by,
            ]);
        } else {
            Category::findOrFail($cate_id)->update([
                'game_type_id' => $request->game_type_id,
                'main_category_id' => $request->main_category_id,
                'category_name' => $request->category_name,
                'category_name_en' => $request->category_name_en,
                'category_description_en' => $request->category_description_en,
                'category_description' => $request->category_description,
                'special'  => $request->special,
                'order_by' => $request->order_by,
            ]);
        }

        $notification = array(
            'message' => 'تم تعديل الفئة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);
    }

    public function updateCategoryOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:categories,id',
            'order_by' => 'nullable|integer|min:1',
            'confirm_swap' => 'nullable|boolean',
        ]);

        $id = $request->id;
        $orderBy = $request->order_by;
        $confirmSwap = $request->boolean('confirm_swap', false);

        $category = Category::findOrFail($id);
        $oldOrder = $category->order_by;

        if (!empty($orderBy)) {
            $conflicting = Category::where('main_category_id', $category->main_category_id)
                ->where('order_by', $orderBy)
                ->where('id', '!=', $id)
                ->first();
            if ($conflicting) {
                if (!$confirmSwap) {
                    return response()->json([
                        'status' => false,
                        'is_duplicate' => true,
                        'conflicting_id' => $conflicting->id,
                        'conflicting_name' => $conflicting->category_name,
                        'message' => 'الفئة ("' . $conflicting->category_name . '") تحمل نفس رقم الترتيب (' . $orderBy . '). هل تريد استبدال المراكز بينهما؟'
                    ]);
                } else {
                    // Perform swap between categories
                    $conflicting->order_by = $oldOrder;
                    $conflicting->save();

                    $category->order_by = $orderBy;
                    $category->save();

                    return response()->json([
                        'status' => true,
                        'swapped' => true,
                        'swapped_id' => $conflicting->id,
                        'swapped_order' => $oldOrder,
                        'message' => 'تم استبدال المراكز بنجاح بين "' . $category->category_name . '" و "' . $conflicting->category_name . '"'
                    ]);
                }
            }
        }

        $category->order_by = $orderBy;
        $category->save();

        return response()->json([
            'status' => true,
            'swapped' => false,
            'message' => 'تم تحديث الترتيب بنجاح'
        ]);
    }

    public function deleteCategory($id){
        $category = Category::findOrFail($id);

        if ($category->category_photo && file_exists(public_path($category->category_photo))) {
            unlink(public_path($category->category_photo));
        }
        Category::findOrFail($id)->delete();
        $notification = array(
            'message' => 'تم حذف الفئة',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);
    }

    public function categoryInactive($id){
        Category::findOrFail($id)->update(['status' => 'inactive']);
        $notification = array(
            'message' => ' غير مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function categoryActive($id){
        Category::findOrFail($id)->update(['status' => 'active']);
        $notification = array(
            'message' => 'مفعل',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function getCategoryApi(Request $request)
    {
        $game_type_id = $request->game_type_id;
        $main_category_id = $request->main_category_id;
        $userId = $request->user_id ?? ($request->user() ? $request->user()->id : null);

        $categoriesQuery = Category::where('game_type_id', $game_type_id)
            ->where('main_category_id', $main_category_id)
            ->where('status', 'active')
            ->whereHas('questions', function ($q) {
                // عدد الأسئلة لا يقل عن 6
            }, '>=', 6)
            ->orderByRaw('order_by IS NULL ASC')
            ->orderBy('order_by', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($categoriesQuery->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid get categories'
            ], 401);
        }

        $categoryIds = $categoriesQuery->pluck('id')->toArray();

        // 1. حساب إجمالي عدد الأسئلة في كل فئة دفعة واحدة
        $questionsCounts = \Illuminate\Support\Facades\DB::table('questions')
            ->whereIn('category_id', $categoryIds)
            ->select('category_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_count'))
            ->groupBy('category_id')
            ->pluck('total_count', 'category_id')
            ->toArray();

        // 2. حساب الأسئلة الملعوبة بواسطة هذا المستخدم في كل فئة دفعة واحدة من جدول questions_registers
        $playedCounts = [];
        if ($userId && !empty($categoryIds)) {
            $playedCounts = \Illuminate\Support\Facades\DB::table('questions_registers')
                ->join('games', 'games.id', '=', 'questions_registers.game_id')
                ->join('questions', 'questions.id', '=', 'questions_registers.question_id')
                ->where('games.user_id_created', $userId)
                ->whereIn('questions.category_id', $categoryIds)
                ->select('questions.category_id', \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT questions_registers.question_id) as played_count'))
                ->groupBy('questions.category_id')
                ->pluck('played_count', 'category_id')
                ->toArray();
        }

        $category = $categoriesQuery->map(function ($item) use ($playedCounts, $questionsCounts) {
            $item->category_selected = false;

            $totalQuestions = (int) ($questionsCounts[$item->id] ?? 0);
            $playedQuestions = (int) ($playedCounts[$item->id] ?? 0);
            $remainingQuestions = max(0, $totalQuestions - $playedQuestions);
            $repetitionPercentage = $totalQuestions > 0 ? round(($playedQuestions / $totalQuestions) * 100, 2) : 0;
            $remainingGames = (int) floor($remainingQuestions / 6);
            $willRepeatNextGame = $remainingQuestions < 6;

            $item->total_questions_count = $totalQuestions;
            $item->played_questions_count = $playedQuestions;
            $item->remaining_questions_count = $remainingQuestions;
            $item->repetition_percentage = $repetitionPercentage;
            $item->remaining_games_count = $remainingGames;
            $item->will_repeat_next_game = $willRepeatNextGame;

            $item->repetition_stats = [
                'total_questions_count' => $totalQuestions,
                'played_questions_count' => $playedQuestions,
                'remaining_questions_count' => $remainingQuestions,
                'repetition_percentage' => $repetitionPercentage,
                'remaining_games_count' => $remainingGames,
                'will_repeat_next_game' => $willRepeatNextGame,
            ];

            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Category retrieval successful',
            'categories' => $category,
        ], 200);
    }

    public function getCategoryRepetitionStatusApi(Request $request)
    {
        $userId = $request->user_id ?? ($request->user() ? $request->user()->id : null);
        $categoryId = $request->category_id;

        if (!$categoryId) {
            return response()->json([
                'success' => false,
                'message' => 'category_id is required'
            ], 422);
        }

        $totalQuestions = \Illuminate\Support\Facades\DB::table('questions')
            ->where('category_id', $categoryId)
            ->count();

        $playedQuestions = 0;

        if ($userId && $totalQuestions > 0) {
            $playedQuestions = \Illuminate\Support\Facades\DB::table('questions_registers')
                ->join('games', 'games.id', '=', 'questions_registers.game_id')
                ->join('questions', 'questions.id', '=', 'questions_registers.question_id')
                ->where('games.user_id_created', $userId)
                ->where('questions.category_id', $categoryId)
                ->distinct('questions_registers.question_id')
                ->count('questions_registers.question_id');
        }

        $remainingQuestions = max(0, $totalQuestions - $playedQuestions);
        $repetitionPercentage = $totalQuestions > 0 ? round(($playedQuestions / $totalQuestions) * 100, 2) : 0;
        $remainingGames = (int) floor($remainingQuestions / 6);
        $willRepeatNextGame = $remainingQuestions < 6;

        return response()->json([
            'success' => true,
            'category_id' => (int) $categoryId,
            'total_questions_count' => $totalQuestions,
            'played_questions_count' => $playedQuestions,
            'remaining_questions_count' => $remainingQuestions,
            'repetition_percentage' => $repetitionPercentage,
            'remaining_games_count' => $remainingGames,
            'will_repeat_next_game' => $willRepeatNextGame,
        ], 200);
    }
}
