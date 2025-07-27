<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function showDashboard()
    {


    $users = User::where('role', '!=', 'admin')->latest()->get();

    $category = Category::latest()->get();

    $games = Game::latest()->get();

    $questions = Question::latest()->get();

    return view('admin.index',compact('users','category','games','questions'));

        // return view('admin.index');
    }
}
