@extends('layouts.app')

@section('title', config('site.name') . ' - ' . config('site.tagline'))
@section('description', 'Lusaka South University College - A premier hub for industry-aligned higher education and skills training in Zambia.')

@section('styles')
<style>
.hero{background:url('{{ asset('images/lsuc site cover img.jpeg') }}') center/cover no-repeat;height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;color:#fff;margin-top:80px;position:relative}
.hero::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.45)}
.hero-content{position:relative;z-index:1;max-width:1200px;width:94%;padding:45px 30px;background:rgba(0,0,0,.45);backdrop-filter:blur(5px);border-radius:20px;border:1px solid rgba(255,255,255,.3);box-shadow:0 8px 32px rgba(0,0,0,.2)}
.hero h1{font-size:clamp(1.8rem,3.6vw,3.2rem);margin-bottom:20px;font-weight:700;text-shadow:2px 2px 4px rgba(0,0,0,.3);letter-spacing:-.5px;white-space:nowrap}
.hero .motto{font-size:1.8rem;color:var(--light-orange);font-style:italic;margin-bottom:25px}
.hero p{font-size:1.15rem;margin-bottom:35px;opacity:.9}
.hero-btns{display:flex;gap:20px;justify-content:center;flex-wrap:wrap}
.hero-btns a{display:inline-flex;align-items:center;justify-content:center;gap:12px}
.hero-btns a i{font-size:1.05rem}
.core-values{background:var(--gray-100)}
/* Value Cards with Background Images */
.value-card{position:relative;overflow:hidden;min-height:320px;padding:0;border:2px solid transparent;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.15);transition:transform .35s ease,box-shadow .35s ease,border-color .35s ease}
.value-card-bg{position:absolute;inset:0;background-size:cover;background-position:center;transition:transform .6s ease;z-index:0}
.value-card-overlay{position:absolute;inset:0;background:linear-gradient(160deg,rgba(30,90,58,.75) 0%,rgba(0,0,0,.65) 100%);transition:background .35s ease;z-index:1}
.value-card-content{position:relative;z-index:2;padding:36px 30px 32px;height:100%;display:flex;flex-direction:column}
.value-card .card-icon{background:rgba(255,255,255,.2);border:2px solid rgba(255,255,255,.45);backdrop-filter:blur(4px);margin-bottom:20px;color:#fff}
.value-card h3{color:#fff;font-size:1.5rem;margin-bottom:12px;text-shadow:0 2px 6px rgba(0,0,0,.4)}
.value-card p{color:rgba(255,255,255,.9);line-height:1.8;text-shadow:0 1px 4px rgba(0,0,0,.35);flex:1}
.value-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--primary-orange),var(--light-orange));z-index:3;transform:scaleX(0);transform-origin:left;transition:transform .35s ease}
.value-card:hover{transform:translateY(-10px);box-shadow:0 24px 48px rgba(0,0,0,.28);border-color:var(--primary-orange)}
.value-card:hover .value-card-bg{transform:scale(1.08)}
.value-card:hover .value-card-overlay{background:linear-gradient(160deg,rgba(30,90,58,.58) 0%,rgba(0,0,0,.52) 100%)}
.value-card:hover::after{transform:scaleX(1)}
.updates-section{background:#fff}
.campus-section{background:var(--gray-100)}
.gallery-section{background:#fff}
/* Stats Counter Section */
.stats-counter-section{background:linear-gradient(135deg,var(--dark-green) 0%,var(--primary-green) 100%);padding:45px 0;color:#fff;box-shadow:0 4px 20px rgba(0,0,0,.12);position:relative;z-index:10}
.stats-counter-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:25px}
.stat-counter-card{text-align:center;padding:25px 15px;border-radius:15px;background:rgba(255,255,255,.08);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.18);transition:transform .3s ease,background .3s ease,box-shadow .3s ease}
.stat-counter-card:hover{transform:translateY(-6px);background:rgba(255,255,255,.15);box-shadow:0 12px 28px rgba(0,0,0,.2)}
.stat-icon{font-size:2.2rem;color:var(--light-orange);margin-bottom:12px}
.stat-number-wrapper{font-size:2.8rem;font-weight:800;line-height:1.1;color:#fff;margin-bottom:8px}
.stat-suffix{color:var(--primary-orange);font-weight:800;margin-left:2px}
.stat-label{font-size:1.05rem;font-weight:600;color:rgba(255,255,255,.95);letter-spacing:.5px;text-transform:capitalize}
@media(max-width:992px){.stats-counter-grid{grid-template-columns:repeat(2,1fr);gap:20px}.hero h1{white-space:normal;font-size:2.4rem}}
@media(max-width:576px){.stats-counter-grid{grid-template-columns:1fr;gap:15px}.stat-number-wrapper{font-size:2.3rem}}
@media(max-width:768px){.hero h1{font-size:1.85rem;white-space:normal}.hero .motto{font-size:1.25rem}.hero-content{margin:15px;padding:25px 15px;width:calc(100% - 30px)}}
</style>
@endsection

@section('content')
{{-- Hero --}}
<section class="hero" id="home-hero">
    <div class="hero-content">
        <h1>{{ config('site.name') }}</h1>
        <div class="motto">{{ config('site.tagline') }}</div>
        <p>Empowering minds, shaping futures, and building tomorrow's leaders through excellence in education and innovative learning experiences.</p>
        <div class="hero-btns">
            <a href="{{ config('site.apply.undergraduate') }}" class="cta-button"><i class="fa fa-graduation-cap"></i> Apply Now</a>
            <a href="{{ route('schools') }}" class="cta-button-outline" style="background:rgba(255,255,255,.15);color:#fff;border-color:rgba(255,255,255,.6)"><i class="fa fa-book-open"></i> Explore Programs</a>
        </div>
    </div>
</section>

{{-- Data Summary / Stats Counter Section --}}
<section class="stats-counter-section">
    <div class="container">
        <div class="stats-counter-grid">
            @if(isset($counters) && $counters->isNotEmpty())
                @foreach($counters as $counter)
                <div class="stat-counter-card">
                    <div class="stat-icon"><i class="fa {{ $counter->icon }}"></i></div>
                    <div class="stat-number-wrapper">
                        <span class="stat-number" data-target="{{ $counter->number }}">{{ $counter->number }}</span>@if($counter->suffix)<span class="stat-suffix">{{ $counter->suffix }}</span>@endif
                    </div>
                    <div class="stat-label">{{ $counter->label }}</div>
                </div>
                @endforeach
            @else
                <div class="stat-counter-card">
                    <div class="stat-icon"><i class="fa fa-user-graduate"></i></div>
                    <div class="stat-number-wrapper">
                        <span class="stat-number" data-target="1000">1000</span><span class="stat-suffix">+</span>
                    </div>
                    <div class="stat-label">Enrolled Students</div>
                </div>
                <div class="stat-counter-card">
                    <div class="stat-icon"><i class="fa fa-book-open"></i></div>
                    <div class="stat-number-wrapper">
                        <span class="stat-number" data-target="50">50</span><span class="stat-suffix">+</span>
                    </div>
                    <div class="stat-label">Programs Offered</div>
                </div>
                <div class="stat-counter-card">
                    <div class="stat-icon"><i class="fa fa-university"></i></div>
                    <div class="stat-number-wrapper">
                        <span class="stat-number" data-target="5">5</span>
                    </div>
                    <div class="stat-label">Schools</div>
                </div>
                <div class="stat-counter-card">
                    <div class="stat-icon"><i class="fa fa-award"></i></div>
                    <div class="stat-number-wrapper">
                        <span class="stat-number" data-target="500">500</span><span class="stat-suffix">+</span>
                    </div>
                    <div class="stat-label">Graduated Students</div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Core Values --}}
