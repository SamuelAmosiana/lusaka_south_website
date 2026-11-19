<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Page-level text and the catalogue PDF for /schools (single row)
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('header_title');
            $table->string('header_subtitle')->nullable();
            $table->string('section_title');
            $table->text('section_subtitle')->nullable();
            $table->string('catalog_pdf')->nullable();
            $table->timestamps();
        });

        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 50)->default('fa-graduation-cap');
            $table->text('intro')->nullable();
            // Key of config('site.apply'), so the Apply button follows the central links
            $table->string('apply_link', 50)->default('undergraduate');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('level', 20)->default('Certificate');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('school_settings')->insert([
            'header_title'     => 'Schools & Programmes',
            'header_subtitle'  => 'Explore our diverse range of industry-aligned academic programmes',
            'section_title'    => 'Our Schools',
            'section_subtitle' => 'Three schools and a range of trade and skills programmes, designed with industry input to ensure graduates are job-ready.',
            'catalog_pdf'      => 'docs/2026 PROGRAMMES.pdf',
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);

        // Seed with the schools and programmes the page showed before it became editable
        $schools = [
            ['School of Business & Management Studies', 'fa-briefcase', "Develop your business acumen with programmes designed to meet the demands of Zambia's growing economy.", 'undergraduate', [
                ['Bachelor of Business Administration (BBA)', 'Degree'],
                ['BSc Procurement and Supply Chain Management (BScPSCM)', 'Degree'],
                ['Bachelor of Accounting and Finance (BAF)', 'Degree'],
                ['Diploma in Business Administration', 'Diploma'],
                ['Diploma in Technical & Vocational Teaching (Teaching Methodology)', 'Diploma'],
            ]],
            ['School of Law', 'fa-scale-balanced', 'Build a strong foundation in legal principles and practice for a career in law and public service.', 'undergraduate', [
                ['Bachelor of Laws (LLB)', 'Degree'],
            ]],
            ['School of Engineering and Information Technology', 'fa-gears', "Gain the technical and digital skills demanded by Zambia's infrastructure, energy and technology sectors.", 'undergraduate', [
                ['Bachelor of Science in Information Communication Technology', 'Degree'],
                ['Diploma in Electrical Engineering and Renewable Energy', 'Diploma'],
                ['Diploma in Mechanical and Industrial Engineering', 'Diploma'],
                ['Diploma in Control & Instrumentation Engineering', 'Diploma'],
            ]],
            ['Trade and Skills Programmes', 'fa-screwdriver-wrench', 'Hands-on certificate programmes that equip you with practical, employable skills for the trades and health care.', 'short_courses', [
                ['Certificate in Automotive Engineering – Level 1', 'Certificate'],
                ['Certificate in Automotive Engineering – Level 2', 'Certificate'],
                ['Certificate in Automotive Engineering – Level 3', 'Certificate'],
                ['Certificate in Electrical Engineering/Electrical Technology – Level 1', 'Certificate'],
                ['Certificate in Electrical Engineering/Electrical Technology – Level 2', 'Certificate'],
                ['Certificate in Electrical Engineering/Electrical Technology – Level 3', 'Certificate'],
                ['Certificate in Solar Technology (Skills Award)', 'Certificate'],
                ['Certificate in Occupational Safety, Health & Environment', 'Certificate'],
                ['Certificate in Civil Engineering and Construction Management', 'Certificate'],
                ['Certificate in Information Communication Technologies (ICT)', 'Certificate'],
                ['Certificate in Nursing & Health Care Assistant', 'Certificate'],
                ['Certificate in Psychosocial Counselling', 'Certificate'],
            ]],
        ];

        foreach ($schools as $i => [$name, $icon, $intro, $apply, $programmes]) {
            $schoolId = DB::table('schools')->insertGetId([
                'name' => $name, 'icon' => $icon, 'intro' => $intro, 'apply_link' => $apply,
                'sort_order' => $i + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);

            DB::table('programmes')->insert(array_map(fn ($p, $j) => [
                'school_id' => $schoolId, 'name' => $p[0], 'level' => $p[1],
                'sort_order' => $j + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ], $programmes, array_keys($programmes)));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('school_settings');
    }
};
