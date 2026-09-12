<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    use HasFactory;

    protected $table = 'terms_and_conditions';

    protected $fillable = [
        'title',
        'title_en',
        'content',
        'content_en',
        'order_by',
        'status',
    ];

    /**
     * Scope for active terms only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
