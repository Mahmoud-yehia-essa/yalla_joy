<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnimationUserLibrary extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'animation_user_libraries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'animation_feedback_id',
    ];

    /**
     * Get the user that owns the animation library entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the animation feedback associated with the library entry.
     */
    public function animationFeedback(): BelongsTo
    {
        return $this->belongsTo(AnimationFeedback::class, 'animation_feedback_id');
    }
}
