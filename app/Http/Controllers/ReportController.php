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


}
