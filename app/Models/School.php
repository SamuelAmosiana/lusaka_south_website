<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    /** Schools with at least this many programmes span the full page width. */
    public const WIDE_FROM = 8;

    protected $fillable = [
        'name',
        'icon',
        'intro',
        'apply_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function programmes()
    {
        return $this->hasMany(Programme::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activeProgrammes()
    {
        return $this->programmes()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Choices for the Apply button, taken from config('site.apply'). */
    public static function applyOptions(): array
    {
        $labels = [
            'undergraduate'      => 'Degree & Diploma application',
            'short_courses'      => 'Certificate / Trade & Skills application',
            'corporate_training' => 'Corporate Training application',
            'scholarships'       => 'Scholarship application',
        ];

        return array_intersect_key($labels, config('site.apply'));
    }

    public function getApplyUrlAttribute(): string
    {
        return config('site.apply.' . $this->apply_link) ?? config('site.apply.undergraduate');
    }
}
