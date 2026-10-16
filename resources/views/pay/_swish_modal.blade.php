{{-- Swish payment modal. $category is 'Payment' or 'Donation'.
     On the donate page the purpose comes from #purpose-ref instead of a text field. --}}
@php $isDonation = $category === 'Donation'; @endphp
<div class="swish-overlay" id="swishOverlay" hidden>
    <div class="swish-modal" role="dialog" aria-modal="true" aria-labelledby="swishTitle">
        <div class="swish-header">
            <div class="swish-header-icon"><i class="fa fa-lock" id="swishHeaderIcon"></i></div>
            <div>
                <h2 id="swishTitle">Secure Payment</h2>
                <p>Powered by Swish &mdash; {{ config('site.name') }}</p>
            </div>
            <button type="button" class="swish-close" data-swish-close aria-label="Close"><i class="fa fa-xmark"></i></button>
        </div>

        {{-- Step 1: details --}}
        <form class="swish-step" id="swishForm" data-step="form" novalidate>
            <input type="hidden" name="method" value="card">
            <input type="hidden" name="category" value="{{ $category }}">

            @if($isDonation)
                <div class="swish-note"><i class="fa fa-hand-holding-heart"></i><span>Donating to: <strong id="swishPurposeLabel"></strong></span></div>
                <input type="hidden" name="purpose">
            @else
                <div class="swish-field">
                    <label for="swishPurpose">What is this payment for? *</label>
                    <input type="text" id="swishPurpose" name="purpose" maxlength="150" required placeholder="e.g. Tuition fees – Student No. 2024001">
                </div>
            @endif

            <div class="swish-field">
                <label for="swishName">Full name</label>
                <input type="text" id="swishName" name="name" maxlength="150" autocomplete="name" placeholder="{{ $isDonation ? 'Optional — for your donation receipt' : 'Name of student or payer' }}">
            </div>
            <div class="swish-field">
                <label for="swishAmount">Amount (ZMW) *</label>
                <input type="number" id="swishAmount" name="amount" min="1" max="1000000" step="0.01" required inputmode="decimal" placeholder="0.00">
            </div>
            <div class="swish-field">
                <label for="swishPhone" id="swishPhoneLabel">Mobile money number *</label>
                <input type="tel" id="swishPhone" name="phone" maxlength="20" required autocomplete="tel" placeholder="e.g. 0971234567">
                <small id="swishPhoneHint">MTN, Airtel or Zamtel number registered for mobile money.</small>
            </div>

            <fieldset class="swish-field swish-mobile-only">
                <legend>How would you like to authorise?</legend>
                <div class="swish-options">
                    <label><input type="radio" name="mobile_method" value="ussd" checked><span><i class="fa fa-mobile-screen"></i>USSD prompt</span></label>
                    <label><input type="radio" name="mobile_method" value="push"><span><i class="fa fa-bell"></i>Push notification</span></label>
                    <label><input type="radio" name="mobile_method" value="qr"><span><i class="fa fa-qrcode"></i>QR code</span></label>
                </div>
            </fieldset>

            <div class="swish-error" id="swishFormError" role="alert" hidden></div>
            <button type="submit" class="cta-button swish-submit" id="swishSubmit"><i class="fa fa-lock me-2"></i><span>Continue</span></button>
        </form>

        {{-- Step 2: waiting for authorisation --}}
        <div class="swish-step swish-center" data-step="waiting" hidden>
            <div class="swish-spinner" aria-hidden="true"></div>
            <h3 id="swishWaitTitle">Waiting for confirmation…</h3>
            <p id="swishWaitMsg"></p>
            <img id="swishQr" class="swish-qr" alt="Swish payment QR code" hidden>
            <p class="swish-ref">Reference: <strong data-swish-ref></strong></p>
            <p class="swish-countdown"><i class="fa fa-clock me-1"></i>Time remaining: <strong id="swishTimer">5:00</strong></p>
        </div>

        {{-- Step 3: results --}}
        <div class="swish-step swish-center" data-step="success" hidden>
            <div class="swish-result-icon success"><i class="fa fa-check"></i></div>
            <h3>{{ $isDonation ? 'Thank you for your donation!' : 'Payment successful!' }}</h3>
            <p>Your payment has been received and recorded. Please keep these details for your records.</p>
            <dl class="swish-receipt">
                <div data-receipt="transaction_id"><dt>Transaction ID</dt><dd></dd></div>
                <div><dt>Reference</dt><dd data-swish-ref></dd></div>
                <div data-receipt="amount"><dt>Amount</dt><dd></dd></div>
                <div data-receipt="paid_at"><dt>Date</dt><dd></dd></div>
            </dl>
            <button type="button" class="cta-button" data-swish-close>Done</button>
        </div>
        <div class="swish-step swish-center" data-step="awaiting" hidden>
            <div class="swish-result-icon awaiting"><i class="fa fa-hourglass-half"></i></div>
            <h3>Awaiting confirmation</h3>
            <p>We haven't received confirmation from your mobile network yet. If you approved the payment and money was deducted, it is safe — it will be confirmed automatically and recorded against your reference.</p>
            <p class="swish-ref">Reference: <strong data-swish-ref></strong></p>
            <p style="font-size:.88rem">Please keep this reference. For help, contact the Finance Office on {{ config('site.payments.finance_phone') }}.</p>
            <button type="button" class="cta-button" id="swishRecheck"><i class="fa fa-rotate me-2"></i><span>Check again</span></button>
        </div>
        <div class="swish-step swish-center" data-step="failed" hidden>
            <div class="swish-result-icon failed"><i class="fa fa-xmark"></i></div>
            <h3>Payment not completed</h3>
            <p id="swishFailMsg"></p>
            <p class="swish-ref" data-swish-ref-wrap hidden>Reference: <strong data-swish-ref></strong></p>
            <button type="button" class="cta-button" id="swishRetry"><i class="fa fa-rotate-right me-2"></i>Try again</button>
        </div>

        <div class="swish-footer"><i class="fa fa-shield-halved"></i>Secure, encrypted connection. Queries: Finance Office {{ config('site.payments.finance_phone') }}</div>
    </div>
</div>

@push('pay-scripts')
<script>
(function () {
    const POLL_MS = 5000, MAX_SECONDS = 300;
    const overlay = document.getElementById('swishOverlay');
    const form = document.getElementById('swishForm');
    const err = document.getElementById('swishFormError');
    const submit = document.getElementById('swishSubmit');
    const isDonation = {{ $isDonation ? 'true' : 'false' }};
    const urls = {
        initiate: @json(route('pay.initiate')),
        status: @json(url('pay-online/status')) + '/',
    };
    let pollTimer = null, clockTimer = null, reference = null, lastFocus = null;

    const copy = {
        card: {
            title: isDonation ? 'Donate with Card' : 'Pay with Card', icon: 'fa-credit-card', button: 'Proceed to card checkout',
            phoneLabel: 'Phone number for confirmation SMS *', phoneHint: 'You will be taken to a secure card checkout page (Visa / Mastercard).',
        },
        mobile: {
            title: isDonation ? 'Donate via Mobile Money' : 'Pay with Mobile Money', icon: 'fa-mobile-screen-button', button: 'Send payment request',
            phoneLabel: 'Mobile money number *', phoneHint: 'MTN, Airtel or Zamtel number registered for mobile money.',
        },
    };
    const waiting = {
        ussd: ['Check your phone', 'A USSD prompt has been sent to {phone}. Enter your mobile money PIN to authorise the payment.'],
        push: ['Check your phone', 'A push notification has been sent to {phone}. Open it and approve the payment.'],
        qr:   ['Scan to pay', 'Scan this QR code with your Swish app to complete the payment.'],
    };

    function show(step) {
        overlay.querySelectorAll('.swish-step').forEach(el => el.hidden = el.dataset.step !== step);
    }
    function setRef(ref) {
        overlay.querySelectorAll('[data-swish-ref]').forEach(el => el.textContent = ref || '');
        overlay.querySelector('[data-swish-ref-wrap]').hidden = !ref;
    }
    function fail(msg) {
        stopPolling();
        document.getElementById('swishFailMsg').textContent = msg;
        setRef(reference);
        show('failed');
    }
    function formError(msg) {
        err.textContent = msg; err.hidden = !msg;
    }
    function busy(on) {
        submit.disabled = on;
        submit.querySelector('i').className = on ? 'fa fa-spinner fa-spin me-2' : 'fa fa-lock me-2';
    }

    function open(kind) {
        const c = copy[kind];
        lastFocus = document.activeElement;
        form.reset();
        form.dataset.kind = kind;
        form.elements.method.value = kind === 'card' ? 'card' : 'ussd';
        document.getElementById('swishTitle').textContent = c.title;
        document.getElementById('swishHeaderIcon').className = 'fa ' + c.icon;
        document.getElementById('swishPhoneLabel').textContent = c.phoneLabel;
        document.getElementById('swishPhoneHint').textContent = c.phoneHint;
        submit.querySelector('span').textContent = c.button;
        overlay.querySelector('.swish-mobile-only').hidden = kind === 'card';
        if (isDonation) {
            const purpose = (document.getElementById('purpose-ref')?.textContent || 'Donation – General Donation').replace(/^Donation\s*–\s*/, '');
            form.elements.purpose.value = purpose;
            document.getElementById('swishPurposeLabel').textContent = purpose;
        }
        reference = null;
        document.querySelector('#swishRecheck span').textContent = 'Check again';
        formError(''); busy(false); setRef(null); show('form');
        overlay.hidden = false;
        document.body.style.overflow = 'hidden';
        form.querySelector('input:not([type=hidden])').focus();
    }
    function close() {
        stopPolling();
        overlay.hidden = true;
        document.body.style.overflow = '';
        if (lastFocus) lastFocus.focus();
    }

    async function startPayment(e) {
        e.preventDefault();
        formError('');
        if (!form.reportValidity()) return;
        if (form.dataset.kind === 'mobile') form.elements.method.value = form.elements.mobile_method.value;

        const body = new FormData(form);
        body.delete('mobile_method');
        busy(true);
        try {
            const res = await fetch(urls.initiate, {
                method: 'POST', body,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok || !data.success) {
                const msg = data.error || (data.errors && Object.values(data.errors)[0][0]) || (res.status === 429 ? 'Too many attempts. Please wait a minute and try again.' : 'Something went wrong. Please try again.');
                busy(false);
                return formError(msg);
            }
            reference = data.reference;
            setRef(reference);

            if (data.method === 'card') {
                if (data.paymentUrl) { window.location.href = data.paymentUrl; return; }
                return fail('The card checkout page could not be opened. Please try again or use mobile money.');
            }

            const [title, msg] = waiting[data.method];
            document.getElementById('swishWaitTitle').textContent = title;
            document.getElementById('swishWaitMsg').textContent = msg.replace('{phone}', '+' + data.phone);
            const qr = document.getElementById('swishQr');
            qr.hidden = !(data.method === 'qr' && data.qrImage);
            if (!qr.hidden) qr.src = data.qrImage.startsWith('data:') ? data.qrImage : 'data:image/png;base64,' + data.qrImage;
            show('waiting');
            startPolling();
        } catch (ex) {
            busy(false);
            formError('Network error — please check your connection and try again.');
        }
    }

    function startPolling() {
        let remaining = MAX_SECONDS;
        const timer = document.getElementById('swishTimer');
        const tick = () => { timer.textContent = Math.floor(remaining / 60) + ':' + String(remaining % 60).padStart(2, '0'); };
        tick();
        clockTimer = setInterval(() => {
            remaining = Math.max(0, remaining - 1); tick();
            if (remaining === 0) { stopPolling(); show('awaiting'); }
        }, 1000);
        pollTimer = setInterval(checkStatus, POLL_MS);
    }
    function stopPolling() {
        clearInterval(pollTimer); clearInterval(clockTimer);
        pollTimer = clockTimer = null;
    }
    async function checkStatus() {
        try {
            const res = await fetch(urls.status + encodeURIComponent(reference), { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (data.payment_status === 'success') { stopPolling(); showReceipt(data); }
            else if (data.payment_status === 'failed') fail('The payment was declined or cancelled. Please try again or choose a different method.');
            return data.payment_status;
        } catch (ex) { return 'pending'; /* keep polling */ }
    }
    function showReceipt(data) {
        const rows = { transaction_id: data.transaction_id, amount: data.amount && 'ZMW ' + data.amount, paid_at: data.paid_at };
        Object.entries(rows).forEach(([key, value]) => {
            const row = overlay.querySelector(`[data-receipt="${key}"]`);
            row.hidden = !value;
            row.querySelector('dd').textContent = value || '';
        });
        show('success');
    }
    async function recheck() {
        const btn = document.getElementById('swishRecheck');
        btn.disabled = true;
        btn.querySelector('i').className = 'fa fa-spinner fa-spin me-2';
        const status = await checkStatus();
        btn.disabled = false;
        btn.querySelector('i').className = 'fa fa-rotate me-2';
        btn.querySelector('span').textContent = status === 'pending' ? 'Still waiting — check again' : 'Check again';
    }

    document.querySelectorAll('[data-swish-open]').forEach(btn => btn.addEventListener('click', () => open(btn.dataset.swishOpen)));
    overlay.querySelectorAll('[data-swish-close]').forEach(btn => btn.addEventListener('click', close));
    overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !overlay.hidden) close(); });
    document.getElementById('swishRetry').addEventListener('click', () => open(form.dataset.kind));
    document.getElementById('swishRecheck').addEventListener('click', recheck);
    form.addEventListener('submit', startPayment);
})();
</script>
@endpush