<section class="section core-values">
    <div class="container">
        <div class="section-title">
            <h2>Our Core Values</h2>
            <p>The principles that guide our commitment to educational excellence and student success</p>
        </div>
        <div class="cards-grid">
            <!-- Excellence -->
            <div class="card value-card">
                <div class="value-card-bg" style="background-image: url('{{ asset('images/excellence.jpg') }}');"></div>
                <div class="value-card-overlay"></div>
                <div class="value-card-content">
                    <div class="card-icon"><i class="fa fa-bullseye"></i></div>
                    <h3>Excellence</h3>
                    <p>We are committed to maintaining the highest standards in education, research, and student services. Our programs are designed to challenge and inspire.</p>
                </div>
            </div>

            <!-- Integrity -->
            <div class="card value-card">
                <div class="value-card-bg" style="background-image: url('{{ asset('images/integrity.jpg') }}');"></div>
                <div class="value-card-overlay"></div>
                <div class="value-card-content">
                    <div class="card-icon"><i class="fa fa-handshake"></i></div>
                    <h3>Integrity</h3>
                    <p>We uphold honesty, transparency, and ethical conduct in all our dealings. We foster a culture of trust and accountability across our institution.</p>
                </div>
            </div>

            <!-- Innovation -->
            <div class="card value-card">
                <div class="value-card-bg" style="background-image: url('{{ asset('images/innovation.jpg') }}');"></div>
                <div class="value-card-overlay"></div>
                <div class="value-card-content">
                    <div class="card-icon"><i class="fa fa-lightbulb"></i></div>
                    <h3>Innovation</h3>
                    <p>We embrace new ideas and technologies to enhance learning experiences. Our curriculum stays current with industry trends and global developments.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Latest Updates --}}
