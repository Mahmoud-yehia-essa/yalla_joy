<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_send_invitataion',
        'user_id_resive_invitaion',
        'date',
        'invitation_statue',
        'game_code',
        'user_id_winner', // تمت الإضافة
        'score_get',      // تمت الإضافة
        'join_start_at', // تمت الإضافة
        'join_end_at',   // تمت الإضافة
    ];

    protected $casts = [
        'date' => 'datetime',
        'score_get' => 'integer', // لضمان تحويل النتيجة إلى رقم صحيح برمجياً
        'join_start_at' => 'datetime', // تحويل الحقل إلى Carbon Object
        'join_end_at' => 'datetime',   // تحويل الحقل إلى Carbon Object
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_send_invitataion');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_resive_invitaion');
    }

    /**
     * علاقة: المستخدم الفائز بالتحدي
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_winner');
    }
}
