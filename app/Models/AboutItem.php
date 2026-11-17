<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutItem extends Model
{
    protected $fillable = [
        'page',
        'group',
        'title',
        'subtitle',
        'body',
        'icon',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ];

    public function aboutPage()
    {
        return $this->belongsTo(AboutPage::class, 'page', 'slug');
    }

    public function groupConfig(): array
    {
        return AboutPage::PAGES[$this->page]['groups'][$this->group];
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image && file_exists(public_path($this->image)) ? asset($this->image) : null;
    }
}
