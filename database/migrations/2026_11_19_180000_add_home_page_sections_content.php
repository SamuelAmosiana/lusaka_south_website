<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SLUGS = ['home-hero', 'home-values', 'home-scholarships', 'home-campus', 'home-environment', 'home-contact'];

    public function up(): void
    {
        // Hero background image, and a short value (e.g. "70%") for scholarship award cards
        Schema::table('about_pages', function (Blueprint $table) {
            $table->string('image')->nullable()->after('extra_text');
        });
        Schema::table('about_items', function (Blueprint $table) {
            $table->string('value', 50)->nullable()->after('subtitle');
        });

        $now = now();
        $page = fn (string $slug, array $data) => array_merge([
            'slug' => $slug, 'header_title' => '', 'header_subtitle' => null, 'section_title' => '',
            'section_subtitle' => null, 'extra_title' => null, 'extra_text' => null, 'image' => null,
            'created_at' => $now, 'updated_at' => $now,
        ], $data);

        // Seeded with the content the home page showed before these sections became editable
        DB::table('about_pages')->insert([
            $page('home-hero', [
                'header_title'     => config('site.name'),
                'header_subtitle'  => config('site.tagline'),
                'section_title'    => 'Hero',
                'section_subtitle' => "Empowering minds, shaping futures, and building tomorrow's leaders through excellence in education and innovative learning experiences.",
                'image'            => 'images/lsuc site cover img.jpeg',
            ]),
            $page('home-values', [
                'section_title'    => 'Our Core Values',
                'section_subtitle' => 'The principles that guide our commitment to educational excellence and student success',
            ]),
            $page('home-scholarships', [
                'header_subtitle'  => 'Financial Aid',
                'section_title'    => 'Former Presidents of Zambia Scholarship Awards',
                'section_subtitle' => 'Financial aid opportunities for every Zambian student — from 20% to 100% tuition discounts.',
                'extra_text'       => "LSUC offers seven tiered scholarship awards named after Zambia's former Presidents — from 20% to 100% tuition discounts. Whether you are from a rural area, differently abled, an international student, or pursuing skills training, there is an award designed for you.",
                'extra_title'      => 'Explore All 7 Awards',
            ]),
            $page('home-campus', [
                'section_title'    => 'Our Campus',
                'section_subtitle' => 'Experience world-class facilities designed to support your academic journey.',
            ]),
            $page('home-environment', [
                'section_title'    => 'School Environment',
                'section_subtitle' => 'Take a visual tour of our modern campus facilities and vibrant student life.',
            ]),
            $page('home-contact', [
                'section_title'    => 'Get in Touch',
                'section_subtitle' => 'Have a question about admissions, programmes or scholarships? Reach out and our team will get back to you.',
                'extra_title'      => 'Main Office',
            ]),
        ]);

        $items = [];
        $add = function (string $pageSlug, string $group, array $rows) use (&$items, $now) {
            foreach ($rows as $i => $row) {
                $items[] = array_merge([
                    'subtitle' => null, 'value' => null, 'body' => null, 'icon' => null, 'image' => null,
                    'url' => null, 'phone' => null, 'email' => null,
                ], $row, [
                    'page' => $pageSlug, 'group' => $group, 'sort_order' => $i + 1, 'is_active' => true,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        };

        $add('home-values', 'values', [
            ['icon' => 'fa-bullseye', 'title' => 'Excellence', 'image' => 'images/excellence.jpg', 'body' => 'We are committed to maintaining the highest standards in education, research, and student services. Our programs are designed to challenge and inspire.'],
            ['icon' => 'fa-handshake', 'title' => 'Integrity', 'image' => 'images/integrity.jpg', 'body' => 'We uphold honesty, transparency, and ethical conduct in all our dealings. We foster a culture of trust and accountability across our institution.'],
            ['icon' => 'fa-lightbulb', 'title' => 'Innovation', 'image' => 'images/innovation.jpg', 'body' => 'We embrace new ideas and technologies to enhance learning experiences. Our curriculum stays current with industry trends and global developments.'],
        ]);

        $add('home-scholarships', 'stats', [
            ['title' => '7', 'subtitle' => 'Award Types'],
            ['title' => '350+', 'subtitle' => 'Slots / Intake'],
            ['title' => '100%', 'subtitle' => 'Max Discount'],
            ['title' => '20%', 'subtitle' => 'Min Discount'],
        ]);

        $add('home-scholarships', 'awards', [
            ['value' => '70%', 'subtitle' => 'Rural & Disabled', 'title' => 'Kenneth D. Kaunda Award', 'body' => '5 Distinctions at O’ Level. For rural and differently abled applicants.'],
            ['value' => '50%', 'subtitle' => 'All Zambians', 'title' => 'Levy P. Mwanawasa Award', 'body' => 'Open to all Zambian citizens with minimum 3 Merits at O’ Level.'],
            ['value' => '100%', 'subtitle' => 'Govt. Funded', 'title' => 'Hakainde Hichilema CDF Award', 'body' => 'Full scholarship for skills courses, funded by the Government of Zambia.'],
            ['value' => '60%', 'subtitle' => 'Rural & Urban', 'title' => 'Frederick T. Chiluba Award', 'body' => '5 Merits at O’ Level. Rural, peri-rural, urban & differently abled.'],
            ['value' => '40%', 'subtitle' => 'Technical Only', 'title' => 'Rupiah B. Banda Award', 'body' => 'For technical skills programmes only, with 5 Credits or better.'],
        ]);

        $add('home-campus', 'slides', [
            ['image' => 'images/school3.jpeg', 'title' => 'State-of-the-Art Facilities', 'body' => 'Modern classrooms and labs equipped for 21st century learning'],
            ['image' => 'images/school1.jpeg', 'title' => 'Vibrant Campus Life', 'body' => 'A thriving community of students, faculty, and staff'],
            ['image' => 'images/school4.jpeg', 'title' => 'Inspiring Learning Environment', 'body' => 'Spaces designed to foster creativity and academic excellence'],
            ['image' => 'images/school2.jpeg', 'title' => 'Industry-Ready Programs', 'body' => 'Curricula aligned with real-world industry needs'],
        ]);

        $add('home-environment', 'photos', array_map(fn ($p) => ['image' => 'images/' . $p[0], 'title' => $p[1]], [
            ['computer_lab.jpg', 'Computer Laboratory'], ['scienc_lab.jpg', 'Science Laboratory'], ['library.jpg', 'Library'],
            ['auditorium.jpg', 'Auditorium'], ['sports_complex.jpg', 'Sports Complex'], ['cafteria.jpg', 'Cafeteria'],
            ['campus_grounds.jpg', 'Campus Grounds'], ['culture.jpg', 'Cultural Events'],
        ]));

        $contact = config('site.contact');
        $add('home-contact', 'details', [
            ['icon' => 'fa-location-dot', 'title' => 'Address', 'body' => implode("\n", $contact['address'])],
            ['icon' => 'fa-phone', 'title' => 'Phone', 'body' => $contact['phone']],
            ['icon' => 'fa-envelope', 'title' => 'Email', 'body' => $contact['email']],
            ['icon' => 'fa-user-graduate', 'title' => 'To talk to Enrolments, Call', 'body' => implode("\n", $contact['enrolments'])],
            ['icon' => 'fa-clock', 'title' => 'Office Hours', 'body' => implode("\n", $contact['office_hours'])],
        ]);

        DB::table('about_items')->insert($items);
    }

    public function down(): void
    {
        DB::table('about_items')->whereIn('page', self::SLUGS)->delete();
        DB::table('about_pages')->whereIn('slug', self::SLUGS)->delete();
        Schema::table('about_items', fn (Blueprint $table) => $table->dropColumn('value'));
        Schema::table('about_pages', fn (Blueprint $table) => $table->dropColumn('image'));
    }
};
