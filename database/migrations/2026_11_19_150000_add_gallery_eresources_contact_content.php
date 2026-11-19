<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extra fields for E-Resources link cards and Contact department cards
        Schema::table('about_items', function (Blueprint $table) {
            $table->string('url')->nullable()->after('image');
            $table->string('phone', 50)->nullable()->after('url');
            $table->string('email')->nullable()->after('phone');
        });

        Schema::create('gallery_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_section_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        // Gallery: seeded with the photos the page showed before
        $sections = [
            ['Campus Facilities', 'Modern facilities built to support world-class learning.', [
                ['computer_lab.jpg', 'Computer Laboratory'], ['scienc_lab.jpg', 'Science Laboratory'], ['library.jpg', 'Library'],
                ['auditorium.jpg', 'Auditorium'], ['sports_complex.jpg', 'Sports Complex'], ['cafteria.jpg', 'Cafeteria'],
                ['campus_grounds.jpg', 'Campus Grounds'], ['hea.jpeg', 'Main Campus'],
            ]],
            ['Student Life', 'Capturing the vibrant life of our students on campus.', [
                ['culture.jpg', 'Cultural Events'], ['enrollment.jpeg', 'Enrollment Day'],
                ['students_solar tech.jpeg', 'Solar Technology Class'], ['Health-Sciences.webp', 'Health Sciences'],
            ]],
            ['Academic Environment', 'Our schools and learning environments at a glance.', [
                ['school1.jpeg', 'School Building'], ['school2.jpeg', 'Academic Block'],
                ['school3.jpeg', 'Campus View'], ['school4.jpeg', 'Campus Entrance'],
            ]],
        ];
        foreach ($sections as $i => [$title, $subtitle, $photos]) {
            $sectionId = DB::table('gallery_sections')->insertGetId([
                'title' => $title, 'subtitle' => $subtitle, 'sort_order' => $i + 1, 'is_active' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);
            DB::table('gallery_photos')->insert(array_map(fn ($p, $j) => [
                'gallery_section_id' => $sectionId, 'image' => 'images/' . $p[0], 'caption' => $p[1],
                'sort_order' => $j + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ], $photos, array_keys($photos)));
        }

        // E-Resources and Contact pages share the page-content tables with the About pages
        DB::table('about_pages')->insert([
            ['slug' => 'e-resources', 'header_title' => 'E-Resources Portal', 'header_subtitle' => 'Access digital learning resources and academic databases',
             'section_title' => 'Digital Learning Resources', 'section_subtitle' => 'Curated online resources to support your academic work.',
             'extra_title' => null, 'extra_text' => null, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'contact', 'header_title' => 'Contact Us', 'header_subtitle' => "We're here to help. Reach out to the right department below.",
             'section_title' => 'Get In Touch', 'section_subtitle' => 'Our team is ready to assist you with admissions, academics, finance and more.',
             'extra_title' => 'Send Us a Message', 'extra_text' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $items = [];
        $add = function (string $page, array $rows) use (&$items, $now) {
            foreach ($rows as $i => $row) {
                $items[] = array_merge(['subtitle' => null, 'body' => null, 'icon' => null, 'image' => null, 'url' => null, 'phone' => null, 'email' => null], $row, [
                    'page' => $page, 'group' => 'cards', 'sort_order' => $i + 1, 'is_active' => true,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        };

        $add('e-resources', [
            ['icon' => 'fa-book', 'title' => 'OpenStax', 'body' => 'Free, peer-reviewed textbooks for college and university courses across multiple disciplines.', 'url' => 'https://openstax.org', 'subtitle' => 'Visit OpenStax'],
            ['icon' => 'fa-scroll', 'title' => 'Google Scholar', 'body' => 'Search scholarly literature across many disciplines and sources: theses, books, abstracts, and articles.', 'url' => 'https://scholar.google.com', 'subtitle' => 'Visit Scholar'],
            ['icon' => 'fa-graduation-cap', 'title' => 'MIT OpenCourseWare', 'body' => 'Free online course materials from MIT covering nearly all undergraduate and graduate subjects.', 'url' => 'https://ocw.mit.edu', 'subtitle' => 'Visit MIT OCW'],
            ['icon' => 'fa-play-circle', 'title' => 'Khan Academy', 'body' => 'Free lessons in math, science, computing, humanities, and more, for learners of all levels.', 'url' => 'https://www.khanacademy.org', 'subtitle' => 'Visit Khan Academy'],
            ['icon' => 'fa-database', 'title' => 'DOAJ', 'body' => 'Directory of Open Access Journals — a comprehensive index of high-quality, open-access peer-reviewed journals.', 'url' => 'https://doaj.org', 'subtitle' => 'Visit DOAJ'],
            ['icon' => 'fa-globe-africa', 'title' => 'African Journals Online', 'body' => 'Access to peer-reviewed, African-published journals across a range of disciplines.', 'url' => 'https://www.ajol.info', 'subtitle' => 'Visit AJOL'],
        ]);

        $phone = config('site.phone');
        $add('contact', [
            ['icon' => 'fa-graduation-cap', 'title' => 'Admissions Office', 'phone' => $phone, 'email' => config('site.admissions_email'), 'body' => 'For queries about application requirements, deadlines, and enrollment procedures.'],
            ['icon' => 'fa-book-open', 'title' => 'Academic Affairs', 'phone' => $phone, 'email' => 'academics@lsc.edu.zm', 'body' => 'For academic timetables, course information, and examination matters.'],
            ['icon' => 'fa-building-columns', 'title' => 'Finance & Accounts', 'phone' => $phone, 'email' => 'finance@lsc.edu.zm', 'body' => 'For tuition fees, payment plans, and financial clearance enquiries.'],
            ['icon' => 'fa-laptop', 'title' => 'ICT & Student Portal', 'phone' => config('site.phone2'), 'email' => 'ict@lsc.edu.zm', 'body' => 'For student portal access, eLearning support, and technical assistance.'],
            ['icon' => 'fa-briefcase', 'title' => 'Corporate Training', 'phone' => $phone, 'email' => 'training@lsc.edu.zm', 'body' => 'For corporate training proposals, customized programs, and partnership inquiries.'],
            ['icon' => 'fa-location-dot', 'title' => 'Visit Us', 'body' => config('site.address') . "\nMonday – Friday: 8:00 AM – 5:00 PM\nSaturday: 9:00 AM – 1:00 PM"],
        ]);

        DB::table('about_items')->insert($items);
    }

    public function down(): void
    {
        DB::table('about_items')->whereIn('page', ['e-resources', 'contact'])->delete();
        DB::table('about_pages')->whereIn('slug', ['e-resources', 'contact'])->delete();
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_sections');
        Schema::table('about_items', function (Blueprint $table) {
            $table->dropColumn(['url', 'phone', 'email']);
        });
    }
};
