<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'header_title',
        'header_subtitle',
        'section_title',
        'section_subtitle',
        'catalog_pdf',
    ];

    /** The single settings row for the Schools page. */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'header_title'  => 'Schools & Programmes',
            'section_title' => 'Our Schools',
        ]);
    }

    public function getCatalogUrlAttribute(): ?string
    {
        return $this->catalog_pdf && file_exists(public_path($this->catalog_pdf)) ? asset($this->catalog_pdf) : null;
    }
}
