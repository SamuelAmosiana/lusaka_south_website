@php
    // Same logo and banner picture as the main website (Admin > Home Page > Hero Banner)
    $logo = \App\Models\AboutPage::siteLogoUrl();
    try {
        $background = \App\Models\AboutPage::where('slug', 'home-hero')->first()?->image_url;
    } catch (\Throwable) {
        $background = null;
    }
    $background ??= asset('images/lsuc site cover img.jpeg');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>Admin Login - {{ config('site.short_name') }}</title>
    <link rel="icon" href="{{ $logo }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{--primary-orange:#ff8c00;--light-orange:#ffb347;--primary-green:#228B22;--dark-green:#1a6b1a;--gray-100:#f8f9fa;--gray-200:#e9ecef;--gray-600:#6c757d;--gray-800:#343a40}
        body{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:var(--gray-100);color:var(--gray-800);min-height:100vh}
        .login-wrap{display:grid;grid-template-columns:1.1fr 1fr;min-height:100vh}

        /* Brand panel */
        .brand{position:relative;background:url('{{ $background }}') center/cover no-repeat;color:#fff;display:flex;align-items:center;justify-content:center;padding:50px 40px;overflow:hidden}
        .brand::before{content:'';position:absolute;inset:0;background:linear-gradient(150deg,rgba(26,107,26,.93) 0%,rgba(34,139,34,.85) 55%,rgba(0,0,0,.6) 100%)}
        .brand::after{content:'';position:absolute;width:420px;height:420px;border-radius:50%;border:60px solid rgba(255,255,255,.05);right:-160px;bottom:-160px}
        .brand-inner{position:relative;z-index:1;text-align:center;max-width:440px}
        .brand-logo{width:150px;height:150px;border-radius:50%;background:#fff;padding:12px;margin:0 auto 28px;border:4px solid var(--primary-orange);box-shadow:0 12px 35px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center}
        .brand-logo img{width:100%;height:100%;object-fit:contain}
        .brand h1{font-size:2rem;line-height:1.25;margin-bottom:10px;text-shadow:0 2px 6px rgba(0,0,0,.3)}
        .brand .motto{color:var(--light-orange);font-style:italic;font-size:1.2rem;margin-bottom:24px}
        .brand p{opacity:.9;line-height:1.7;font-size:.98rem}
        .brand-features{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:28px}
        .brand-features span{background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.25);padding:7px 14px;border-radius:50px;font-size:.82rem;display:inline-flex;align-items:center;gap:7px}

        /* Form panel */
        .form-side{display:flex;align-items:center;justify-content:center;padding:40px 24px;background:#fff}
        .login-card{width:100%;max-width:410px}
        .login-card .mobile-logo{display:none}
        .badge{display:inline-flex;align-items:center;gap:7px;background:#e8f5e9;color:var(--primary-green);font-weight:700;font-size:.78rem;letter-spacing:.6px;text-transform:uppercase;padding:6px 13px;border-radius:50px;margin-bottom:16px}
        .login-card h2{font-size:1.9rem;color:var(--gray-800);margin-bottom:6px}
        .login-card .lead{color:var(--gray-600);margin-bottom:28px}
        .alert{padding:12px 15px;border-radius:10px;font-size:.9rem;margin-bottom:18px;display:flex;gap:10px;align-items:flex-start;line-height:1.5}
        .alert-error{background:#fdecea;color:#b71c1c;border:1px solid #f5c6cb}
        .alert-success{background:#e8f5e9;color:#1b5e20;border:1px solid #c3e6cb}
        .field{margin-bottom:18px}
        .field label{display:block;font-weight:600;font-size:.9rem;margin-bottom:7px}
        .input-wrap{position:relative;display:flex;align-items:center}
        .input-wrap>i{position:absolute;left:16px;color:var(--gray-600);pointer-events:none;transition:color .2s ease}
        .input-wrap input{width:100%;padding:13px 46px;border:2px solid var(--gray-200);border-radius:12px;font-size:1rem;font-family:inherit;transition:border-color .2s ease,box-shadow .2s ease;background:#fff}
        .input-wrap input:focus{outline:none;border-color:var(--primary-green);box-shadow:0 0 0 4px rgba(34,139,34,.12)}
        .input-wrap:focus-within>i{color:var(--primary-green)}
        .input-wrap.invalid input{border-color:#dc3545}
        .toggle-pass{position:absolute;right:8px;background:none;border:none;color:var(--gray-600);cursor:pointer;width:36px;height:36px;border-radius:8px}
        .toggle-pass:hover{background:var(--gray-100);color:var(--gray-800)}
        .field-error{color:#dc3545;font-size:.83rem;margin-top:6px;display:block}
        .row{display:flex;justify-content:space-between;align-items:center;gap:10px;margin:4px 0 24px;flex-wrap:wrap;font-size:.9rem}
        .remember{display:flex;align-items:center;gap:8px;cursor:pointer;color:var(--gray-600)}
        .remember input{width:17px;height:17px;accent-color:var(--primary-green)}
        .row a{color:var(--primary-green);text-decoration:none;font-weight:600}
        .row a:hover{color:var(--primary-orange);text-decoration:underline}
        .submit{width:100%;display:flex;align-items:center;justify-content:center;gap:10px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;border:none;border-radius:12px;padding:14px;font-size:1.05rem;font-weight:700;cursor:pointer;transition:transform .2s ease,box-shadow .2s ease;font-family:inherit}
        .submit:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(34,139,34,.35)}
        .submit:disabled{opacity:.8;cursor:wait;transform:none;box-shadow:none}
        .submit:focus-visible,.toggle-pass:focus-visible,.row a:focus-visible,.back:focus-visible{outline:3px solid var(--primary-orange);outline-offset:2px}
        .back{display:inline-flex;align-items:center;gap:8px;margin-top:26px;color:var(--gray-600);text-decoration:none;font-size:.9rem}
        .back:hover{color:var(--primary-green)}
        .secure-note{margin-top:28px;padding-top:18px;border-top:1px solid var(--gray-200);color:var(--gray-600);font-size:.8rem;display:flex;align-items:center;gap:8px}

        @media(max-width:900px){
            .login-wrap{grid-template-columns:1fr}
            .brand{display:none}
            .form-side{min-height:100vh;align-items:flex-start;padding-top:40px;background:linear-gradient(180deg,#e8f5e9 0,#fff 220px)}
            .login-card .mobile-logo{display:flex;width:96px;height:96px;border-radius:50%;background:#fff;padding:8px;border:3px solid var(--primary-orange);box-shadow:0 8px 22px rgba(0,0,0,.15);margin:0 auto 20px;align-items:center;justify-content:center}
            .login-card .mobile-logo img{width:100%;height:100%;object-fit:contain}
            .login-card .mobile-title{display:block;text-align:center;color:var(--primary-green);font-weight:700;margin-bottom:22px}
            .login-card h2{font-size:1.6rem}
        }
        @media(min-width:901px){.mobile-title{display:none}}
        @media(prefers-reduced-motion:reduce){*{transition:none!important}}
    </style>
</head>
<body>
<div class="login-wrap">
    <aside class="brand" aria-hidden="true">
        <div class="brand-inner">
            <div class="brand-logo"><img src="{{ $logo }}" alt=""></div>
            <h1>{{ config('site.name') }}</h1>
            <div class="motto">{{ config('site.tagline') }}</div>
            <p>Manage the website's news, pages, programmes, gallery and payments from one secure place.</p>
            <div class="brand-features">
                <span><i class="fa fa-newspaper"></i> News &amp; Events</span>
                <span><i class="fa fa-house"></i> Home Page</span>
                <span><i class="fa fa-money-bill-wave"></i> Payments</span>
            </div>
        </div>
    </aside>

    <main class="form-side">
        <div class="login-card">
            <div class="mobile-logo"><img src="{{ $logo }}" alt="{{ config('site.short_name') }} logo"></div>
            <span class="mobile-title">{{ config('site.name') }}</span>

            <span class="badge"><i class="fa fa-shield-halved"></i> Admin Panel</span>
            <h2>Welcome back</h2>
            <p class="lead">Sign in to manage the {{ config('site.short_name') }} website.</p>

            @if (session('status'))
                <div class="alert alert-success" role="status"><i class="fa fa-circle-check"></i><span>{{ session('status') }}</span></div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error" role="alert"><i class="fa fa-triangle-exclamation"></i><span>{{ $errors->first() }}</span></div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <div class="input-wrap {{ $errors->has('email') ? 'invalid' : '' }}">
                        <i class="fa fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@lsc.edu.zm" @if($errors->has('email')) aria-invalid="true" @endif>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap {{ $errors->has('password') ? 'invalid' : '' }}">
                        <i class="fa fa-lock"></i>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" @if($errors->has('password')) aria-invalid="true" @endif>
                        <button type="button" class="toggle-pass" id="toggle-pass" aria-label="Show password" aria-pressed="false"><i class="fa fa-eye"></i></button>
                    </div>
                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="row">
                    <label class="remember" for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="submit" id="login-submit"><i class="fa fa-right-to-bracket"></i> <span>Log in</span></button>
            </form>

            <a href="{{ route('home') }}" class="back"><i class="fa fa-arrow-left"></i> Back to website</a>

            <div class="secure-note"><i class="fa fa-lock"></i> For authorised {{ config('site.short_name') }} staff only. Your sign-in is encrypted and attempts are limited.</div>
        </div>
    </main>
</div>

<script>
(() => {
    const pass = document.getElementById('password');
    const toggle = document.getElementById('toggle-pass');
    toggle.addEventListener('click', () => {
        const show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', show);
        toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        toggle.innerHTML = '<i class="fa ' + (show ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
        pass.focus();
    });

    // After a failed attempt, put the cursor in the password box
    @if ($errors->any() && old('email'))
    pass.focus();
    @endif

    const form = document.getElementById('login-form');
    const submit = document.getElementById('login-submit');
    form.addEventListener('submit', e => {
        if (!form.checkValidity()) { e.preventDefault(); form.reportValidity(); return; }
        submit.disabled = true;
        submit.innerHTML = '<i class="fa fa-spinner fa-spin"></i> <span>Signing in…</span>';
    });
})();
</script>
</body>
</html>
