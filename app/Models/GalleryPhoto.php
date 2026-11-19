<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    /** Photos uploaded through the admin panel live here; only these are ever deleted from disk. */
    public const UPLOAD_DIR = 'images/gallery';

    protected $fillable = [
        'gallery_section_id',
        'image',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(GallerySection::class, 'gallery_section_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image && file_exists(public_path($this->image)) ? asset($this->image) : null;
    }

    public function deleteUploadedFile(): void
    {
        if (str_starts_with($this->image, self::UPLOAD_DIR . '/') && file_exists(public_path($this->image))) {
            @unlink(public_path($this->image));
        }
    }
}
