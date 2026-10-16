<?php

namespace Database\Seeders;

use App\Models\Download;
use Illuminate\Database\Seeder;

class DownloadSeeder extends Seeder
{
    public function run(): void
    {
        $downloads = [
            // Institutional Documents
            [
                'title'       => 'CPD & Short Courses',
                'description' => 'Download information about our Continuing Professional Development and Short Course offerings',
                'filename'    => 'cpd-short-courses.pdf',
                'category'    => 'Institutional Documents',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Academic Calendar',
                'description' => 'Access our academic calendar with important dates and events',
                'filename'    => 'academic-calendar.pdf',
                'category'    => 'Institutional Documents',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Application Form',
                'description' => 'Download Application Form for Physical Admission',
                'filename'    => 'application-form.pdf',
                'category'    => 'Institutional Documents',
                'sort_order'  => 3,
            ],
            // Additional Resources
            [
                'title'       => 'Course Exemption Form',
                'description' => 'Application form for students seeking exemption from certain courses based on prior learning or qualifications',
                'filename'    => 'exemption_form.pdf',
                'category'    => 'Additional Resources',
                'sort_order'  => 4,
            ],
            [
                'title'       => 'Student Portal Access Guide',
                'description' => 'Step-by-step instructions for accessing your student portal and viewing academic results',
                'filename'    => 'Student-Portal-Access-Guide.pdf',
                'category'    => 'Additional Resources',
                'sort_order'  => 5,
            ],
            [
                'title'       => 'Program Catalog',
                'description' => 'Detailed information about all our academic programs',
                'filename'    => 'program-catalog.pdf',
                'category'    => 'Additional Resources',
                'sort_order'  => 6,
            ],
            [
                'title'       => 'CDF Bursary Application Form',
                'description' => 'Skills Development Bureau Bursary Application Form',
                'filename'    => 'cdf_bussary.pdf',
                'category'    => 'Additional Resources',
                'sort_order'  => 7,
            ],
            [
                'title'       => 'Press Release - Official Establishment',
                'description' => 'Read about our official establishment as a premier hub for industry-aligned higher education and skills training',
                'filename'    => 'press_release.pdf',
                'category'    => 'Additional Resources',
                'sort_order'  => 8,
            ],
            [
                'title'       => 'Time Table',
                'description' => 'A planning tool that shows the scheduled times of lectures, tutorials, and other classes',
                'filename'    => 'time-table.pdf',
                'category'    => 'Additional Resources',
                'sort_order'  => 9,
            ],
        ];

        foreach ($downloads as $download) {
            Download::updateOrCreate(['filename' => $download['filename']], $download);
        }
    }
}
