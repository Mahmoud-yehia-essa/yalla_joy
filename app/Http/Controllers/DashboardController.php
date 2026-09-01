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
use App\Models\PaymentTransaction;

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

        // 🏆 Top 10 users with highest online points
        $topOnlineUsers = User::where('role', '!=', 'admin')
            ->orderByDesc('online_points')
            ->take(10)
            ->get();

        // 💰 Financial stats
        $financialStats = [
            'total_revenue'         => PaymentTransaction::paid()->sum('amount'),
            'success_count'         => PaymentTransaction::paid()->count(),
            'failed_count'          => PaymentTransaction::failed()->count(),
            'total_games_purchased' => PaymentTransaction::paid()->sum('games_count'),
            'total_coins_purchased' => PaymentTransaction::paid()->sum('coins_count'),
        ];

        return view('admin.index', compact(
            'users',
            'category',
            'games',
            'questions',
            'gameType',
            'mainCategory',
            'sponsor',
            'titlePosition',
            'topOnlineUsers',
            'financialStats'
        ));
    }
}
