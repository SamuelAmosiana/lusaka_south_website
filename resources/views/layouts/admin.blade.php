<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - LSUC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--primary-orange:#ff8c00;--primary-green:#228B22;--dark-green:#1a6b1a;--sidebar-w:260px;--gray-100:#f8f9fa;--gray-200:#e9ecef;--gray-600:#6c757d;--gray-800:#343a40}
        body{font-family:'Segoe UI',sans-serif;background:#f0f2f5;color:var(--gray-800)}
        .admin-wrapper{display:flex;min-height:100vh}
        .sidebar{width:var(--sidebar-w);background:linear-gradient(180deg,var(--dark-green),var(--primary-green));color:#fff;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:100;transition:transform .3s ease}
        .sidebar-brand{padding:25px 20px;border-bottom:1px solid rgba(255,255,255,.15);display:flex;align-items:center;gap:12px}
        .sidebar-brand img{width:48px;height:48px;border-radius:50%;border:2px solid var(--primary-orange);object-fit:contain;background:#fff;padding:3px;flex-shrink:0}
        .sidebar-brand div h3{font-size:1rem;font-weight:700}
        .sidebar-brand div p{font-size:.75rem;opacity:.75}
        .sidebar-nav{padding:15px 0}
        .sidebar-section{padding:10px 20px 5px;font-size:.7rem;text-transform:uppercase;letter-spacing:1.5px;opacity:.6;font-weight:600}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:12px 20px;color:#fff;text-decoration:none;transition:all .2s ease;font-size:.9rem}
        .sidebar-link:hover,.sidebar-link.active{background:rgba(255,255,255,.15);padding-left:28px;color:var(--primary-orange)}
        .sidebar-link i{width:18px;text-align:center}
        .main-content{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column}
        .topbar{background:#fff;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 10px rgba(0,0,0,.08);position:sticky;top:0;z-index:50}
        .topbar-left h1{font-size:1.3rem;color:var(--gray-800)}
        .topbar-right{display:flex;align-items:center;gap:15px}
        .admin-badge{background:var(--primary-orange);color:#fff;padding:4px 12px;border-radius:20px;font-size:.8rem;font-weight:600}
        .page-body{padding:30px;flex:1}
        .alert-success{background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:15px 20px;border-radius:8px;margin-bottom:20px}
        .alert-error{background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:15px 20px;border-radius:8px;margin-bottom:20px}
        .stat-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:20px;margin-bottom:30px}
        .stat-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,.06);border-left:4px solid var(--primary-orange)}
        .stat-card h2{font-size:2rem;color:var(--gray-800);font-weight:700}
        .stat-card p{color:var(--gray-600);font-size:.9rem;margin-top:5px}
        .stat-card i{font-size:1.5rem;color:var(--primary-orange);margin-bottom:10px}
        .card{background:#fff;border-radius:12px;padding:25px;box-shadow:0 2px 10px rgba(0,0,0,.06);margin-bottom:20px}
        .card-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:15px;border-bottom:2px solid var(--gray-200)}
        .card-header h3{color:var(--primary-green);font-size:1.1rem}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;font-size:.9rem;font-weight:600;cursor:pointer;text-decoration:none;border:none;transition:all .2s ease}
        .btn-primary{background:var(--primary-green);color:#fff}.btn-primary:hover{background:var(--dark-green)}
        .btn-orange{background:var(--primary-orange);color:#fff}.btn-orange:hover{background:#e67e00}
        .btn-danger{background:#dc3545;color:#fff}.btn-danger:hover{background:#b02a37}
        .btn-outline{background:transparent;color:var(--primary-green);border:2px solid var(--primary-green)}.btn-outline:hover{background:var(--primary-green);color:#fff}
        .btn-sm{padding:6px 12px;font-size:.82rem}
        table{width:100%;border-collapse:collapse}
        th{background:var(--gray-100);padding:12px 15px;text-align:left;font-size:.85rem;color:var(--gray-600);text-transform:uppercase;letter-spacing:.5px}
        td{padding:12px 15px;border-bottom:1px solid var(--gray-200);font-size:.9rem}
        tr:hover td{background:#fafafa}
        .badge{padding:3px 10px;border-radius:12px;font-size:.78rem;font-weight:600}
        .badge-green{background:#e8f5e9;color:#2e7d32}
        .badge-orange{background:#fff3e0;color:#e65100}
        .badge-blue{background:#e3f2fd;color:#1565c0}
        .badge-red{background:#fce4ec;color:#b71c1c}
        .form-group{margin-bottom:18px}
        .form-group label{display:block;margin-bottom:6px;font-weight:600;font-size:.9rem;color:var(--gray-800)}
        .form-control{width:100%;padding:10px 14px;border:2px solid var(--gray-200);border-radius:8px;font-size:.95rem;transition:all .2s ease;font-family:inherit}
        .form-control:focus{outline:none;border-color:var(--primary-green);box-shadow:0 0 0 3px rgba(34,139,34,.1)}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
        @media(max-width:900px){.sidebar{transform:translateX(-100%)}.main-content{margin-left:0}.form-row{grid-template-columns:1fr}}
        @media print{.sidebar,.topbar{display:none}.main-content{margin-left:0}}
        .logout-modal{position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;z-index:10000;padding:16px;animation:logout-fade .15s ease}
        .logout-modal[hidden]{display:none}
        .logout-modal-box{background:#fff;border-radius:14px;padding:28px 26px 24px;max-width:400px;width:100%;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.3)}
        .logout-modal-icon{width:58px;height:58px;border-radius:50%;background:#fce4ec;color:#dc3545;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto 14px}
        .logout-modal-box h3{color:var(--gray-800);font-size:1.25rem;margin-bottom:8px}
        .logout-modal-box p{color:var(--gray-600);font-size:.92rem;line-height:1.6;margin-bottom:22px}
        .logout-modal-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
        .logout-modal-actions .btn{min-width:130px;justify-content:center}
        @keyframes logout-fade{from{opacity:0}to{opacity:1}}
    </style>
    @yield('styles')
</head>
<body>
<div class="admin-wrapper">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ \App\Models\AboutPage::siteLogoUrl() }}" alt="LSUC Logo">
            <div><h3>LSUC Admin</h3><p>Management Panel</p></div>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-section">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard')?'active':'' }}"><i class="fa fa-gauge-high"></i> Dashboard</a>
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank"><i class="fa fa-globe"></i> View Website</a>
            <div class="sidebar-section">Content</div>
            <a href="{{ route('admin.events.index') }}" class="sidebar-link {{ request()->routeIs('admin.events*')?'active':'' }}"><i class="fa fa-newspaper"></i> News &amp; Events</a>
            <a href="{{ route('admin.events.create') }}" class="sidebar-link" style="padding-left:36px;font-size:.85rem"><i class="fa fa-plus fa-sm"></i> Add News</a>
            <a href="{{ route('admin.faqs.index') }}" class="sidebar-link {{ request()->routeIs('admin.faqs*')?'active':'' }}"><i class="fa fa-circle-question"></i> FAQs</a>
            <a href="{{ route('admin.downloads.index') }}" class="sidebar-link {{ request()->routeIs('admin.downloads*')?'active':'' }}"><i class="fa fa-download"></i> Downloads</a>
            @php
                // Which content page/section is being edited, so the right menu item lights up
                $contentSlug = request()->routeIs('admin.about*')
                    ? (optional(request()->route('page'))->slug ?? optional(request()->route('item'))->page)
                    : null;
                $contentArea = \App\Models\AboutPage::PAGES[$contentSlug]['area'] ?? null;
                $onHomePage = request()->routeIs('admin.home*', 'admin.counters*', 'admin.learning*') || $contentArea === 'home';
            @endphp
            <a href="{{ route('admin.home.index') }}" class="sidebar-link {{ $onHomePage ?'active':'' }}"><i class="fa fa-house"></i> Home Page</a>
            <a href="{{ route('admin.about.index') }}" class="sidebar-link {{ request()->routeIs('admin.about.index') || $contentArea === 'about' ?'active':'' }}"><i class="fa fa-building-columns"></i> About Pages</a>
            <a href="{{ route('admin.schools.index') }}" class="sidebar-link {{ request()->routeIs('admin.schools*')?'active':'' }}"><i class="fa fa-school"></i> Schools &amp; Programmes</a>
            <a href="{{ route('admin.gallery.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery*')?'active':'' }}"><i class="fa fa-images"></i> Gallery</a>
            <a href="{{ route('admin.about.edit', 'e-resources') }}" class="sidebar-link {{ $contentSlug === 'e-resources' ?'active':'' }}"><i class="fa fa-book-bookmark"></i> E-Resources</a>
            <a href="{{ route('admin.about.edit', 'contact') }}" class="sidebar-link {{ $contentSlug === 'contact' ?'active':'' }}"><i class="fa fa-address-book"></i> Contact Page</a>
            <div class="sidebar-section">Finance</div>
            <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments*')?'active':'' }}"><i class="fa fa-money-bill-wave"></i> Payments
                @php $pendingPayments = \App\Models\Payment::where('status','pending')->count(); @endphp
                @if($pendingPayments)<span class="badge badge-orange" style="margin-left:auto">{{ $pendingPayments }}</span>@endif
            </a>
            <div class="sidebar-section">Account</div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="sidebar-link" style="width:100%;background:none;border:none;cursor:pointer;text-align:left"><i class="fa fa-right-from-bracket"></i> Logout</button>
            </form>
        </nav>
    </aside>
    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left"><h1>@yield('page-title','Admin Panel')</h1></div>
            <div class="topbar-right">
                <span class="admin-badge"><i class="fa fa-shield-halved me-1"></i>{{ auth()->user()->username ?? auth()->user()->name }}</span>
            </div>
        </div>
        <div class="page-body">
            @if(session('success'))<div class="alert-success"><i class="fa fa-circle-check me-2"></i>{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert-error"><i class="fa fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>@endif
            @yield('content')
        </div>
    </div>
</div>
{{-- Logout confirmation --}}
<div class="logout-modal" id="logout-modal" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title" aria-describedby="logout-modal-text" hidden>
    <div class="logout-modal-box">
        <div class="logout-modal-icon"><i class="fa fa-right-from-bracket"></i></div>
        <h3 id="logout-modal-title">Log out?</h3>
        <p id="logout-modal-text">Are you sure you want to log out of the admin panel? Any changes you haven't saved will be lost.</p>
        <div class="logout-modal-actions">
            <button type="button" class="btn btn-outline" id="logout-cancel">Cancel</button>
            <button type="button" class="btn btn-danger" id="logout-confirm"><i class="fa fa-right-from-bracket"></i> Yes, log out</button>
        </div>
    </div>
</div>
<script>
(() => {
    const form = document.getElementById('logout-form');
    const modal = document.getElementById('logout-modal');
    const cancel = document.getElementById('logout-cancel');
    const confirmBtn = document.getElementById('logout-confirm');
    if (!form || !modal) return;
    let opener = null;

    function close() {
        modal.hidden = true;
        document.removeEventListener('keydown', onKey);
        if (opener) opener.focus();
    }
    function onKey(e) {
        if (e.key === 'Escape') close();
        // Keep keyboard focus inside the box while it's open
        if (e.key === 'Tab') {
            const first = cancel, last = confirmBtn;
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
    }

    // Ask first; only "Yes, log out" actually submits the logout form
    form.addEventListener('submit', e => {
        if (form.dataset.confirmed === '1') return;
        e.preventDefault();
        opener = document.activeElement;
        modal.hidden = false;
        document.addEventListener('keydown', onKey);
        cancel.focus();
    });
    cancel.addEventListener('click', close);
    modal.addEventListener('click', e => { if (e.target === modal) close(); });
    confirmBtn.addEventListener('click', () => {
        form.dataset.confirmed = '1';
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Logging out…';
        form.submit();
    });
})();
</script>
@yield('scripts')
</body>
</html>
