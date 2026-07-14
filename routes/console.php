<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\OnlineGameInfo;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    OnlineGameInfo::where('game_online_state', 'waiting')
        ->where('created_at', '<=', now()->subMinutes(5))
        ->update(['game_online_state' => 'finished']);
})->everyMinute();
