<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvatarItems extends Model
{
        protected $guarded = [];

        public function category()
        {
            return $this->belongsTo(AvatarCategory::class, 'category_id');
        }

        public function coin()
        {
            return $this->belongsTo(GameCoin::class, 'game_coin_id');
        }

        public function users()
        {
            return $this->belongsToMany(User::class, 'user_avatar_items', 'avatar_item_id', 'user_id')->withTimestamps();
        }
}
