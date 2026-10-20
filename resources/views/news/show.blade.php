@extends('layouts.app')
@section('title', $event->title . ' - ' . config('site.name'))
@section('styles')
<style>
.event-layout{display:grid;grid-template-columns:2fr 1fr;gap:40px;margin-top:30px}
.detail-meta{background:var(--gray-100);padding:20px;border-radius:10px;margin-bottom:25px;border-left:4px solid var(--primary-orange)}
.detail-meta p{margin-bottom:8px;color:var(--gray-600)}
.detail-full{font-size:1.05rem;line-height:1.85;color:var(--gray-800)}
.detail-full h3{color:var(--primary-green);margin:25px 0 15px}
.detail-full ul,.detail-full ol{margin:15px 0;padding-left:30px}
.detail-full li{margin-bottom:8px}
.sidebar-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 5px 20px rgba(0,0,0,.08);margin-bottom:20px}
.sidebar-card h4{color:var(--primary-green);margin-bottom:15px;padding-bottom:10px;border-bottom:2px solid var(--primary-orange)}
.related-item{padding:10px 0;border-bottom:1px solid var(--gray-200);display:flex;gap:10px;align-items:flex-start}
.related-item:last-child{border-bottom:none}
.related-item a{color:var(--gray-800);text-decoration:none;font-size:.9rem;line-height:1.4}
.related-item a:hover{color:var(--primary-orange)}
.related-date{font-size:.8rem;color:var(--gray-600)}

/* Event Cover Banner */
.event-cover-banner{width:100%;max-height:440px;border-radius:15px;overflow:hidden;margin-bottom:25px;box-shadow:0 8px 25px rgba(0,0,0,.08)}
.event-cover-banner img{width:100%;height:100%;max-height:440px;object-fit:cover;display:block}

