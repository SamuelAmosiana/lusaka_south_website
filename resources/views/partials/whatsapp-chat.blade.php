{{-- Floating "Send us a message" button: opens a WhatsApp chat with config('site.whatsapp') --}}
@php $waNumber = preg_replace('/\D/', '', config('site.whatsapp')); @endphp
@if($waNumber)
<style>
.wa-widget{position:fixed;right:22px;bottom:22px;z-index:9000;font-family:inherit}
.wa-toggle{display:flex;align-items:center;gap:10px;background:#25D366;color:#fff;border:none;border-radius:50px;padding:0 22px 0 0;height:60px;box-shadow:0 6px 20px rgba(0,0,0,.25);cursor:pointer;font-size:1rem;font-weight:600;transition:transform .2s ease,box-shadow .2s ease}
.wa-toggle:hover{transform:translateY(-3px);box-shadow:0 10px 26px rgba(0,0,0,.3)}
.wa-toggle:focus-visible{outline:3px solid var(--primary-orange);outline-offset:3px}
.wa-toggle .wa-icon{width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0}
.wa-toggle .wa-pulse{position:absolute;left:0;top:0;width:60px;height:60px;border-radius:50%;background:#25D366;opacity:.5;animation:wa-pulse 2.2s ease-out infinite;z-index:-1}
@keyframes wa-pulse{0%{transform:scale(1);opacity:.5}100%{transform:scale(1.7);opacity:0}}
.wa-widget.open .wa-pulse{display:none}
.wa-card{position:absolute;right:0;bottom:76px;width:340px;max-width:calc(100vw - 32px);background:#fff;border-radius:16px;box-shadow:0 12px 40px rgba(0,0,0,.25);overflow:hidden;opacity:0;visibility:hidden;transform:translateY(12px) scale(.98);transform-origin:bottom right;transition:all .22s ease}
.wa-widget.open .wa-card{opacity:1;visibility:visible;transform:none}
.wa-card-header{background:#075E54;color:#fff;padding:16px 18px;display:flex;align-items:center;gap:12px}
.wa-card-header img{width:44px;height:44px;border-radius:50%;object-fit:contain;background:#fff;border:2px solid #fff;padding:3px}
.wa-card-header strong{display:block;font-size:1rem}
.wa-card-header span{font-size:.8rem;opacity:.85}
.wa-close{margin-left:auto;background:rgba(255,255,255,.15);border:none;color:#fff;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:1rem}
.wa-close:hover{background:rgba(255,255,255,.3)}
.wa-card-body{background:#ECE5DD;padding:18px}
.wa-bubble{background:#fff;border-radius:0 10px 10px 10px;padding:10px 14px;font-size:.92rem;color:#303030;line-height:1.5;box-shadow:0 1px 1px rgba(0,0,0,.1);max-width:90%}
.wa-card-form{padding:14px 16px 16px;display:flex;flex-direction:column;gap:10px;background:#fff}
.wa-card-form textarea{width:100%;border:2px solid var(--gray-200);border-radius:10px;padding:10px 12px;font-size:.95rem;font-family:inherit;resize:none;min-height:80px}
.wa-card-form textarea:focus{outline:none;border-color:#25D366}
.wa-start{display:flex;align-items:center;justify-content:center;gap:8px;background:#25D366;color:#fff;border:none;border-radius:50px;padding:12px;font-size:1rem;font-weight:700;cursor:pointer;text-decoration:none;transition:background .2s ease}
.wa-start:hover{background:#1ebe5a;color:#fff}
.wa-note{font-size:.75rem;color:var(--gray-600);text-align:center}
@media(max-width:768px){
    .wa-widget{right:16px;bottom:16px}
    .wa-toggle{padding:0;width:58px;height:58px}
    .wa-toggle .wa-icon{width:58px;height:58px}
    .wa-toggle .wa-label{display:none}
    .wa-card{bottom:72px}
}
@media(prefers-reduced-motion:reduce){.wa-toggle .wa-pulse{animation:none}}
@media print{.wa-widget{display:none}}
</style>

<div class="wa-widget" id="wa-widget">
    <div class="wa-card" id="wa-card" role="dialog" aria-modal="false" aria-labelledby="wa-card-title" aria-hidden="true">
        <div class="wa-card-header">
            <img src="{{ \App\Models\AboutPage::siteLogoUrl() }}" alt="">
            <div>
                <strong id="wa-card-title">{{ config('site.short_name') }} Admissions &amp; Enquiries</strong>
                <span>Typically replies within office hours</span>
            </div>
            <button type="button" class="wa-close" id="wa-close" aria-label="Close chat"><i class="fa fa-xmark"></i></button>
        </div>
        <div class="wa-card-body">
            <div class="wa-bubble">Hello! 👋 Welcome to {{ config('site.name') }}.<br>How can we help you today?</div>
        </div>
        <form class="wa-card-form" id="wa-form">
            <label for="wa-message" class="wa-note" style="text-align:left;font-weight:600;color:var(--gray-800);font-size:.85rem">Your message</label>
            <textarea id="wa-message" rows="3" maxlength="1000" placeholder="e.g. I'd like to know about the Diploma in Business Administration…"></textarea>
            <button type="submit" class="wa-start"><i class="fa-brands fa-whatsapp"></i> Start Chat</button>
            <div class="wa-note">Opens WhatsApp to chat with us on {{ config('site.whatsapp') }}</div>
        </form>
    </div>
    <button type="button" class="wa-toggle" id="wa-toggle" aria-controls="wa-card" aria-expanded="false" aria-label="Send us a message on WhatsApp">
        <span class="wa-icon"><span class="wa-pulse" aria-hidden="true"></span><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></span>
        <span class="wa-label">Send us a message</span>
    </button>
</div>

<script>
(() => {
    const number = @js($waNumber);
    const widget = document.getElementById('wa-widget');
    const card = document.getElementById('wa-card');
    const toggle = document.getElementById('wa-toggle');
    const message = document.getElementById('wa-message');
    const defaultText = 'Hello LSUC, I would like to make an enquiry.';

    function setOpen(open) {
        widget.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', open);
        card.setAttribute('aria-hidden', !open);
        if (open) setTimeout(() => message.focus(), 150);
    }

    toggle.addEventListener('click', () => setOpen(!widget.classList.contains('open')));
    document.getElementById('wa-close').addEventListener('click', () => { setOpen(false); toggle.focus(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && widget.classList.contains('open')) { setOpen(false); toggle.focus(); } });
    document.addEventListener('click', e => { if (widget.classList.contains('open') && !widget.contains(e.target)) setOpen(false); });

    document.getElementById('wa-form').addEventListener('submit', e => {
        e.preventDefault();
        const text = message.value.trim() || defaultText;
        // wa.me opens the WhatsApp app on phones and WhatsApp Web on computers
        window.open('https://wa.me/' + number + '?text=' + encodeURIComponent(text), '_blank', 'noopener');
        message.value = '';
        setOpen(false);
    });
    // Enter sends, Shift+Enter makes a new line
    message.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); document.getElementById('wa-form').requestSubmit(); }
    });
})();
</script>
@endif
