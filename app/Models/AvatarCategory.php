<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvatarCategory extends Model
{
        protected $guarded = [];

        public function items()
        {
            return $this->hasMany(AvatarItems::class, 'category_id');
        }
}
