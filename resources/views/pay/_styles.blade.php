<style>
    .pay-methods .card{display:flex;flex-direction:column}
    .pay-methods .card h3{color:var(--primary-green);margin-bottom:12px}
    .pay-methods .card p{color:var(--gray-600);flex:1;margin-bottom:22px}
    .pay-methods .pay-link{align-self:flex-start;font-size:.95rem;padding:12px 26px}
    .pay-banner{display:flex;gap:22px;align-items:flex-start;background:var(--gray-100);border-left:5px solid var(--primary-green);border-radius:15px;padding:30px;margin-top:50px}
    .pay-banner-icon{flex:none;width:56px;height:56px;border-radius:50%;background:var(--primary-green);color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px}
    .pay-banner h3{color:var(--primary-green);margin-bottom:8px}
    .pay-banner p{color:var(--gray-600);margin:0}
    .pay-banner a{color:var(--primary-green);font-weight:600}
    .donate-intro{text-align:center;max-width:780px;margin:0 auto 50px}
    .donate-intro .pay-banner-icon{margin:0 auto 20px;width:72px;height:72px;font-size:30px;background:var(--primary-orange)}
    .donate-intro h2{color:var(--primary-green);margin-bottom:15px}
    .donate-intro p{color:var(--gray-600);font-size:1.05rem}
    .purpose-box{background:#fff;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08);padding:30px;margin-bottom:20px}
    .purpose-box h3{color:var(--primary-green);margin-bottom:18px;text-align:center}
    .purpose-options{display:flex;flex-wrap:wrap;gap:12px;justify-content:center}
    .purpose-options input{position:absolute;opacity:0;pointer-events:none}
    .purpose-options label{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border:2px solid var(--gray-200);border-radius:50px;cursor:pointer;color:var(--gray-800);transition:all .2s ease}
    .purpose-options label:hover{border-color:var(--primary-orange)}
    .purpose-options input:checked + label{background:var(--primary-green);border-color:var(--primary-green);color:#fff}
    .purpose-options input:focus-visible + label{outline:3px solid var(--light-orange);outline-offset:2px}
    .purpose-other{max-width:480px;margin:20px auto 0}
    .purpose-other label{display:block;font-weight:600;color:var(--gray-800);margin-bottom:6px}
    .purpose-other input{width:100%;padding:12px 16px;border:2px solid var(--gray-200);border-radius:10px;font-size:1rem;font-family:inherit}
    .purpose-other input:focus{outline:none;border-color:var(--primary-green)}
    .purpose-ref{text-align:center;margin-top:18px;color:var(--gray-600);font-size:.95rem}
    .purpose-ref strong{color:var(--primary-green)}
    /* Swish payment modal */
    .swish-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:2000;display:flex;align-items:center;justify-content:center;padding:16px}
    .swish-overlay[hidden],.swish-overlay [hidden]{display:none!important}
    .swish-modal{background:#fff;border-radius:18px;width:100%;max-width:480px;max-height:calc(100vh - 32px);overflow-y:auto;box-shadow:0 25px 60px rgba(0,0,0,.3)}
    .swish-header{display:flex;align-items:center;gap:14px;padding:20px 22px;background:linear-gradient(135deg,var(--primary-green),var(--dark-green));color:#fff;border-radius:18px 18px 0 0;position:sticky;top:0;z-index:1}
    .swish-header-icon{flex:none;width:44px;height:44px;border-radius:50%;background:var(--primary-orange);display:flex;align-items:center;justify-content:center;font-size:18px}
    .swish-header h2{font-size:1.2rem;margin:0}
    .swish-header p{font-size:.8rem;margin:2px 0 0;opacity:.85}
    .swish-close{margin-left:auto;background:rgba(255,255,255,.15);border:none;color:#fff;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:16px;flex:none}
    .swish-close:hover{background:rgba(255,255,255,.3)}
    .swish-step{padding:22px}
    .swish-field{margin-bottom:16px;border:none;padding:0}
    .swish-field label,.swish-field legend{display:block;font-weight:600;color:var(--gray-800);margin-bottom:6px;font-size:.92rem}
    .swish-field input[type=text],.swish-field input[type=number],.swish-field input[type=tel]{width:100%;padding:11px 14px;border:2px solid var(--gray-200);border-radius:10px;font-size:1rem;font-family:inherit}
    .swish-field input:focus{outline:none;border-color:var(--primary-green)}
    .swish-field small{display:block;color:var(--gray-600);font-size:.8rem;margin-top:5px}
    .swish-options{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
    .swish-options label{margin:0;font-weight:500}
    .swish-options input{position:absolute;opacity:0;pointer-events:none}
    .swish-options span{display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 6px;border:2px solid var(--gray-200);border-radius:10px;text-align:center;font-size:.82rem;cursor:pointer;transition:all .2s ease}
    .swish-options span i{font-size:1.2rem;color:var(--primary-green)}
    .swish-options input:checked + span{border-color:var(--primary-green);background:rgba(34,139,34,.08)}
    .swish-options input:focus-visible + span{outline:3px solid var(--light-orange);outline-offset:2px}
    .swish-note{display:flex;gap:10px;align-items:center;background:var(--gray-100);border-radius:10px;padding:12px 14px;margin-bottom:16px;color:var(--gray-800);font-size:.92rem}
    .swish-note i{color:var(--primary-orange)}
    .swish-error{background:#fdecea;color:#b3261e;border-radius:10px;padding:11px 14px;margin-bottom:14px;font-size:.9rem}
    .swish-submit{width:100%;font-size:1rem;padding:14px}
    .swish-submit:disabled{opacity:.7;cursor:wait}
    .swish-center{text-align:center}
    .swish-center h3{color:var(--primary-green);margin:14px 0 8px}
    .swish-center p{color:var(--gray-600)}
    .swish-spinner{width:54px;height:54px;margin:6px auto 0;border:5px solid var(--gray-200);border-top-color:var(--primary-orange);border-radius:50%;animation:swish-spin 1s linear infinite}
    @keyframes swish-spin{to{transform:rotate(360deg)}}
    .swish-qr{display:block;width:220px;max-width:100%;margin:14px auto;border:1px solid var(--gray-200);border-radius:10px;padding:8px}
    .swish-ref{font-size:.9rem;margin-top:10px;word-break:break-all}
    .swish-ref strong{color:var(--gray-800)}
    .swish-countdown{font-size:.88rem}
    .swish-result-icon{width:64px;height:64px;margin:6px auto 0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff}
    .swish-result-icon.success{background:var(--primary-green)}
    .swish-result-icon.failed{background:#c0392b}
    .swish-result-icon.awaiting{background:var(--primary-orange)}
    .swish-receipt{background:var(--gray-100);border-radius:12px;padding:6px 16px;margin:16px 0 4px;text-align:left}
    .swish-receipt div{display:flex;justify-content:space-between;gap:12px;padding:9px 0;border-bottom:1px solid var(--gray-200);font-size:.92rem}
    .swish-receipt div:last-child{border-bottom:none}
    .swish-receipt dt{color:var(--gray-600)}
    .swish-receipt dd{margin:0;font-weight:600;color:var(--gray-800);text-align:right;word-break:break-all}
    .swish-center .cta-button{margin-top:14px}
    .swish-footer{display:flex;gap:8px;align-items:center;justify-content:center;padding:14px 22px;border-top:1px solid var(--gray-200);color:var(--gray-600);font-size:.78rem;text-align:center}
    .swish-footer i{color:var(--primary-green)}
    @media (max-width:600px){
        .pay-banner{flex-direction:column;padding:22px}
        .purpose-box{padding:22px 16px}
        .swish-step{padding:18px 16px}
        .swish-header{padding:16px}
    }
    @media (prefers-reduced-motion:reduce){.swish-spinner{animation-duration:3s}}
</style>
