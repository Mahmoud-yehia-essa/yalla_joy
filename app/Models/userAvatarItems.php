<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userAvatarItems extends Model
{
    protected $table = 'user_avatar_items';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function avatarItem()
    {
        return $this->belongsTo(AvatarItems::class, 'avatar_item_id');
    }
}
