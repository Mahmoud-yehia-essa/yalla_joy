<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\Category;
use App\Models\GameType;
use App\Models\Question;
use App\Models\GameElement;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\TitlePosition;

class DashboardController extends Controller
{

    public function showDashboard()
    {


    $users = User::where('role', '!=', 'admin')->latest()->get();

    $category = Category::latest()->get();

    $games = Game::latest()->get();
    $questions = Question::latest()->get();

        $gameType = GameType::latest()->get();
        $mainCategory = MainCategory::latest()->get();

                $sponsor = Sponsor::latest()->get();
                $titlePosition = TitlePosition::latest()->get();




    return view('admin.index',compact('users','category','games','questions','gameType','mainCategory','sponsor','titlePosition'));

        // return view('admin.index');
    }
}
