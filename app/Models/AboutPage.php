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
     * The editable content pages: admin label, public route, the item groups
     * each page shows, and what (if anything) the extra block is. Pages with
     * 'about' => true are listed under About Pages; the others get their own
     * admin menu link. Group types: "card" = icon/title/text,
     * "person" = photo/name/role/text, "link" = card with a button to a website,
     * "contact" = card with phone/email.
     */
    public const PAGES = [
        'overview' => [
            'about'  => true,
            'label'  => 'LSUC Overview',
            'route'  => 'about',
            'icon'   => 'fa-circle-info',
            'groups' => ['cards' => ['label' => 'Information Cards', 'type' => 'card']],
            'extra'  => null,
        ],
        'team' => [
            'about'  => true,
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
            'about'  => true,
            'label'  => 'Partnerships',
            'route'  => 'about.partnerships',
            'icon'   => 'fa-handshake',
            'groups' => ['cards' => ['label' => 'Partnership Cards', 'type' => 'card']],
            'extra'  => null,
        ],
        'accreditation' => [
            'about'  => true,
            'label'  => 'Accreditation',
            'route'  => 'about.accreditation',
            'icon'   => 'fa-certificate',
            'groups' => ['cards' => ['label' => 'Accreditation Cards', 'type' => 'card']],
            'extra'  => ['label' => 'Quality Assurance Box', 'title' => 'Box Heading', 'text' => 'Box Text'],
        ],
        'e-resources' => [
            'about'  => false,
            'label'  => 'E-Resources',
            'route'  => 'e-resources',
            'icon'   => 'fa-book-bookmark',
            'groups' => ['cards' => ['label' => 'Resource Links', 'type' => 'link']],
            'extra'  => null,
        ],
        'contact' => [
            'about'  => false,
            'label'  => 'Contact Page',
            'route'  => 'contact',
            'icon'   => 'fa-address-book',
            'groups' => ['cards' => ['label' => 'Department Contact Cards', 'type' => 'contact']],
            'extra'  => ['label' => 'Message Form', 'title' => 'Form Heading', 'text' => null],
        ],
    ];

    public static function aboutSlugs(): array
    {
        return array_keys(array_filter(self::PAGES, fn ($p) => $p['about']));
    }

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
