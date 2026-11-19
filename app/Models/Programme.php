<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    public const LEVELS = ['Degree', 'Diploma', 'Certificate'];

    protected $fillable = [
        'school_id',
        'name',
        'level',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
