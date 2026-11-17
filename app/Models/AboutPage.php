<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'slug',
        'header_title',
        'header_subtitle',
        'section_title',
        'section_subtitle',
        'extra_title',
        'extra_text',
    ];

    /**
     * The four editable About pages: admin label, public route, the item
     * groups each page shows, and what (if anything) the extra block is.
     * Group types: "card" = icon/title/text, "person" = photo/name/role/text.
     */
    public const PAGES = [
        'overview' => [
            'label'  => 'LSUC Overview',
            'route'  => 'about',
            'icon'   => 'fa-circle-info',
            'groups' => ['cards' => ['label' => 'Information Cards', 'type' => 'card']],
            'extra'  => null,
        ],
        'team' => [
            'label'  => 'Our Team',
            'route'  => 'about.team',
            'icon'   => 'fa-users',
            'groups' => [
                'leadership' => ['label' => 'Leadership Team', 'type' => 'person', 'describe' => true],
                'hods'       => ['label' => 'Heads of Departments', 'type' => 'person', 'describe' => false],
            ],
            'extra'  => ['label' => 'Heads of Departments Heading', 'title' => 'Heading', 'text' => 'Subtitle'],
        ],
        'partnerships' => [
            'label'  => 'Partnerships',
            'route'  => 'about.partnerships',
            'icon'   => 'fa-handshake',
            'groups' => ['cards' => ['label' => 'Partnership Cards', 'type' => 'card']],
            'extra'  => null,
        ],
        'accreditation' => [
            'label'  => 'Accreditation',
            'route'  => 'about.accreditation',
            'icon'   => 'fa-certificate',
            'groups' => ['cards' => ['label' => 'Accreditation Cards', 'type' => 'card']],
            'extra'  => ['label' => 'Quality Assurance Box', 'title' => 'Box Heading', 'text' => 'Box Text'],
        ],
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function items()
    {
        return $this->hasMany(AboutItem::class, 'page', 'slug')->orderBy('sort_order')->orderBy('id');
    }

    public function config(): array
    {
        return self::PAGES[$this->slug];
    }

    /** Active items for one group, for the public pages. */
    public function activeItems(string $group)
    {
        return $this->items->where('group', $group)->where('is_active', true)->values();
    }
}
