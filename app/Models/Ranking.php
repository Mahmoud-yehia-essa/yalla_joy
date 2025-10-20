<?php

namespace App\Models;

use App\Models\Levels;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
      protected $guarded = [];

    public function level()
    {
        return $this->belongsTo(Levels::class, 'level_id');
    }
}
