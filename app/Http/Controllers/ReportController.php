<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Game;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\Category;
use App\Models\GameType;
use App\Models\Question;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\TitlePosition;
use App\Models\OnlineGameInfo;
use App\Models\OnlineGameUser;
use App\Models\OnlineGameCategory;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{

    public function ReportView(){
        return view('admin.report.report_view');
    }




   public function SearchByDate(Request $request)
{
    // ✅ التحقق من صحة الإدخال
    $request->validate([
        'date' => 'required|date',
    ], [
        'date.required' => 'يجب إدخال التاريخ.',
        'date.date'     => 'يجب إدخال تاريخ صحيح.',
    ]);

    // ✅ صياغة التاريخ
    $date = new DateTime($request->date);
    $formatDate = $date->format('Y-m-d'); // شكل YYYY-MM-DD

    // ✅ حساب القيم (counts) فقط
    $users         = User::where('role', '!=', 'admin')->whereDate('created_at', $formatDate)->count();
    $category      = Category::whereDate('created_at', $formatDate)->count();
    $games         = Game::whereDate('created_at', $formatDate)->count();
    $questions     = Question::whereDate('created_at', $formatDate)->count();
    $gameType      = GameType::whereDate('created_at', $formatDate)->count();
    $mainCategory  = MainCategory::whereDate('created_at', $formatDate)->count();
    $sponsor       = Sponsor::whereDate('created_at', $formatDate)->count();
    $titlePosition = TitlePosition::whereDate('created_at', $formatDate)->count();

    // ✅ إرجاع نفس الـ Blade المستخدم للرسم البياني
    return view('admin.report.report_by_date', compact(
        'users',
        'formatDate',
        'category',
        'games',
        'questions',
        'gameType',
        'mainCategory',
        'sponsor',
        'titlePosition'
    ));
}


    // public function SearchByMonth(Request $request){

    //     // $month = $request->month;
    //     // $year = $request->year_name;

    //     // $orders = Order::where('order_month',$month)->where('order_year',$year)->latest()->get();
    //     // return view('backend.report.report_by_month',compact('orders','month','year'));

    // }// End Method


   public function SearchByMonth(Request $request)
{
    // ✅ التحقق من صحة الإدخال
    $request->validate([
        'year_name' => 'required|not_in:non',
        'month'     => 'required|not_in:non',
    ], [
        'month.required'   => 'يجب اختيار الشهر.',
        'month.not_in'     => 'يجب اختيار الشهر.',
        'year_name.not_in' => 'يجب اختيار السنة.',
        'month.min'        => 'الشهر يجب أن يكون بين 1 و 12.',
        'month.max'        => 'الشهر يجب أن يكون بين 1 و 12.',
        'year_name.required' => 'يجب اختيار السنة.',
        'year_name.numeric'  => 'يجب أن تكون السنة رقمية.',
        'year_name.min'      => 'السنة يجب أن تكون بعد 2000.',
        'year_name.max'      => 'السنة لا يمكن أن تتجاوز السنة الحالية.',
    ]);

    // ✅ استخراج الشهر والسنة
    $month = date('m', strtotime($request->month)); // يحوّل إلى رقم شهر 01-12
    $year  = $request->year_name;

    // ✅ صيغة التاريخ للعرض فقط
    $formatDate = $year . '/' . $month;

    // ✅ حساب القيم (counts فقط)
    $users         = User::where('role', '!=', 'admin')->whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $category      = Category::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $games         = Game::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $questions     = Question::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $gameType      = GameType::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $mainCategory  = MainCategory::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $sponsor       = Sponsor::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
    $titlePosition = TitlePosition::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();

    // ✅ إرجاع نفس الـ view المستخدم مع SearchByYear
    return view('admin.report.report_by_date', compact(
        'users',
        'formatDate',
        'category',
        'games',
        'questions',
        'gameType',
        'mainCategory',
        'sponsor',
        'titlePosition'
    ));
}



        public function SearchByYear(Request $request)
{
    // ✅ التحقق من صحة الإدخال
    $request->validate([
        'years' => 'required|not_in:non',
    ], [
        'years.not_in'   => 'يجب اختيار السنة.',
        'years.required' => 'يجب اختيار السنة.',
        'years.numeric'  => 'يجب أن تكون السنة رقمية.',
        'years.min'      => 'السنة يجب أن تكون بعد 2000.',
        'years.max'      => 'السنة لا يمكن أن تتجاوز السنة الحالية.',
    ]);

    $year = $request->years;
    $formatDate = $year; // مجرد عرض في الواجهة

    // ✅ نحسب الأعداد فقط بدون تحميل كل البيانات
    $users         = User::where('role', '!=', 'admin')->whereYear('created_at', $year)->count();
    $category      = Category::whereYear('created_at', $year)->count();
    $games         = Game::whereYear('created_at', $year)->count();
    $questions     = Question::whereYear('created_at', $year)->count();
    $gameType      = GameType::whereYear('created_at', $year)->count();
    $mainCategory  = MainCategory::whereYear('created_at', $year)->count();
    $sponsor       = Sponsor::whereYear('created_at', $year)->count();
    $titlePosition = TitlePosition::whereYear('created_at', $year)->count();

    // ✅ نرجع الأعداد فقط للعرض
    return view('admin.report.report_by_date', compact(
        'users',
        'formatDate',
        'category',
        'games',
        'questions',
        'gameType',
        'mainCategory',
        'sponsor',
        'titlePosition'
    ));
}




    public function OrderByUser(){
        // $users = User::where('role','user')->latest()->get();
        // return view('backend.report.report_by_user',compact('users'));
    }// End Method

    public function SearchByUser(Request $request){
        // $user_id = $request->user;
        // $users = User::find($user_id);
        // $orders = Order::where('user_id',$user_id)->latest()->get();
        // return view('backend.report.report_by_user_show',compact('orders','users'));
    }// End Method

    /**
     * تقرير تدقيق ومراقبة مباريات الميدان أونلاين
     */
    public function OnlineGamesAuditReport(Request $request)
    {
        $search = $request->input('search');
        $anomalyOnly = $request->input('anomaly_only');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = OnlineGameInfo::with(['user', 'categories.category', 'onlineGameUsers.user'])
            ->latest('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('game_session_name', 'like', "%{$search}%")
                  ->orWhere('online_game_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('f_name', 'like', "%{$search}%")
                         ->orWhere('l_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $allGames = $query->paginate(25)->withQueryString();

        // إلحاق إحصائيات الأسئلة والفئات وفحص الأنماط غير الطبيعية (Anomalies) لكل لعبة
        foreach ($allGames as $game) {
            $game->questions_count = DB::table('game_session_question_onlines')
                ->where('session_id', $game->game_session_name)
                ->count();

            $game->categories_count = $game->categories->count();

            $game->has_self_join = $game->onlineGameUsers->contains(function ($u) use ($game) {
                return $u->user_id == $game->created_user_id && $u->role !== 'admin';
            });

            $game->is_anomaly = ($game->questions_count > 36) || ($game->categories_count > 6) || $game->has_self_join;
        }

        // إذا تم اختيار فلتر الألعاب غير الطبيعية فقط
        if ($anomalyOnly == '1') {
            $filteredItems = $allGames->getCollection()->filter(function ($game) {
                return $game->is_anomaly;
            });
            $allGames->setCollection($filteredItems);
        }

        // إحصائيات عامة
        $totalOnlineGames = OnlineGameInfo::count();
        $totalOnlineQuestionsInDb = DB::table('game_session_question_onlines')->count();

        return view('admin.report.online_games_audit', compact(
            'allGames',
            'search',
            'anomalyOnly',
            'dateFrom',
            'dateTo',
            'totalOnlineGames',
            'totalOnlineQuestionsInDb'
        ));
    }

    /**
     * تفاصيل جلسة لعبة أونلاين محددة والأسئلة والفئات المرتبطة بها
     */
    public function OnlineGameDetailsReport($id)
    {
        $game = OnlineGameInfo::with(['user', 'categories.category', 'onlineGameUsers.user'])
            ->where('id', $id)
            ->orWhere('game_session_name', $id)
            ->firstOrFail();

        $questions = DB::table('game_session_question_onlines as gsq')
            ->join('questions as q', 'gsq.question_id', '=', 'q.id')
            ->join('categories as c', 'gsq.category_id', '=', 'c.id')
            ->where('gsq.session_id', $game->game_session_name)
            ->orderBy('gsq.id')
            ->select(
                'q.*',
                'gsq.category_id',
                'gsq.question_order',
                'gsq.created_at as question_attached_at',
                'c.category_name',
                'c.category_name_en',
                'c.category_photo'
            )
            ->get();

        $categories = $game->categories;
        $players = $game->onlineGameUsers;

        return view('admin.report.online_game_details', compact(
            'game',
            'questions',
            'categories',
            'players'
        ));
    }
}
