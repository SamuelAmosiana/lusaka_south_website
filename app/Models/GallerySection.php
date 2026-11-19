<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GallerySection extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activePhotos()
    {
        return $this->photos()->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
