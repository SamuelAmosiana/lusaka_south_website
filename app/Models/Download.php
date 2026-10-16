<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    public const CATEGORIES = [
        'Institutional Documents',
        'Admissions',
        'Academic Resources',
        'Fees & Financial Aid',
        'Student Resources',
        'Additional Resources',
    ];

    protected $fillable = [
        'title', 'description', 'filename', 'category', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
