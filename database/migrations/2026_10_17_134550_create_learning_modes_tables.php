<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Learning Settings Table
        Schema::create('learning_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->default('Online and On-Campus Learning');
            $table->text('section_subtitle')->nullable();
            $table->string('video_path')->default('images/campus_video.mp4');
            $table->string('video_title')->default('Campus Overview');
            $table->text('video_description')->nullable();
            $table->string('poster_image')->nullable()->default('images/school2.jpeg');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Learning Features Table (Left Column items)
        Schema::create('learning_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('icon', 50)->default('fa-laptop-code');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial settings
        DB::table('learning_settings')->insert([
            'section_title'     => 'Online and On-Campus Learning',
            'section_subtitle'  => 'Flexible study modes designed to empower your educational journey wherever you are.',
            'video_path'        => 'images/campus_video.mp4',
            'video_title'       => 'Campus Overview',
            'video_description' => 'Experience world-class learning spaces, digital infrastructure, and vibrant student community at Foxdale, Lusaka.',
            'poster_image'      => 'images/school2.jpeg',
            'is_active'         => true,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Seed initial 4 learning features
        DB::table('learning_features')->insert([
            [
                'title'       => 'E-Learning System',
                'description' => 'Classes will take place in a Virtual Learning Environment (VLE). Users can log in at any time and at their convenience. Once online, students will be able to communicate with their lecturer and fellow students, read assigned texts, access digital learning materials and the e-Library resources on a 24/7 basis, post assignments and comment on other students’ contributions to the class.',
                'icon'        => 'fa-laptop-code',
                'sort_order'  => 1,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Online Lecturers',
                'description' => 'Students are given the greatest possible support with dedicated online lecturers. All the lecturers are experts in their respective fields and have years of experience at the highest standard. The class lecturer will respond to all queries at a fast pace.',
                'icon'        => 'fa-chalkboard-user',
                'sort_order'  => 2,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'Online Classroom',
                'description' => 'The online learning experience will include extensive participation and interaction with the online lecturer and fellow students.',
                'icon'        => 'fa-comments',
                'sort_order'  => 3,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'title'       => 'On-Campus',
                'description' => 'For those who prefer on-campus study, Lusaka South University College goes the extra mile to enhance online learning with additional face-to-face tutorials. These tutorials take place on the fully equipped campus in Foxdale Lusaka, which boasts modern infrastructure and a technology-friendly environment. This supportive atmosphere fosters a sense of community among students, faculty, and staff, further enriching the learning experience.',
                'icon'        => 'fa-building-columns',
                'sort_order'  => 4,
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_features');
        Schema::dropIfExists('learning_settings');
    }
};
