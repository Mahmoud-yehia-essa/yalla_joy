<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameBundleItem extends Model
{
            protected $guarded = [];

    public function item()
{
    return $this->belongsTo(GameItem::class, 'game_item_id', 'id');
}

}
