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
        'image',
    ];

    /** Text fields shown on a page's edit form unless the page lists its own 'fields'. */
    public const DEFAULT_FIELDS = [
        'header_title'     => ['label' => 'Banner Title', 'required' => true],
        'header_subtitle'  => ['label' => 'Banner Subtitle'],
        'section_title'    => ['label' => 'Section Heading', 'required' => true],
        'section_subtitle' => ['label' => 'Section Subtitle'],
    ];

    /**
     * The editable content pages and home page sections.
     *
     * - area:   'about' pages are listed under About Pages, 'home' sections under Home Page;
     *           'page' ones have their own admin menu link.
     * - fields: page-level text fields and their labels (DEFAULT_FIELDS when omitted);
     *           'textarea' => true for long text, 'image' => true for an uploadable picture.
     * - extra:  optional second block (stored in extra_title / extra_text).
     * - groups: the lists of items the page shows. Item types:
     *     card    = icon / title / text            person  = photo / name / role / text
     *     link    = card with a website button      contact = card with phone / email
     *     value   = background photo / icon / title / text
     *     stat    = big number / label              award   = value / tag / name / text
     *     slide   = photo / title / description     photo   = photo / caption
     *     info    = icon / label / lines of text (phone numbers and emails become links)
     */
    public const PAGES = [
        'overview' => [
            'area'   => 'about',
            'label'  => 'LSUC Overview',
            'route'  => 'about',
            'icon'   => 'fa-circle-info',
            'groups' => ['cards' => ['label' => 'Information Cards', 'type' => 'card']],
            'extra'  => null,
        ],
        'team' => [
            'area'   => 'about',
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
            'area'   => 'about',
            'label'  => 'Partnerships',
            'route'  => 'about.partnerships',
            'icon'   => 'fa-handshake',
            'groups' => ['cards' => ['label' => 'Partnership Cards', 'type' => 'card']],
            'extra'  => null,
        ],
        'accreditation' => [
            'area'   => 'about',
            'label'  => 'Accreditation',
            'route'  => 'about.accreditation',
            'icon'   => 'fa-certificate',
            'groups' => ['cards' => ['label' => 'Accreditation Cards', 'type' => 'card']],
            'extra'  => ['label' => 'Quality Assurance Box', 'title' => 'Box Heading', 'text' => 'Box Text'],
        ],
        'e-resources' => [
            'area'   => 'page',
            'label'  => 'E-Resources',
            'route'  => 'e-resources',
            'icon'   => 'fa-book-bookmark',
            'groups' => ['cards' => ['label' => 'Resource Links', 'type' => 'link']],
            'extra'  => null,
        ],
        'contact' => [
            'area'   => 'page',
            'label'  => 'Contact Page',
            'route'  => 'contact',
            'icon'   => 'fa-address-book',
            'groups' => ['cards' => ['label' => 'Department Contact Cards', 'type' => 'contact']],
            'extra'  => ['label' => 'Message Form', 'title' => 'Form Heading', 'text' => null],
        ],

        // Home page sections (in the order they appear on the home page)
        'home-hero' => [
            'area'        => 'home',
            'label'       => 'Hero Banner',
            'description' => 'The large banner at the top: heading, motto, intro text and background picture.',
            'route'       => 'home',
            'icon'        => 'fa-panorama',
            'fields'      => [
                'header_title'     => ['label' => 'Main Heading', 'required' => true],
                'header_subtitle'  => ['label' => 'Motto'],
                'section_subtitle' => ['label' => 'Intro Text', 'textarea' => true],
                'image'            => ['label' => 'Background Picture', 'image' => true],
            ],
            'groups'      => [],
            'extra'       => null,
        ],
        'home-values' => [
            'area'        => 'home',
            'label'       => 'Core Values',
            'description' => 'The value cards with background pictures (Excellence, Integrity, Innovation).',
            'route'       => 'home',
            'icon'        => 'fa-gem',
            'fields'      => [
                'section_title'    => ['label' => 'Section Heading', 'required' => true],
                'section_subtitle' => ['label' => 'Section Subtitle'],
            ],
            'groups'      => ['values' => ['label' => 'Value Cards', 'type' => 'value']],
            'extra'       => null,
        ],
        'home-scholarships' => [
            'area'        => 'home',
            'label'       => 'Scholarships',
            'description' => 'The green scholarships section: headings, the four figures and the award cards.',
            'route'       => 'home',
            'icon'        => 'fa-hand-holding-dollar',
            'fields'      => [
                'header_subtitle'  => ['label' => 'Small Label Above Heading'],
                'section_title'    => ['label' => 'Section Heading', 'required' => true],
                'section_subtitle' => ['label' => 'Highlighted Line'],
                'extra_text'       => ['label' => 'Introduction', 'textarea' => true],
                'extra_title'      => ['label' => 'Heading of the Last "View All" Card'],
            ],
            'groups'      => [
                'stats'  => ['label' => 'Figures (e.g. 7 Award Types)', 'type' => 'stat'],
                'awards' => ['label' => 'Award Cards', 'type' => 'award'],
            ],
            'extra'       => null,
        ],
        'home-campus' => [
            'area'        => 'home',
            'label'       => 'Our Campus',
            'description' => 'The rotating campus slideshow: pictures with a title and short description.',
            'route'       => 'home',
            'icon'        => 'fa-building-columns',
            'fields'      => [
                'section_title'    => ['label' => 'Section Heading', 'required' => true],
                'section_subtitle' => ['label' => 'Section Subtitle'],
            ],
            'groups'      => ['slides' => ['label' => 'Slideshow Pictures', 'type' => 'slide']],
            'extra'       => null,
        ],
        'home-environment' => [
            'area'        => 'home',
            'label'       => 'School Environment',
            'description' => 'The photo grid near the bottom of the home page, with captions.',
            'route'       => 'home',
            'icon'        => 'fa-images',
            'fields'      => [
                'section_title'    => ['label' => 'Section Heading', 'required' => true],
                'section_subtitle' => ['label' => 'Section Subtitle'],
            ],
            'groups'      => ['photos' => ['label' => 'Photos', 'type' => 'photo']],
            'extra'       => null,
        ],
        'home-contact' => [
            'area'        => 'home',
            'label'       => 'Get in Touch',
            'description' => 'The contact details card next to the message form: address, phones, email and hours.',
            'route'       => 'home',
            'icon'        => 'fa-address-card',
            'fields'      => [
                'section_title'    => ['label' => 'Section Heading', 'required' => true],
                'section_subtitle' => ['label' => 'Section Subtitle'],
                'extra_title'      => ['label' => 'Contact Card Heading'],
            ],
            'groups'      => ['details' => ['label' => 'Contact Details', 'type' => 'info']],
            'extra'       => null,
        ],
    ];

    /** Item types that carry a picture, and those where a picture is required. */
    public const IMAGE_TYPES = ['person', 'value', 'slide', 'photo'];
    public const IMAGE_REQUIRED_TYPES = ['slide', 'photo'];

    public static function slugsIn(string $area): array
    {
        return array_keys(array_filter(self::PAGES, fn ($p) => $p['area'] === $area));
    }

    public static function aboutSlugs(): array
    {
        return self::slugsIn('about');
    }

    public static function fieldsFor(string $slug): array
    {
        return self::PAGES[$slug]['fields'] ?? self::DEFAULT_FIELDS;
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

    public function fields(): array
    {
        return self::fieldsFor($this->slug);
    }

    /** Active items for one group, for the public pages. */
    public function activeItems(string $group)
    {
        return $this->items->where('group', $group)->where('is_active', true)->values();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image && file_exists(public_path($this->image)) ? asset($this->image) : null;
    }
}
