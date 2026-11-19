<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutItem extends Model
{
    /**
     * Fields each item type uses: field => [label, required]. The admin form and
     * validation are built from this, so a new type only needs an entry here.
     */
    public const TYPES = [
        'card'    => ['title' => ['Title', true], 'icon' => ['Icon', true], 'body' => ['Text', true]],
        'person'  => ['title' => ['Name', true], 'subtitle' => ['Role / Position', true], 'body' => ['Short Description', false], 'image' => ['Photo', false]],
        'link'    => ['title' => ['Resource Name', true], 'icon' => ['Icon', true], 'url' => ['Website Link', true], 'subtitle' => ['Button Text', false], 'body' => ['Text', true]],
        'contact' => ['title' => ['Department / Office', true], 'icon' => ['Icon', true], 'phone' => ['Phone', false], 'email' => ['Email', false], 'body' => ['Text', false]],
        'value'   => ['title' => ['Value Name', true], 'icon' => ['Icon', true], 'body' => ['Text', true], 'image' => ['Background Picture', false]],
        'stat'    => ['title' => ['Figure (e.g. 350+)', true], 'subtitle' => ['Label (e.g. Slots / Intake)', true]],
        'award'   => ['title' => ['Award Name', true], 'value' => ['Discount (e.g. 70%)', true], 'subtitle' => ['Tag (e.g. Rural & Disabled)', false], 'body' => ['Short Description', true]],
        'slide'   => ['title' => ['Slide Title', true], 'body' => ['Short Description', false], 'image' => ['Picture', true]],
        'photo'   => ['title' => ['Caption', false], 'image' => ['Photo', true]],
        'info'    => ['title' => ['Label (e.g. Phone)', true], 'icon' => ['Icon', true], 'body' => ['Details (one per line)', true]],
    ];

    /** What to call one item of each type in the admin panel. */
    public const NOUNS = [
        'card' => 'Card', 'person' => 'Person', 'link' => 'Resource', 'contact' => 'Contact Card', 'value' => 'Value Card',
        'stat' => 'Figure', 'award' => 'Award Card', 'slide' => 'Slide', 'photo' => 'Photo', 'info' => 'Contact Detail',
    ];

    protected $fillable = [
        'page',
        'group',
        'title',
        'subtitle',
        'value',
        'body',
        'icon',
        'image',
        'url',
        'phone',
        'email',
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

    public function type(): string
    {
        return $this->groupConfig()['type'];
    }

    /** The fields this item's type uses, minus a description on groups that hide it. */
    public function fieldSpec(): array
    {
        $spec = self::TYPES[$this->type()];
        if (($this->groupConfig()['describe'] ?? true) === false) {
            unset($spec['body']);
        }

        return $spec;
    }

    public function noun(): string
    {
        return self::NOUNS[$this->type()] ?? 'Item';
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image && file_exists(public_path($this->image)) ? asset($this->image) : null;
    }
}
