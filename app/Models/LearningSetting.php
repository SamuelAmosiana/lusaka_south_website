<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_title',
        'section_subtitle',
        'video_path',
        'video_title',
        'video_description',
        'poster_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Helper to get or create the singleton settings record.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'section_title'     => 'Online and On-Campus Learning',
            'section_subtitle'  => 'Flexible study modes designed to empower your educational journey wherever you are.',
            'video_path'        => 'images/campus_video.mp4',
            'video_title'       => 'Campus Overview',
            'video_description' => 'Experience world-class learning spaces, digital infrastructure, and vibrant student community at Foxdale, Lusaka.',
            'poster_image'      => 'images/school2.jpeg',
            'is_active'         => true,
        ]);
    }
}