<section class="section updates-section">
    <div class="container">
        <div class="section-title">
            <h2>Latest Updates</h2>
            <p>Stay informed with the latest news and upcoming events at {{ config('site.short_name') }}.</p>
        </div>
        <div class="updates-grid">
            @forelse($featuredUpdates as $event)
            <div class="update-card">
                <div class="update-image">
                    <i class="fa fa-newspaper fa-3x"></i>
                </div>
                <div class="update-content">
                    <div class="update-date">
                        <i class="fa fa-calendar me-1"></i>
                        {{ $event->date->format('F j, Y') }} &bull;
                        <span style="background:var(--primary-green);color:#fff;padding:2px 10px;border-radius:10px;font-size:.8rem;margin-left:5px">{{ $event->category }}</span>
                    </div>
                    <h3>{{ $event->title }}</h3>
                    <p>{{ Str::limit($event->short_description, 140) }}</p>
                    <a href="{{ route('news.show', $event) }}" class="cta-button-outline" style="margin-top:15px;display:inline-block">Read More <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
            @empty
            <div class="update-card">
                <div class="update-image"><i class="fa fa-calendar-check fa-3x"></i></div>
                <div class="update-content">
                    <div class="update-date"><i class="fa fa-calendar me-1"></i> 2026</div>
                    <h3>2026 Academic Year Now Open</h3>
                    <p>Applications for the 2026 academic year are now open. Apply for Undergraduate, Short Courses, or Corporate Training programs.</p>
                    <a href="{{ route('news') }}" class="cta-button-outline" style="margin-top:15px;display:inline-block">View All News</a>
                </div>
            </div>
            @endforelse
        </div>
        <div style="text-align:center;margin-top:40px">
            <a href="{{ route('news') }}" class="cta-button"><i class="fa fa-newspaper me-2"></i>View All News &amp; Events</a>
        </div>
    </div>
</section>

