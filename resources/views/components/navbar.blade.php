<nav class="navbar">
    <div class="nav-container">
        <div class="logo-section">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/lsuc_logo.jpeg') }}" alt="{{ config('site.short_name') }} Logo">
                </a>
            </div>
            <div class="logo-text">
                <h2><a href="{{ route('home') }}" style="color:white;text-decoration:none">{{ config('site.short_name') }}</a></h2>
                <p>{{ config('site.tagline') }}</p>
            </div>
        </div>

        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" aria-label="Toggle menu" id="mobileMenuToggle">
            <span></span><span></span><span></span>
        </button>

        <ul class="nav-menu" id="navMenu">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('about') }}" class="nav-link" onclick="toggleDropdown(this)">About Us <i class="fa fa-chevron-down fa-xs"></i></a>
                <ul class="dropdown">
                    <li><a href="{{ route('about') }}" class="dropdown-link"><i class="fa fa-building fa-sm me-2"></i> LSUC Overview</a></li>
                    <li><a href="{{ route('about.team') }}" class="dropdown-link"><i class="fa fa-users fa-sm me-2"></i> Our Team</a></li>
                    <li><a href="{{ route('about.partnerships') }}" class="dropdown-link"><i class="fa fa-handshake fa-sm me-2"></i> Partnerships</a></li>
                    <li><a href="{{ route('about.accreditation') }}" class="dropdown-link"><i class="fa fa-medal fa-sm me-2"></i> Accreditation</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('schools') }}" class="nav-link">Schools</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('news') }}" class="nav-link">News &amp; Events</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleDropdown(this)">Apply <i class="fa fa-chevron-down fa-xs"></i></a>
                <ul class="dropdown">
                    <li><a href="{{ config('site.apply.undergraduate') }}" class="dropdown-link"><i class="fa fa-graduation-cap fa-sm me-2"></i> Degree &amp; Diploma Programmes</a></li>
                    <li><a href="{{ config('site.apply.short_courses') }}" class="dropdown-link"><i class="fa fa-certificate fa-sm me-2"></i> Certificate Programmes</a></li>
                    <li><a href="{{ config('site.apply.corporate_training') }}" class="dropdown-link"><i class="fa fa-briefcase fa-sm me-2"></i> Corporate Training</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleDropdown(this)">Pay Online <i class="fa fa-chevron-down fa-xs"></i></a>
                <ul class="dropdown">
                    <li><a href="{{ route('pay.online') }}" class="dropdown-link"><i class="fa fa-credit-card fa-sm me-2"></i> Make a Payment</a></li>
                    <li><a href="{{ route('pay.donate') }}" class="dropdown-link"><i class="fa fa-hand-holding-heart fa-sm me-2"></i> Donate</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleDropdown(this)">Resources <i class="fa fa-chevron-down fa-xs"></i></a>
                <ul class="dropdown">
                    <li><a href="{{ route('downloads') }}" class="dropdown-link"><i class="fa fa-download fa-sm me-2"></i> Downloads</a></li>
                    <li><a href="{{ route('gallery') }}" class="dropdown-link"><i class="fa fa-images fa-sm me-2"></i> Gallery</a></li>
                    <li><a href="{{ route('faqs') }}" class="dropdown-link"><i class="fa fa-circle-question fa-sm me-2"></i> FAQs</a></li>
                    <li><a href="{{ route('e-resources') }}" class="dropdown-link"><i class="fa fa-laptop fa-sm me-2"></i> E-Resources</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="toggleDropdown(this)">Online Services <i class="fa fa-chevron-down fa-xs"></i></a>
                <ul class="dropdown">
                    <li><a href="{{ route('services.portal') }}" class="dropdown-link"><i class="fa fa-id-card fa-sm me-2"></i> Staff &amp; Student Portal</a></li>
                    <li><a href="{{ route('services.elearning') }}" class="dropdown-link"><i class="fa fa-book-open fa-sm me-2"></i> eLearning</a></li>
                    <li><a href="{{ route('services.elibrary') }}" class="dropdown-link"><i class="fa fa-book fa-sm me-2"></i> E-Library</a></li>
                    <li><a href="{{ route('services.research') }}" class="dropdown-link"><i class="fa fa-microscope fa-sm me-2"></i> Research Repository</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            </li>
        </ul>
    </div>
</nav>
