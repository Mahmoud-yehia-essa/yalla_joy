<?php

namespace App\Models;

use App\Models\User;
use App\Models\questionsByUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGame extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'status',
    //     'name',
    //     'des',
    //     'hint',
    //     'tags',
    //     'rate',
    //     'privacy',
    //     'photo',
    // ];

        protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(questionsByUsers::class, 'user_game_id');
    }
}
