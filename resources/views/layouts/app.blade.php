<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('site.name') . ' - ' . config('site.tagline'))</title>
    <meta name="description" content="@yield('description', config('site.name') . ' - ' . config('site.tagline'))">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--primary-orange:#ff8c00;--primary-green:#228B22;--light-orange:#ffb347;--light-green:#90ee90;--dark-green:#1a6b1a;--white:#fff;--gray-100:#f8f9fa;--gray-200:#e9ecef;--gray-600:#6c757d;--gray-800:#343a40}
        body{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;line-height:1.6;color:var(--gray-800);background-color:var(--white)}
        .navbar{background:linear-gradient(135deg,var(--primary-green) 0%,var(--dark-green) 100%);padding:0;box-shadow:0 2px 10px rgba(0,0,0,.1);position:fixed;width:100%;top:0;z-index:1000}
        .nav-container{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;padding:0 20px}
        .logo-section{display:flex;align-items:center;padding:10px 0}
        .logo{width:50px;height:50px;margin-right:15px;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;border:2px solid var(--primary-orange)}
        .logo img{width:100%;height:100%;object-fit:cover}
        .logo-text{color:#fff}.logo-text h2{font-size:22px;font-weight:700}.logo-text p{font-size:12px;color:var(--light-orange)}
        .nav-menu{list-style:none;display:flex;margin-left:auto}
        .nav-item{position:relative}
        .nav-link{color:#fff;text-decoration:none;padding:20px 16px;display:block;transition:all .3s ease;font-weight:500;font-size:14px}
        .nav-link:hover{background-color:var(--primary-orange)}
        .dropdown{position:absolute;top:100%;left:0;background:#fff;min-width:230px;box-shadow:0 5px 20px rgba(0,0,0,.1);opacity:0;visibility:hidden;transform:translateY(-10px);transition:all .3s ease;border-radius:0 0 10px 10px}
        .nav-item:hover .dropdown{opacity:1;visibility:visible;transform:translateY(0)}
        .dropdown-link{color:var(--gray-800);padding:13px 20px;display:block;text-decoration:none;transition:all .3s ease;border-bottom:1px solid var(--gray-200);font-size:14px;white-space:nowrap}
        .dropdown-link:hover{background-color:var(--gray-100);color:var(--primary-green);padding-left:30px}
        .mobile-menu-toggle{display:none;flex-direction:column;cursor:pointer;padding:5px;z-index:1001}
        .mobile-menu-toggle span{width:25px;height:3px;background:#fff;margin:3px 0;transition:.3s;border-radius:2px}
        .mobile-menu-toggle.active span:nth-child(1){transform:rotate(-45deg) translate(-5px,6px)}
        .mobile-menu-toggle.active span:nth-child(2){opacity:0}
        .mobile-menu-toggle.active span:nth-child(3){transform:rotate(45deg) translate(-5px,-6px)}
        .container{max-width:1200px;margin:0 auto;padding:0 20px}
        .section{padding:80px 0}
        .section-title{text-align:center;margin-bottom:60px}
        .section-title h2{font-size:2.5rem;color:var(--primary-green);margin-bottom:20px;position:relative;display:inline-block}
        .section-title h2::after{content:'';position:absolute;bottom:-10px;left:50%;transform:translateX(-50%);width:80px;height:4px;background:var(--primary-orange);border-radius:2px}
        .section-title p{font-size:1.1rem;color:var(--gray-600);max-width:600px;margin:0 auto;margin-top:20px}
        .cards-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:30px;margin-top:50px}
        .card{background:#fff;border-radius:15px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,.1);transition:all .3s ease;border:2px solid transparent}
        .card:hover{transform:translateY(-10px);box-shadow:0 20px 40px rgba(0,0,0,.15);border-color:var(--primary-orange)}
        .card-icon{width:60px;height:60px;background:linear-gradient(135deg,var(--primary-orange),var(--light-orange));border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:20px;font-size:24px;color:#fff}
        .card h3{color:var(--primary-green);margin-bottom:15px;font-size:1.3rem}
        .card p{color:var(--gray-600);line-height:1.8}
        .updates-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:30px;margin-top:50px}
        .update-card{background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,.1);transition:all .3s ease}
        .update-card:hover{transform:translateY(-5px);box-shadow:0 15px 35px rgba(0,0,0,.15)}
        .update-image{height:200px;background:linear-gradient(135deg,var(--light-green),var(--primary-green));display:flex;align-items:center;justify-content:center;color:#fff;font-size:48px}
        .update-content{padding:25px}
        .update-date{color:var(--primary-orange);font-size:.9rem;font-weight:600;margin-bottom:10px}
        .update-card h3{color:var(--primary-green);margin-bottom:15px;font-size:1.2rem}
        .update-card p{color:var(--gray-600);line-height:1.7}
        .gallery-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;margin-top:50px}
        .gallery-item{height:200px;border-radius:15px;display:flex;align-items:center;justify-content:center;text-align:center;transition:all .3s ease;cursor:pointer;position:relative;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,.1)}
        .gallery-item img{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
        .gallery-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.8));color:#fff;padding:20px;transform:translateY(10px);opacity:0;transition:all .3s ease}
        .gallery-overlay h4{margin:0;font-size:1.1rem;font-weight:600}
        .gallery-item:hover{transform:scale(1.03);box-shadow:0 15px 30px rgba(0,0,0,.2)}
        .gallery-item:hover img{transform:scale(1.1)}
        .gallery-item:hover .gallery-overlay{opacity:1;transform:translateY(0)}
        .campus-carousel{width:100%;height:400px;position:relative;border-radius:20px;overflow:hidden;margin:40px 0;box-shadow:0 15px 35px rgba(0,0,0,.1)}
        .carousel-image{position:absolute;top:0;left:0;width:100%;height:100%;background-size:cover;background-position:center;opacity:0;transition:opacity 1s ease-in-out}
        .carousel-image.active{opacity:1}
        .carousel-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.7));color:#fff;padding:30px;text-align:center}
        .carousel-title{font-size:1.8rem;font-weight:600;margin-bottom:10px;text-shadow:2px 2px 4px rgba(0,0,0,.5)}
        .carousel-description{font-size:1.1rem;text-shadow:1px 1px 2px rgba(0,0,0,.5)}
        .carousel-indicators{position:absolute;bottom:15px;left:50%;transform:translateX(-50%);display:flex;gap:10px}
        .indicator{width:12px;height:12px;border-radius:50%;background:rgba(255,255,255,.5);cursor:pointer;transition:all .3s ease}
        .indicator.active{background:var(--primary-orange);transform:scale(1.2)}
        .footer{background:linear-gradient(135deg,var(--dark-green) 0%,var(--primary-green) 100%);color:#fff;padding:60px 0 30px}
        .footer-content{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:40px;margin-bottom:40px}
        .footer-section h3{color:var(--light-orange);margin-bottom:20px;font-size:1.2rem}
        .footer-section p,.footer-section li{line-height:1.8;margin-bottom:8px;font-size:.95rem}
        .footer-section ul{list-style:none}
        .footer-section a{color:#fff;text-decoration:none;transition:color .3s ease}
        .footer-section a:hover{color:var(--light-orange)}
        .footer-bottom{text-align:center;padding-top:30px;border-top:1px solid rgba(255,255,255,.2);font-size:.9rem}
        .social-links{display:flex;gap:15px;margin-top:15px}
        .social-links a{width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;transition:all .3s ease;text-decoration:none}
        .social-links a:hover{background:var(--primary-orange);transform:translateY(-3px)}
        .page-header{background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;padding:80px 0 40px;margin-top:80px;text-align:center}
        .page-header h1{font-size:2.5rem;font-weight:700;margin-bottom:10px}
        .page-header p{font-size:1.1rem;opacity:.85}
        .breadcrumb{background:var(--gray-100);padding:15px 0;border-bottom:1px solid var(--gray-200);font-size:.9rem}
        .breadcrumb a{color:var(--primary-green);text-decoration:none}
        .breadcrumb a:hover{color:var(--primary-orange)}
        .content-section{padding:60px 0}
        .cta-button{background:var(--primary-orange);color:#fff;padding:15px 40px;border:none;border-radius:50px;font-size:1.1rem;font-weight:600;text-decoration:none;display:inline-block;transition:all .3s ease;cursor:pointer}
        .cta-button:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(255,140,0,.4);background:var(--light-orange);color:#fff}
        .cta-button-outline{background:transparent;color:var(--primary-green);border:2px solid var(--primary-green);padding:12px 30px;border-radius:50px;font-size:1rem;font-weight:600;text-decoration:none;display:inline-block;transition:all .3s ease}
        .cta-button-outline:hover{background:var(--primary-green);color:#fff}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;margin-bottom:8px;color:var(--gray-800);font-weight:600;font-size:.95rem}
        .form-group input,.form-group select,.form-group textarea{width:100%;padding:12px 15px;border:2px solid var(--gray-200);border-radius:8px;font-size:1rem;transition:all .3s ease;font-family:inherit}
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--primary-green);box-shadow:0 0 0 3px rgba(34,139,34,.1)}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .form-section{background:var(--gray-100);padding:30px;border-radius:15px;margin-bottom:25px}
        .form-section h3{color:var(--primary-green);margin-bottom:20px;font-size:1.2rem;border-bottom:2px solid var(--primary-orange);padding-bottom:10px}
        .submit-btn{background:linear-gradient(135deg,var(--primary-orange),var(--light-orange));color:#fff;padding:15px 40px;border:none;border-radius:50px;font-size:1.1rem;font-weight:600;cursor:pointer;transition:all .3s ease;width:100%;margin-top:10px}
        .submit-btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(255,140,0,.4)}
        .alert-success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:20px 25px;border-radius:10px;margin-bottom:25px}
        .alert-error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:20px 25px;border-radius:10px;margin-bottom:25px}
        .image-preview-modal{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.9);display:none;align-items:center;justify-content:center;z-index:10000}
        .image-preview-content{position:relative;max-width:90%;max-height:90%;background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,.3)}
        .image-preview-content img{width:100%;height:auto;display:block;max-height:85vh;object-fit:contain}
        .image-preview-header{position:absolute;top:0;left:0;right:0;background:linear-gradient(rgba(0,0,0,.8),transparent);color:#fff;padding:20px;display:flex;justify-content:space-between;align-items:center}
        .image-preview-title{font-size:1.3rem;font-weight:600}
        .close-preview{background:rgba(255,255,255,.2);color:#fff;border:none;width:40px;height:40px;border-radius:50%;font-size:1.5rem;cursor:pointer;transition:all .3s ease;display:flex;align-items:center;justify-content:center}
        .close-preview:hover{background:rgba(255,255,255,.35);transform:scale(1.1)}
        .pdf-viewer-modal{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.9);display:none;align-items:center;justify-content:center;z-index:10001}
        .pdf-viewer-content{position:relative;width:98%;height:95%;max-width:1400px;max-height:95vh;background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,.3);display:flex;flex-direction:column;margin:auto}
        .pdf-viewer-header{background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;padding:20px 30px;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;border-bottom:3px solid var(--primary-orange)}
        .pdf-viewer-title{font-size:1.4rem;font-weight:600;margin:0}
        .pdf-viewer-controls{display:flex;gap:10px;align-items:center}
        .pdf-control-btn{background:rgba(255,255,255,.2);color:#fff;border:2px solid rgba(255,255,255,.3);padding:8px 16px;border-radius:8px;cursor:pointer;font-size:.9rem;font-weight:600;transition:all .3s ease;display:flex;align-items:center;gap:8px}
        .pdf-control-btn:hover{background:rgba(255,255,255,.35);transform:translateY(-2px)}
        .close-pdf-viewer{background:rgba(255,255,255,.2);color:#fff;border:none;width:40px;height:40px;border-radius:50%;font-size:1.5rem;cursor:pointer;transition:all .3s ease;display:flex;align-items:center;justify-content:center}
        .close-pdf-viewer:hover{background:rgba(255,255,255,.3);transform:scale(1.1)}
        .pdf-viewer-frame{flex:1;border:none;width:100%;height:100%;background:#f8f9fa;min-height:500px}
        .pdf-loading{display:flex;align-items:center;justify-content:center;flex:1;font-size:1.2rem;color:var(--gray-600);gap:15px}
        .pdf-error{display:flex;flex-direction:column;align-items:center;justify-content:center;flex:1;padding:40px;text-align:center}
        .staff-image-container{width:120px;height:120px;border-radius:50%;overflow:hidden;margin:0 auto 20px;border:3px solid var(--primary-orange);box-shadow:0 5px 15px rgba(0,0,0,.1)}
        .staff-image{width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
        .card:hover .staff-image{transform:scale(1.05)}
        .form-info{background:linear-gradient(135deg,var(--gray-100),#fff);border:2px solid var(--primary-green);border-radius:15px;padding:25px;margin-bottom:30px}
        .form-info h4{color:var(--primary-green);margin-bottom:15px}
        .form-info ul{padding-left:20px;color:var(--gray-600);line-height:1.9}
        .faq-header{display:flex;align-items:center;gap:10px;margin-bottom:15px}
        .faq-number{width:35px;height:35px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;flex-shrink:0}
        .faq-category-badge{background:var(--gray-100);color:var(--gray-600);padding:4px 12px;border-radius:15px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px}
        .faq-answer{color:var(--gray-800);line-height:1.8;margin-top:10px}
        @keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
        .animate-in{animation:fadeInUp .6s ease forwards}
        @keyframes fadeIn{from{opacity:0}to{opacity:1}}
        @keyframes zoomIn{from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1}}
        @media(max-width:768px){
            .mobile-menu-toggle{display:flex}
            .nav-menu{position:fixed;top:80px;left:0;width:100%;background:var(--dark-green);flex-direction:column;margin-left:0;transform:translateX(-100%);transition:transform .3s ease;box-shadow:0 2px 10px rgba(0,0,0,.1);max-height:calc(100vh - 80px);overflow-y:auto}
            .nav-menu.mobile-open{transform:translateX(0)}
            .nav-item{width:100%}
            .nav-link{padding:15px 20px;border-bottom:1px solid rgba(255,255,255,.1)}
            .dropdown{position:static;opacity:1;visibility:visible;transform:none;box-shadow:none;background:var(--primary-green);border-radius:0;max-height:0;overflow:hidden;transition:max-height .3s ease}
            .nav-item.dropdown-open .dropdown{max-height:300px}
            .dropdown-link{color:#fff;padding:12px 40px;border-bottom:1px solid rgba(255,255,255,.1);white-space:normal}
            .dropdown-link:hover{background:rgba(255,255,255,.1);color:#fff;padding-left:50px}
            .cards-grid,.updates-grid{grid-template-columns:1fr}
            .form-row{grid-template-columns:1fr}
            .page-header h1{font-size:1.8rem}
            .campus-carousel{height:250px}
            .gallery-grid{grid-template-columns:repeat(2,1fr);gap:15px}
            .gallery-item{height:120px}
            .footer-content{grid-template-columns:1fr}
        }
        @media(max-width:480px){.gallery-grid{grid-template-columns:1fr}.section{padding:50px 0}}
        @media print{.navbar,.footer{display:none}.page-header{margin-top:0}}
    </style>
    @yield('styles')
</head>
<body>

{{-- Navbar --}}
@include('components.navbar')

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
@include('components.footer')

{{-- Modals --}}
@include('components.image-preview-modal')
@include('components.pdf-modal')

<script>
// Mobile Menu
function toggleMobileMenu(){
    const m=document.querySelector('.nav-menu'),t=document.querySelector('.mobile-menu-toggle');
    m.classList.toggle('mobile-open');t.classList.toggle('active');
}
function closeMobileMenu(){
    document.querySelector('.nav-menu').classList.remove('mobile-open');
    document.querySelector('.mobile-menu-toggle').classList.remove('active');
}
function toggleDropdown(el){
    if(window.innerWidth<=768) el.parentElement.classList.toggle('dropdown-open');
}
document.addEventListener('click',function(e){
    const nb=document.querySelector('.navbar');
    const nm=document.querySelector('.nav-menu');
    if(nm && !nb.contains(e.target) && nm.classList.contains('mobile-open')) closeMobileMenu();
});
window.addEventListener('resize',function(){if(window.innerWidth>768) closeMobileMenu();});

// Gallery image preview
function openImagePreview(el){
    const src=el.getAttribute('data-image');
    const titleEl=el.querySelector('.gallery-overlay h4');
    const title=titleEl?titleEl.textContent:'Campus';
    document.getElementById('previewImage').src=src;
    document.getElementById('previewTitle').textContent=title;
    document.getElementById('imagePreviewModal').style.display='flex';
    document.body.style.overflow='hidden';
}
function closeImagePreview(e){
    if(!e||e.target.id==='imagePreviewModal'||e.target.classList.contains('close-preview')){
        document.getElementById('imagePreviewModal').style.display='none';
        document.body.style.overflow='auto';
    }
}

// PDF Viewer
let currentPDFUrl='',currentPDFTitle='';
function openPDFViewer(url,title){
    currentPDFUrl=url;currentPDFTitle=title||'Document';
    const m=document.getElementById('pdfViewerModal');
    document.getElementById('pdfViewerTitle').textContent=currentPDFTitle;
    document.getElementById('pdfLoading').style.display='flex';
    document.getElementById('pdfError').style.display='none';
    document.getElementById('pdfViewerFrame').style.display='none';
    m.style.display='flex';document.body.style.overflow='hidden';
    setTimeout(()=>{
        const f=document.getElementById('pdfViewerFrame');
        f.src=url;
        f.onload=()=>{document.getElementById('pdfLoading').style.display='none';f.style.display='block';};
        f.onerror=()=>{document.getElementById('pdfLoading').style.display='none';document.getElementById('pdfError').style.display='flex';};
        setTimeout(()=>{if(document.getElementById('pdfLoading').style.display!=='none'){document.getElementById('pdfLoading').style.display='none';f.style.display='block';}},3000);
    },400);
}
function closePDFViewer(e){
    if(!e||e.target.id==='pdfViewerModal'||e.target.classList.contains('close-pdf-viewer')){
        document.getElementById('pdfViewerModal').style.display='none';
        document.getElementById('pdfViewerFrame').src='';
        document.body.style.overflow='auto';
    }
}
function downloadPDF(){if(currentPDFUrl){const a=document.createElement('a');a.href=currentPDFUrl;a.download=currentPDFTitle.replace(/[^a-z0-9]/gi,'_').toLowerCase()+'.pdf';document.body.appendChild(a);a.click();document.body.removeChild(a);}}
function openPDFNewTab(){if(currentPDFUrl) window.open(currentPDFUrl,'_blank');}

// Keyboard shortcuts
document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){closeImagePreview();closePDFViewer();}
});

// Scroll animations
function animateOnScroll(){
    document.querySelectorAll('.card,.update-card').forEach(el=>{
        if(el.getBoundingClientRect().top<window.innerHeight-100) el.classList.add('animate-in');
    });
}
window.addEventListener('scroll',animateOnScroll);
animateOnScroll();
</script>
@yield('scripts')
</body>
</html>