/* Event Gallery & Captions */
.event-gallery-section{margin-top:40px;padding-top:30px;border-top:2px solid var(--gray-200)}
.event-gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:20px;margin-top:20px}
.gallery-photo-card{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 15px rgba(0,0,0,.07);border:1px solid var(--gray-200);cursor:pointer;transition:all .3s ease;display:flex;flex-direction:column}
.gallery-photo-card:hover{transform:translateY(-4px);box-shadow:0 12px 25px rgba(0,0,0,.13);border-color:var(--primary-orange)}
.gallery-photo-thumb{height:180px;position:relative;overflow:hidden;background:#f1f5f9}
.gallery-photo-thumb img{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
.gallery-photo-card:hover .gallery-photo-thumb img{transform:scale(1.08)}
.gallery-photo-overlay{position:absolute;inset:0;background:rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;opacity:0;transition:opacity .2s ease}
.gallery-photo-card:hover .gallery-photo-overlay{opacity:1}
.gallery-photo-caption{padding:12px 14px;background:#fff;flex:1;display:flex;align-items:center}
.gallery-photo-caption p{margin:0;font-size:.88rem;color:var(--gray-800);line-height:1.45;font-weight:500}

/* Lightbox Modal */
.photo-modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:9999;align-items:center;justify-content:center;padding:20px;backdrop-filter:blur(4px)}
.photo-modal-backdrop.active{display:flex}
.photo-modal-content{max-width:900px;width:100%;background:#1e293b;border-radius:14px;overflow:hidden;box-shadow:0 25px 50px rgba(0,0,0,.5);position:relative;animation:modalZoomIn .25s ease}
@keyframes modalZoomIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.photo-modal-close{position:absolute;top:15px;right:15px;width:38px;height:38px;border-radius:50%;background:rgba(0,0,0,.6);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:18px;z-index:10;transition:background .2s}
.photo-modal-close:hover{background:var(--primary-orange)}
.photo-modal-image-wrapper{max-height:75vh;display:flex;align-items:center;justify-content:center;background:#0f172a}
.photo-modal-image-wrapper img{max-width:100%;max-height:75vh;object-fit:contain;display:block}
.photo-modal-caption{padding:16px 20px;color:#f8fafc;font-size:1rem;background:#1e293b;border-top:1px solid #334155}

@media(max-width:768px){
    .event-layout{grid-template-columns:1fr}
    .event-gallery-grid{grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px}
    .gallery-photo-thumb{height:140px}
}
</style>
@endsection
@section('content')
<div class="page-header"><div class="container">
    <h1>{{ Str::limit($event->title, 60) }}</h1>
    <p>{{ $event->category }} &bull; {{ $event->date->format('F j, Y') }}</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; <a href="{{ route('news') }}">News &amp; Events</a> &rsaquo; {{ Str::limit($event->title,40) }}
</div></div>
<section class="content-section"><div class="container">
    <div class="event-layout">
        <div>
            {{-- Prominent Cover Banner --}}
            @if(!empty($event->image) && file_exists(public_path($event->image)))
            <div class="event-cover-banner">
                <img src="{{ asset($event->image) }}" alt="{{ $event->title }}">
            </div>
            @endif

            <div class="detail-meta">
                <p><i class="fa fa-calendar me-2" style="color:var(--primary-orange)"></i><strong>Date:</strong> {{ $event->date->format('l, F j, Y') }}</p>
                <p><i class="fa fa-tag me-2" style="color:var(--primary-orange)"></i><strong>Category:</strong> {{ $event->category }}</p>
                <p><i class="fa fa-user me-2" style="color:var(--primary-orange)"></i><strong>Author:</strong> {{ $event->author }}</p>
            </div>

            <div class="detail-full">
                {!! $event->full_description !!}
            </div>

            {{-- Extra Event Photos Gallery Section --}}
            @if($event->images->isNotEmpty())
            <div class="event-gallery-section">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                    <div style="width:42px;height:42px;border-radius:10px;background:rgba(255,140,0,.15);display:flex;align-items:center;justify-content:center;color:var(--primary-orange)">
                        <i class="fa fa-camera-retro fa-lg"></i>
                    </div>
                    <div>
                        <h3 style="color:var(--primary-green);margin:0;font-size:1.45rem">Event Photo Highlights</h3>
                        <p style="color:var(--gray-600);margin:0;font-size:.9rem">Explore moments captured during this event ({{ $event->images->count() }} {{ Str::plural('photo', $event->images->count()) }}).</p>
                    </div>
                </div>

                <div class="event-gallery-grid">
                    @foreach($event->images as $photo)
                    <div class="gallery-photo-card" onclick="openPhotoModal('{{ asset($photo->image_path) }}', '{{ addslashes($photo->caption ?? '') }}')">
                        <div class="gallery-photo-thumb">
                            <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->caption ?? $event->title }}" loading="lazy">
                            <div class="gallery-photo-overlay">
                                <i class="fa fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                        @if(!empty($photo->caption))
                        <div class="gallery-photo-caption">
                            <p><i class="fa fa-quote-left fa-xs me-1" style="color:var(--primary-orange);opacity:.7"></i>{{ $photo->caption }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div style="margin-top:35px;padding-top:25px;border-top:2px solid var(--gray-200);display:flex;gap:12px;flex-wrap:wrap">
                <a href="{{ route('news') }}" class="cta-button-outline"><i class="fa fa-arrow-left me-2"></i>Back to News</a>
                <a href="{{ config('site.apply.undergraduate') }}" class="cta-button"><i class="fa fa-graduation-cap me-2"></i>Apply Now</a>
            </div>
        </div>

        <div>
            <div class="sidebar-card">
                <h4><i class="fa fa-calendar-days me-2"></i>Quick Links</h4>
                <div style="display:flex;flex-direction:column;gap:8px">
                    <a href="{{ config('site.apply.undergraduate') }}" style="color:var(--primary-green);text-decoration:none;padding:8px 0;border-bottom:1px solid var(--gray-200)"><i class="fa fa-chevron-right fa-xs me-2"></i>Apply for Admission</a>
                    <a href="{{ route('downloads') }}" style="color:var(--primary-green);text-decoration:none;padding:8px 0;border-bottom:1px solid var(--gray-200)"><i class="fa fa-chevron-right fa-xs me-2"></i>Download Forms</a>
                    <a href="{{ route('contact') }}" style="color:var(--primary-green);text-decoration:none;padding:8px 0"><i class="fa fa-chevron-right fa-xs me-2"></i>Contact Us</a>
                </div>
            </div>

            @if($relatedEvents->isNotEmpty())
            <div class="sidebar-card">
                <h4><i class="fa fa-newspaper me-2"></i>Related News</h4>
                @foreach($relatedEvents as $related)
                <div class="related-item">
                    @if(!empty($related->image) && file_exists(public_path($related->image)))
                        <img src="{{ asset($related->image) }}" alt="" style="width:48px;height:40px;border-radius:6px;object-fit:cover;flex-shrink:0">
                    @else
                        <i class="fa fa-circle-dot fa-xs" style="color:var(--primary-orange);margin-top:4px;flex-shrink:0"></i>
                    @endif
                    <div>
                        <a href="{{ route('news.show',$related) }}">{{ $related->title }}</a>
                        <div class="related-date">{{ $related->date->format('M j, Y') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <div class="sidebar-card" style="background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff">
                <h4 style="color:var(--light-orange);border-bottom-color:var(--primary-orange)"><i class="fa fa-graduation-cap me-2"></i>Ready to Apply?</h4>
                <p style="font-size:.9rem;margin-bottom:15px;opacity:.9">Applications for the 2026 intake are now open. Secure your place today.</p>
                <a href="{{ config('site.apply.undergraduate') }}" style="background:var(--primary-orange);color:#fff;padding:10px 20px;border-radius:25px;text-decoration:none;font-weight:600;display:inline-block;font-size:.9rem"><i class="fa fa-graduation-cap me-1"></i>Apply Now</a>
            </div>
        </div>
    </div>
</div></section>

{{-- Photo Lightbox Modal --}}
<div id="photoModal" class="photo-modal-backdrop" onclick="closePhotoModal(event)">
    <div class="photo-modal-content" onclick="event.stopPropagation()">
        <button type="button" class="photo-modal-close" onclick="closePhotoModalDirect()">&times;</button>
        <div class="photo-modal-image-wrapper">
            <img id="photoModalImg" src="" alt="Event Photo">
        </div>
        <div id="photoModalCaption" class="photo-modal-caption"></div>
    </div>
</div>

<script>
function openPhotoModal(imgUrl, caption) {
    const modal = document.getElementById('photoModal');
    const modalImg = document.getElementById('photoModalImg');
    const modalCap = document.getElementById('photoModalCaption');
    
    modalImg.src = imgUrl;
    if (caption && caption.trim().length > 0) {
        modalCap.textContent = caption;
        modalCap.style.display = 'block';
    } else {
        modalCap.textContent = '';
        modalCap.style.display = 'none';
    }
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closePhotoModal(e) {
    if (e.target.id === 'photoModal') {
        closePhotoModalDirect();
    }
}

function closePhotoModalDirect() {
    const modal = document.getElementById('photoModal');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModalDirect();
    }
});
</script>
@endsection
