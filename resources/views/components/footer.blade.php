<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><i class="fa fa-location-dot"></i> Contact Information</h3>
                <p><i class="fa fa-map-marker-alt fa-sm me-2"></i> {{ config('site.address') }}</p>
                <p><i class="fa fa-phone fa-sm me-2"></i> {{ config('site.phone') }}</p>
                <p><i class="fa fa-phone fa-sm me-2"></i> {{ config('site.phone2') }}</p>
                <p><i class="fa fa-envelope fa-sm me-2"></i> {{ config('site.admissions_email') }}</p>
            </div>
            <div class="footer-section">
                <h3><i class="fa fa-link"></i> Quick Links</h3>
                <ul>
                    <li><a href="{{ route('home') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>Home</a></li>
                    <li><a href="{{ route('about') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>About Us</a></li>
                    <li><a href="{{ route('schools') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>Programs</a></li>
                    <li><a href="{{ route('news') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>News &amp; Events</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><i class="fa fa-graduation-cap"></i> Student Resources</h3>
                <ul>
                    <li><a href="{{ route('services.portal') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>Student Portal</a></li>
                    <li><a href="{{ route('services.elearning') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>eLearning</a></li>
                    <li><a href="{{ route('services.elibrary') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>Digital Library</a></li>
                    <li><a href="{{ route('e-resources') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>E-Resources Portal</a></li>
                    <li><a href="{{ route('services.research') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>Research Repository</a></li>
                    <li><a href="{{ route('faqs') }}"><i class="fa fa-chevron-right fa-xs me-2"></i>FAQs</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><i class="fa fa-share-nodes"></i> Follow Us</h3>
                <p>Stay connected with {{ config('site.short_name') }} on social media for the latest updates and news.</p>
                <div class="social-links">
                    <a href="{{ config('site.facebook') }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ config('site.instagram') }}" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="{{ config('site.twitter') }}" target="_blank" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                    <a href="{{ config('site.linkedin') }}" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('site.name') }}. All rights reserved. | {{ config('site.tagline') }}</p>
        </div>
    </div>
</footer>
