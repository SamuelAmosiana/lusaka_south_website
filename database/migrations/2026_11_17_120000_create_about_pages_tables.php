<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Page-level text for the four About pages (one row per page)
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('header_title');
            $table->string('header_subtitle')->nullable();
            $table->string('section_title');
            $table->text('section_subtitle')->nullable();
            // Second block: "Heads of Departments" heading on Team, "Quality Assurance" box on Accreditation
            $table->string('extra_title')->nullable();
            $table->text('extra_text')->nullable();
            $table->timestamps();
        });

        // Cards and people shown on the About pages
        Schema::create('about_items', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50);
            $table->string('group', 50);
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('body')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['page', 'group', 'sort_order']);
        });

        $now = now();

        // Seed with the content the pages had before they became editable
        DB::table('about_pages')->insert([
            ['slug' => 'overview', 'header_title' => 'About LSUC', 'header_subtitle' => 'Our story, mission, and vision for higher education in Zambia',
             'section_title' => 'LSUC Overview', 'section_subtitle' => 'Transforming lives through quality education and skills development.',
             'extra_title' => null, 'extra_text' => null, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'team', 'header_title' => 'Our Team', 'header_subtitle' => 'Meet the dedicated professionals leading LSUC',
             'section_title' => 'Leadership Team', 'section_subtitle' => 'Experienced professionals guiding our institution to excellence.',
             'extra_title' => 'Heads of Departments', 'extra_text' => 'Academic leaders driving excellence in their respective schools.', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'partnerships', 'header_title' => 'Partnerships & Collaborations', 'header_subtitle' => 'Building bridges between academia and industry',
             'section_title' => 'Our Partnerships', 'section_subtitle' => 'Strategic alliances that enhance our educational offerings and student opportunities.',
             'extra_title' => null, 'extra_text' => null, 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'accreditation', 'header_title' => 'Accreditation & Recognition', 'header_subtitle' => 'Nationally recognized qualifications you can trust',
             'section_title' => 'Our Accreditation', 'section_subtitle' => 'LSUC is fully registered and accredited by the relevant regulatory authorities in Zambia.',
             'extra_title' => 'Quality Assurance', 'extra_text' => 'We are committed to continuous quality improvement in all our programs and services. Our internal quality assurance processes ensure that we consistently meet and exceed the standards set by our accrediting bodies.',
             'created_at' => $now, 'updated_at' => $now],
        ]);

        $items = [];
        $add = function (string $page, string $group, array $rows) use (&$items, $now) {
            foreach ($rows as $i => $row) {
                $items[] = array_merge(['subtitle' => null, 'body' => null, 'icon' => null, 'image' => null], $row, [
                    'page' => $page, 'group' => $group, 'sort_order' => $i + 1, 'is_active' => true,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        };

        $add('overview', 'cards', [
            ['icon' => 'fa-book-open', 'title' => 'Our History', 'body' => 'Lusaka South University College (LSUC) was established to address the growing need for quality, industry-aligned higher education in Zambia. Built on a foundation of academic excellence, we have grown to become a premier institution for skills development and professional training.'],
            ['icon' => 'fa-bullseye', 'title' => 'Our Mission', 'body' => 'To provide accessible, affordable, and high-quality education and training that empowers individuals with the skills and knowledge needed to excel in their careers and contribute meaningfully to the development of Zambia.'],
            ['icon' => 'fa-eye', 'title' => 'Our Vision', 'body' => 'To be the leading higher education institution in Zambia, recognized for academic excellence, innovation, and producing graduates who are ready for the challenges of the modern workforce.'],
            ['icon' => 'fa-building', 'title' => 'Our Campus', 'body' => 'Located in the serene Foxdale area of Lusaka, our campus offers modern facilities including well-equipped classrooms, computer labs, a science laboratory, a library, sports facilities, and a cafeteria — all designed to support a holistic learning experience.'],
            ['icon' => 'fa-certificate', 'title' => 'Accreditation', 'body' => 'LSUC is registered and accredited by the Higher Education Authority (HEA) of Zambia and the Technical Education, Vocational and Entrepreneurship Training Authority (TEVETA), ensuring that our qualifications are recognized nationally and internationally.'],
            ['icon' => 'fa-industry', 'title' => 'Industry Partnerships', 'body' => 'We maintain strong partnerships with leading organizations across various sectors, providing our students with internship opportunities, industry exposure, and employment pathways upon graduation.'],
        ]);

        $add('team', 'leadership', [
            ['title' => 'Vice Principal', 'subtitle' => 'Vice Principal', 'image' => 'images/staff/Vice-Principal.jpg', 'body' => 'Providing strategic oversight and academic direction for the institution.'],
            ['title' => 'Registrar', 'subtitle' => 'Registrar', 'image' => 'images/staff/Registrar.jpg', 'body' => 'Managing academic records, admissions, and student administration.'],
            ['title' => 'Academic Dean', 'subtitle' => 'Academic Dean', 'image' => 'images/staff/Academic-Dean.jpg', 'body' => 'Overseeing academic programs and ensuring quality education standards.'],
            ['title' => 'Enrollments Officer', 'subtitle' => 'Enrollments Officer', 'image' => 'images/staff/Enrollments-Officer.jpg', 'body' => 'Facilitating student enrollment and admissions processes.'],
            ['title' => 'Systems Administrator', 'subtitle' => 'Systems Administrator', 'image' => 'images/staff/Systems-Administrator.jpg', 'body' => 'Managing ICT infrastructure and digital learning systems.'],
            ['title' => 'Front Desk Officer', 'subtitle' => 'Front Desk Officer', 'image' => 'images/staff/front-desk-officer.jpg', 'body' => 'The first point of contact for students, staff, and visitors.'],
        ]);

        $add('team', 'hods', [
            ['title' => 'Mr. Bwalya', 'subtitle' => 'Head – School of Engineering & Technology', 'image' => 'images/hod/mr_bwalya_engineering.jpg'],
            ['title' => 'Mr. Machila', 'subtitle' => 'Head – School of Business & Management', 'image' => 'images/hod/mr_machila_business.jpg'],
            ['title' => 'Mr. Sianamate', 'subtitle' => 'Head – School of Information Technology', 'image' => 'images/hod/mr_sianamate_it.jpg'],
            ['title' => 'Mr. Chido', 'subtitle' => 'Head – School of Health Sciences', 'image' => 'images/hod/mr_chido_health_sciences.jpg'],
        ]);

        $add('partnerships', 'cards', [
            ['icon' => 'fa-industry', 'title' => 'Industry Partners', 'body' => "We collaborate with leading companies across Zambia's key industries to ensure our curricula remain relevant and to provide students with practical work experience opportunities."],
            ['icon' => 'fa-globe', 'title' => 'International Collaborations', 'body' => "Our international partnerships expose students to global best practices and open doors to further education and career opportunities beyond Zambia's borders."],
            ['icon' => 'fa-landmark', 'title' => 'Government & Regulatory Bodies', 'body' => 'We work closely with government ministries, regulatory bodies, and professional associations to align our programs with national development goals.'],
            ['icon' => 'fa-handshake', 'title' => 'Corporate Training Partners', 'body' => 'We partner with organizations to deliver customized corporate training programs that build workforce capacity and drive organizational performance.'],
            ['icon' => 'fa-flask', 'title' => 'Research Partners', 'body' => 'Academic and research collaborations help us contribute to knowledge creation and ensure our faculty remain at the cutting edge of their disciplines.'],
            ['icon' => 'fa-money-bill-wave', 'title' => 'Scholarship Partners', 'body' => 'Through partnerships with funding organizations, we secure scholarships and bursaries that make quality education accessible to deserving students.'],
        ]);

        $add('accreditation', 'cards', [
            ['icon' => 'fa-medal', 'title' => 'Higher Education Authority (HEA)', 'body' => 'LSUC is registered and accredited by the Higher Education Authority of Zambia, ensuring our degree and diploma programs meet national academic standards.'],
            ['icon' => 'fa-certificate', 'title' => 'TEVETA Accreditation', 'body' => "Our technical and vocational programs are accredited by the Technical Education, Vocational and Entrepreneurship Training Authority (TEVETA), Zambia's key body for TEVET."],
            ['icon' => 'fa-star', 'title' => 'Professional Associations', 'body' => 'Several of our programs are recognized by professional associations, allowing graduates to pursue professional membership and certification in their fields.'],
            ['icon' => 'fa-globe', 'title' => 'International Recognition', 'body' => "Our qualifications are structured to align with international standards, facilitating recognition of our graduates' credentials in the global job market."],
        ]);

        DB::table('about_items')->insert($items);
    }

    public function down(): void
    {
        Schema::dropIfExists('about_items');
        Schema::dropIfExists('about_pages');
    }
};
