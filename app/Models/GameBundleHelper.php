<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameBundleHelper extends Model
{
            protected $guarded = [];

            public function helper()
{
    return $this->belongsTo(GameHelper::class, 'game_helper_id', 'id');
}
}
