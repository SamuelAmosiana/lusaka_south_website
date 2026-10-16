<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Seed sample events from legacy structure
        $events = [
            [
                'title'             => 'Welcome to the 2026 Academic Year',
                'date'              => '2026-02-03',
                'category'          => 'Latest News',
                'author'            => 'Admin',
                'image'             => null,
                'short_description' => 'Lusaka South University College officially opens the 2026 academic year. All students are welcome to begin their studies.',
                'full_description'  => '<p>Lusaka South University College is pleased to announce the start of the 2026 academic year. Registration is ongoing at the admissions office.</p><p>All enrolled students are encouraged to report to their respective faculties and collect their timetables.</p>',
                'featured'          => true,
            ],
            [
                'title'             => 'Application Deadline for April Intake',
                'date'              => '2026-03-15',
                'category'          => 'Upcoming Event',
                'author'            => 'Admissions',
                'image'             => null,
                'short_description' => 'Applications for the April 2026 intake close on March 15. Apply now to secure your spot.',
                'full_description'  => '<p>The deadline for the April 2026 intake is <strong>March 15, 2026</strong>. All prospective students must submit their completed application forms along with required documents before this date.</p><ul><li>Completed application form</li><li>Grade 12 certificate</li><li>National Registration Card</li><li>Two passport photographs</li><li>Proof of payment (application fee)</li></ul>',
                'featured'          => true,
            ],
            [
                'title'             => '2025 Graduation Ceremony',
                'date'              => '2025-11-28',
                'category'          => 'Past Event',
                'author'            => 'Admin',
                'image'             => null,
                'short_description' => 'LSUC held its 2025 graduation ceremony celebrating the achievements of graduating students.',
                'full_description'  => '<p>Lusaka South University College successfully held its 2025 Graduation Ceremony. Over 100 students graduated across all schools and faculties.</p><p>The ceremony was attended by distinguished guests, parents, and faculty members who celebrated this milestone with our graduates.</p>',
                'featured'          => false,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(['title' => $event['title']], $event);
        }
    }
}
