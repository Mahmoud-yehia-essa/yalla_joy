<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameInstruction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'order_by' => 'integer',
    ];

    /**
     * Scope for active instructions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for specific game target ('session' or 'field')
     */
    public function scopeForGame($query, $target)
    {
        if (!empty($target)) {
            return $query->where('game_target', $target);
        }
        return $query;
    }
}