{{-- Campus Carousel --}}
<section class="section campus-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Campus</h2>
            <p>Experience world-class facilities designed to support your academic journey.</p>
        </div>
        <div class="campus-carousel" id="campusCarousel">
            <div class="carousel-image active" style="background-image:url('{{ asset('images/school3.jpeg') }}')">
                <div class="carousel-overlay">
                    <div class="carousel-title">State-of-the-Art Facilities</div>
                    <div class="carousel-description">Modern classrooms and labs equipped for 21st century learning</div>
                </div>
            </div>
            <div class="carousel-image" style="background-image:url('{{ asset('images/school1.jpeg') }}')">
                <div class="carousel-overlay">
                    <div class="carousel-title">Vibrant Campus Life</div>
                    <div class="carousel-description">A thriving community of students, faculty, and staff</div>
                </div>
            </div>
            <div class="carousel-image" style="background-image:url('{{ asset('images/school4.jpeg') }}')">
                <div class="carousel-overlay">
                    <div class="carousel-title">Inspiring Learning Environment</div>
                    <div class="carousel-description">Spaces designed to foster creativity and academic excellence</div>
                </div>
            </div>
            <div class="carousel-image" style="background-image:url('{{ asset('images/school2.jpeg') }}')">
                <div class="carousel-overlay">
                    <div class="carousel-title">Industry-Ready Programs</div>
                    <div class="carousel-description">Curricula aligned with real-world industry needs</div>
                </div>
            </div>
            <div class="carousel-indicators">
                <div class="indicator active"></div>
                <div class="indicator"></div>
                <div class="indicator"></div>
                <div class="indicator"></div>
            </div>
        </div>
    </div>
</section>

{{-- Gallery Preview --}}
<section class="section gallery-section">
    <div class="container">
        <div class="section-title">
            <h2>School Environment</h2>
            <p>Take a visual tour of our modern campus facilities and vibrant student life.</p>
        </div>
        <div class="gallery-grid">
            @php
            $galleryItems = [
                ['img'=>'computer_lab.jpg','label'=>'Computer Laboratory'],
                ['img'=>'scienc_lab.jpg','label'=>'Science Laboratory'],
                ['img'=>'library.jpg','label'=>'Library'],
                ['img'=>'auditorium.jpg','label'=>'Auditorium'],
                ['img'=>'sports_complex.jpg','label'=>'Sports Complex'],
                ['img'=>'cafteria.jpg','label'=>'Cafeteria'],
                ['img'=>'campus_grounds.jpg','label'=>'Campus Grounds'],
                ['img'=>'culture.jpg','label'=>'Cultural Events'],
            ];
            @endphp
            @foreach($galleryItems as $item)
            <div class="gallery-item" onclick="openImagePreview(this)" data-image="{{ asset('images/'.$item['img']) }}">
                <img src="{{ asset('images/'.$item['img']) }}" alt="{{ $item['label'] }}" loading="lazy">
                <div class="gallery-overlay"><h4>{{ $item['label'] }}</h4></div>
            </div>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:40px">
            <a href="{{ route('gallery') }}" class="cta-button"><i class="fa fa-images me-2"></i>View Full Gallery</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
// Campus Carousel
let currentSlide=0;
const slides=document.querySelectorAll('.carousel-image');
const indicators=document.querySelectorAll('.indicator');
function showSlide(i){
    slides.forEach(s=>s.classList.remove('active'));
    indicators.forEach(d=>d.classList.remove('active'));
    slides[i].classList.add('active');
    indicators[i].classList.add('active');
}
function nextSlide(){currentSlide=(currentSlide+1)%slides.length;showSlide(currentSlide);}
let carouselInterval=setInterval(nextSlide,3000);
indicators.forEach((d,i)=>d.addEventListener('click',()=>{
    currentSlide=i;showSlide(i);
    clearInterval(carouselInterval);carouselInterval=setInterval(nextSlide,3000);
}));
const carousel=document.getElementById('campusCarousel');
if(carousel){
    carousel.addEventListener('mouseenter',()=>clearInterval(carouselInterval));
    carousel.addEventListener('mouseleave',()=>{carouselInterval=setInterval(nextSlide,3000);});
}

// Animated Stat Counters
(function() {
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            let count = 0;
            const step = Math.max(1, Math.ceil(target / 45));
            const timer = setInterval(() => {
                count += step;
                if (count >= target) {
                    counter.innerText = target;
                    clearInterval(timer);
                } else {
                    counter.innerText = count;
                }
            }, 30);
        });
    }

    const statsSection = document.querySelector('.stats-counter-section');
    if (statsSection && 'IntersectionObserver' in window) {
        let hasAnimated = false;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    hasAnimated = true;
                    animateCounters();
                }
            });
        }, { threshold: 0.2 });
        observer.observe(statsSection);
    }
})();
</script>
@endsection
