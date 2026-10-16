<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question'   => 'How do I apply for admission?',
                'answer'     => 'Applications can be submitted online through our website or in person at the admissions office. Required documents include academic certificates, national ID, and passport photos. Application deadlines vary by program.',
                'category'   => 'Admissions',
                'sort_order' => 1,
            ],
            [
                'question'   => 'What are the admission requirements?',
                'answer'     => 'Requirements vary by program. Generally, diploma programs require Grade 12 certificate, while certificate programs may accept Grade 10 or 11. Specific grade requirements are listed for each program.',
                'category'   => 'Admissions',
                'sort_order' => 2,
            ],
            [
                'question'   => 'Are scholarships available?',
                'answer'     => 'Yes, we offer merit-based scholarships, need-based financial aid, and partnerships with various organizations providing sponsorship opportunities for eligible students.',
                'category'   => 'Financial Aid',
                'sort_order' => 3,
            ],
            [
                'question'   => 'Is accommodation available?',
                'answer'     => 'We have on-campus hostels for both male and female students. Private accommodation options are also available nearby. Hostel applications should be submitted early due to limited spaces.',
                'category'   => 'Campus Life',
                'sort_order' => 4,
            ],
            [
                'question'   => 'What is the academic calendar?',
                'answer'     => 'The academic year runs from February to November with two semesters. There are mid-semester breaks and a longer holiday in December and January.',
                'category'   => 'Academic',
                'sort_order' => 5,
            ],
            [
                'question'   => 'How can I check my results?',
                'answer'     => 'Results are available through the student portal. You will receive login credentials upon enrollment. Results are also posted on notice boards in the academic buildings.',
                'category'   => 'Academic',
                'sort_order' => 6,
            ],
            [
                'question'   => 'What career services do you offer?',
                'answer'     => 'Our career services include job placement assistance, career counseling, resume writing workshops, interview preparation, and maintaining connections with industry partners.',
                'category'   => 'Career Services',
                'sort_order' => 7,
            ],
            [
                'question'   => 'How do I pay school fees?',
                'answer'     => 'Fees can be paid at the finance office using bank deposits or mobile money. Payment plans are available for students who need to pay in installments. We do not accept cash payments at campus.',
                'category'   => 'Financial',
                'sort_order' => 8,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
