<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proverb extends Model
{
    protected $fillable = ['title', 'title_en', 'type', 'ranking_new_id', 'audio_ar', 'audio_en'];

    public function rankingNew()
    {
        return $this->belongsTo(RankingNew::class, 'ranking_new_id');
    }
}
