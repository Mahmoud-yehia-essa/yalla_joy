<?php

namespace App\Models;

use App\Models\Levels;
use Illuminate\Database\Eloquent\Model;

class GameHelper extends Model
{
        protected $guarded = [];


          public function levels()
    {
                return $this->belongsTo(Levels::class, 'level_id');

    }


    //  public function levels()
    // {
    //     return $this->belongsTo(Levels::class);
    // }


}
