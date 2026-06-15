<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimationFeedback extends Model
{
    protected $table = 'animation_feedbacks';
    protected $guarded = [];

    public function rankingNew()
    {
        return $this->belongsTo(RankingNew::class, 'ranking_new_id');
    }

    public function coin()
    {
        return $this->belongsTo(GameCoin::class, 'coin_id');
    }

    /**
     * Get the user library entries for the animation feedback.
     */
    public function animationUserLibraries()
    {
        return $this->hasMany(AnimationUserLibrary::class, 'animation_feedback_id');
    }
}
