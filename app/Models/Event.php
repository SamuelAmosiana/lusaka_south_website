<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'date', 'category', 'author', 'image',
        'short_description', 'full_description', 'featured',
    ];

    protected $casts = [
        'date' => 'date',
        'featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function images()
    {
        return $this->hasMany(EventImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return null;
    }

    public function getFormattedFullDescriptionAttribute(): string
    {
        $raw = trim($this->full_description ?? '');
        if ($raw === '') {
            return '';
        }

        // Normalize line breaks
        $clean = str_replace(["\r\n", "\r"], "\n", $raw);

        // Convert multiple consecutive <br> tags into double newlines (paragraph breaks)
        $clean = preg_replace('/(?:[ \t]*<br\s*\/?>[ \t]*\n*){2,}/i', "\n\n", $clean);

        // Clean up <br> right after closing block tags
        $clean = preg_replace('/(<\/(?:p|h[1-6]|ul|ol|li|blockquote)>)[ \t]*(?:<br\s*\/?>[ \t]*)+/i', '$1', $clean);

        // Split by double newlines into blocks
        $chunks = preg_split('/\n{2,}/', $clean);
        $formatted = [];

        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            if ($chunk === '') continue;

            // Check if chunk is already wrapped in a block tag
            if (preg_match('/^<(?:p|h[1-6]|ul|ol|blockquote|hr|div|table)[^>]*>/i', $chunk)) {
                // Strip trailing <br> only
                $chunk = preg_replace('/(?:<br\s*\/?>[ \t]*)+$/i', '', $chunk);
                // Ensure closing tag if missing on <p>
                if (preg_match('/^<p[^>]*>/i', $chunk) && !preg_match('/<\/p>$/i', $chunk)) {
                    $chunk .= '</p>';
                }
                $formatted[] = $chunk;
            } else {
                // Raw chunk without opening block tag
                // Strip any trailing orphaned </p> or <br>
                $chunk = preg_replace('/(?:<\/(?:p|div)>|<br\s*\/?>[ \t]*)+$/i', '', $chunk);
                // Strip any leading orphaned </p>
                $chunk = preg_replace('/^<\/(?:p|div)>\s*/i', '', $chunk);
                $chunk = trim($chunk);
                if ($chunk !== '') {
                    $formatted[] = '<p>' . nl2br($chunk) . '</p>';
                }
            }
        }

        return implode("\n\n", $formatted);
    }
}
