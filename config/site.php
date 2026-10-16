<?php

return [
    'name'       => 'Lusaka South University College',
    'short_name' => 'LSUC',
    'tagline'    => 'Dream, Explore, Acquire',
    'url'        => 'https://lsc.edu.zm',
    'email'      => 'info@lusakasouthcollege.edu.zm',
    'admissions_email' => 'admissions@lsc.edu.zm',
    'phone'      => '+260-211-292-299',
    'phone2'     => '+260 770359518',
    'address'    => 'Foxdale, Corner of Zambezi and Mutumbi Road, Lusaka, Zambia',
    'facebook'   => 'https://www.facebook.com/profile.php?id=61564462900141',
    'linkedin'   => 'https://www.linkedin.com/company/lsuc/posts/?feedView=all',
    'instagram'  => '#',
    'twitter'    => '#',
    'student_portal' => 'https://srms.lsc.edu.zm/auth/student_login.php',
    'staff_portal'   => 'https://srms.lsc.edu.zm/auth/login.php',
    'elearning'      => 'https://elearning.lsc.edu.zm/login/index.php',
    'research'       => 'https://demo.dspace.org/home',

    // External application forms (the /apply/* pages redirect here)
    'apply' => [
        'undergraduate'      => 'https://lsc.edu.zm/#undergraduate-application',
        'short_courses'      => 'https://srms.lsc.edu.zm/applications/short_courses_application',
        'corporate_training' => 'https://docs.google.com/forms/d/e/1FAIpQLSdoRKsQChnEPC9HYUDwcUpfpyX6_SowsDIGLPKZ699E1lT6OQ/viewform',
    ],

    // Pay Online (card / mobile money go through Swish, see config/services.php)
    'payments' => [
        'finance_phone'   => '+260 211 292292',
        'donations_email' => 'info@lsc.edu.zm',
    ],
];
