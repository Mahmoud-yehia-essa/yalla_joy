<?php

namespace App\Models;

use App\Models\OnlineGameCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OnlineGameInfo extends Model
{
    //
    protected $guarded = [];

public function user()
{
    return $this->belongsTo(User::class, 'created_user_id');
}

public function categories()
{
    return $this->hasMany(
        OnlineGameCategory::class,
        'online_game_info_id'
    );
}


}
