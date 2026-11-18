@extends('layouts.app')
@section('title', 'Downloads - ' . config('site.name'))
@section('styles')
<style>
.downloads-table-wrap{background:#fff;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08);overflow:hidden}
.downloads-table{width:100%;border-collapse:collapse}
.downloads-table th{background:var(--primary-green);color:#fff;text-align:left;padding:15px 20px;font-size:.92rem;font-weight:700}
.downloads-table td{padding:15px 20px;border-bottom:1px solid var(--gray-200);vertical-align:middle;font-size:.95rem}
.downloads-table tbody tr:last-child td{border-bottom:none}
.downloads-table tbody tr:hover td{background:#f4faf4}
.doc-name{display:flex;align-items:center;gap:12px;font-weight:600;color:var(--gray-800)}
.doc-name i{color:#d32f2f;font-size:1.4rem;flex-shrink:0}
.doc-desc{color:var(--gray-600);line-height:1.6}
.doc-actions{display:flex;gap:8px;justify-content:center;flex-wrap:wrap}
.doc-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:50px;font-size:.85rem;font-weight:600;text-decoration:none;cursor:pointer;transition:all .3s ease;border:2px solid var(--primary-green);font-family:inherit;white-space:nowrap}
.doc-btn-view{background:transparent;color:var(--primary-green)}
.doc-btn-view:hover{background:var(--primary-green);color:#fff}
.doc-btn-download{background:var(--primary-orange);border-color:var(--primary-orange);color:#fff}
.doc-btn-download:hover{background:var(--light-orange);border-color:var(--light-orange);color:#fff}
.doc-filter{display:flex;gap:12px;background:#fff;padding:14px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.08);position:sticky;top:90px;z-index:20}
.doc-filter-search{flex:1;position:relative;display:flex;align-items:center}
.doc-filter-search>i{position:absolute;left:16px;color:var(--primary-green);pointer-events:none}
.doc-filter-search input{width:100%;padding:13px 44px;border:2px solid var(--gray-200);border-radius:50px;font-size:1rem;font-family:inherit;transition:border-color .2s ease}
.doc-filter-search input:focus{outline:none;border-color:var(--primary-green);box-shadow:0 0 0 3px rgba(34,139,34,.1)}
.doc-filter-search input::-webkit-search-cancel-button{display:none}
#doc-search-clear{position:absolute;right:10px;background:var(--gray-100);border:none;width:28px;height:28px;border-radius:50%;cursor:pointer;color:var(--gray-600)}
#doc-search-clear:hover{background:var(--gray-200);color:var(--gray-800)}
.doc-filter select{padding:12px 16px;border:2px solid var(--gray-200);border-radius:50px;font-size:.95rem;font-family:inherit;background:#fff;color:var(--gray-800);cursor:pointer;min-width:200px}
.doc-filter select:focus{outline:none;border-color:var(--primary-green)}
.doc-filter-status{color:var(--gray-600);font-size:.9rem;margin:12px 4px 0;min-height:1.2em}
.doc-no-results{text-align:center;padding:50px 20px;color:var(--gray-600)}
.doc-no-results i{font-size:3rem;color:var(--gray-200);margin-bottom:15px}
.doc-no-results h3{color:var(--gray-800);margin-bottom:8px}
.doc-no-results a{color:var(--primary-green);font-weight:600}
.doc-name mark{background:#fff3cd;color:inherit;padding:0 2px;border-radius:3px}
@media(max-width:768px){
    .downloads-table thead{display:none}
    .downloads-table,.downloads-table tbody,.downloads-table tr,.downloads-table td{display:block;width:100%}
    .downloads-table tr{padding:16px 18px;border-bottom:1px solid var(--gray-200)}
    .downloads-table tbody tr:last-child{border-bottom:none}
    .downloads-table td{padding:4px 0;border:none}
    .downloads-table tbody tr:hover td{background:transparent}
    .doc-desc{margin:4px 0 10px}
    .doc-actions{justify-content:flex-start}
    .doc-filter{flex-direction:column;top:80px;padding:12px}
    .doc-filter select{min-width:0;width:100%}
}
</style>
@endsection
@section('content')
<div class="page-header"><div class="container">
    <h1>Downloads</h1>
    <p>Access and download important documents, forms, and resources</p>
</div></div>
<div class="breadcrumb"><div class="container">
    <a href="{{ route('home') }}">Home</a> &rsaquo; Downloads
</div></div>
<section class="content-section"><div class="container">
    <div class="section-title"><h2>Document Library</h2>
        <p>Download important forms, guides, and institutional documents.</p>
    </div>
    @if($downloads->isNotEmpty())
    <div class="doc-filter" role="search">
        <div class="doc-filter-search">
            <i class="fa fa-magnifying-glass"></i>
            <input type="search" id="doc-search" placeholder="Search documents, e.g. fees, timetable, bursary, results…" autocomplete="off" aria-label="Search documents">
            <button type="button" id="doc-search-clear" aria-label="Clear search" hidden><i class="fa fa-xmark"></i></button>
        </div>
        <select id="doc-category" aria-label="Filter by category">
            <option value="">All categories</option>
            @foreach($downloads->keys() as $category)
            <option value="{{ $category }}">{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <p class="doc-filter-status" id="doc-filter-status" aria-live="polite"></p>
    <div class="doc-no-results" id="doc-no-results" hidden>
        <i class="fa fa-file-circle-question"></i>
        <h3>No matching documents</h3>
        <p>Try a different or shorter word, or <a href="{{ route('contact') }}">contact us</a> and we'll send you the document you need.</p>
    </div>
    @endif
    @foreach($downloads as $category => $docs)
    <div class="doc-group" data-category="{{ $category }}">
    <h3 style="color:var(--primary-green);font-size:1.3rem;margin:40px 0 20px;border-left:4px solid var(--primary-orange);padding-left:15px">
        <i class="fa fa-folder-open me-2"></i>{{ $category }}
    </h3>
    <div class="downloads-table-wrap">
        <table class="downloads-table">
            <thead>
                <tr><th style="width:34%">Document Name</th><th>Description</th><th style="width:230px;text-align:center">Action</th></tr>
            </thead>
            <tbody>
                @foreach($docs as $doc)
                <tr class="doc-row" data-title="{{ $doc->title }}" data-text="{{ $doc->description }} {{ $category }} {{ pathinfo($doc->filename, PATHINFO_FILENAME) }}">
                    <td data-label="Document">
                        <div class="doc-name"><i class="fa fa-file-pdf"></i><span>{{ $doc->title }}</span></div>
                    </td>
                    <td data-label="Description" class="doc-desc">{{ $doc->description ?: '—' }}</td>
                    <td data-label="Action">
                        <div class="doc-actions">
                            <button type="button" onclick="openPDFViewer(@js(asset('docs/'.$doc->filename)), @js($doc->title))" class="doc-btn doc-btn-view">
                                <i class="fa fa-eye"></i> View
                            </button>
                            <a href="{{ asset('docs/'.$doc->filename) }}" download class="doc-btn doc-btn-download">
                                <i class="fa fa-download"></i> Download
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
    @endforeach
    @if($downloads->isEmpty())
    <p style="text-align:center;color:var(--gray-600);padding:50px">No downloads available. Check back soon.</p>
    @endif
</div></section>
@endsection

@section('scripts')
<script>
/* doc-search:start */
const DocSearch = (() => {
    // Words people may use instead of the names we gave the documents.
    // Any word in a group also finds documents containing the other words.
    const RELATED = [
        ['fee', 'fees', 'price', 'prices', 'cost', 'costs', 'tuition', 'payment', 'pay', 'charges', 'amount'],
        ['timetable', 'schedule', 'lecture', 'lectures', 'class', 'classes', 'tutorial', 'tutorials', 'periods'],
        ['calendar', 'dates', 'date', 'term', 'semester', 'intake', 'holiday', 'holidays', 'opening', 'closing', 'events'],
        ['application', 'apply', 'applying', 'admission', 'admissions', 'enrol', 'enroll', 'enrolment', 'enrollment', 'registration', 'register', 'join', 'joining'],
        ['form', 'forms', 'paper'],
        ['exemption', 'exempt', 'exemptions', 'credit', 'transfer', 'rpl', 'prior', 'qualifications'],
        ['portal', 'login', 'signin', 'srms', 'account', 'password', 'access', 'results', 'grades', 'marks', 'transcript'],
        ['guide', 'manual', 'instructions', 'instruction', 'help', 'steps', 'howto', 'handbook'],
        ['catalog', 'catalogue', 'prospectus', 'brochure', 'offerings', 'program', 'programs', 'programme', 'programmes'],
        ['course', 'courses', 'program', 'programs', 'programme', 'programmes', 'degree', 'degrees', 'diploma', 'diplomas', 'study', 'studies'],
        ['bursary', 'bursaries', 'scholarship', 'scholarships', 'sponsorship', 'sponsor', 'funding', 'grant', 'loan', 'cdf', 'financial', 'aid'],
        ['cpd', 'short', 'professional', 'continuing', 'certificate', 'certificates', 'training', 'skills'],
        ['press', 'release', 'news', 'announcement', 'establishment', 'established', 'history', 'launch'],
    ];
    const STOP = new Set(['a', 'an', 'the', 'of', 'for', 'to', 'and', 'or', 'in', 'on', 'at', 'my', 'me', 'i', 'is', 'are', 'how', 'where', 'what', 'can', 'do', 'get', 'want', 'need', 'find', 'please', 'download', 'downloads', 'document', 'documents', 'doc', 'pdf', 'file', 'files', 'copy', 'lsuc']);

    const related = new Map();
    RELATED.forEach(group => group.forEach(w => {
        if (!related.has(w)) related.set(w, new Set());
        group.forEach(o => o !== w && related.get(w).add(o));
    }));

    const words = text => (text || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, ' ').trim().split(' ').filter(Boolean);

    // Joined words too, so "time table" and "timetable" find each other
    const withJoined = list => list.concat(list.slice(1).map((w, i) => list[i] + w));

    function distance(a, b) {
        if (Math.abs(a.length - b.length) > 2) return 3;
        let prev = Array.from({ length: b.length + 1 }, (_, i) => i);
        for (let i = 1; i <= a.length; i++) {
            const cur = [i];
            for (let j = 1; j <= b.length; j++) {
                cur[j] = Math.min(prev[j] + 1, cur[j - 1] + 1, prev[j - 1] + (a[i - 1] === b[j - 1] ? 0 : 1));
            }
            prev = cur;
        }
        return prev[b.length];
    }

    // How well one typed word matches one document word (0 = not at all)
    function wordScore(q, w) {
        if (q === w) return 1;
        if (q.length >= 3 && w.startsWith(q)) return 0.85;
        if (w.length >= 4 && q.startsWith(w)) return 0.75;
        if (q.length >= 4 && w.length >= 4) {
            const allowed = q.length >= 7 ? 2 : 1;
            const d = Math.min(distance(q, w), distance(q, w.slice(0, q.length)) + 0.5);
            if (d <= allowed) return 0.7 - d * 0.1;
        }
        return 0;
    }

    function bestIn(q, docWords) {
        let best = 0;
        for (const w of docWords) {
            best = Math.max(best, wordScore(q, w));
            if (best === 1) break;
        }
        return best;
    }

    // Related words for what was typed, allowing for typos in the typed word itself
    function relatedTo(q) {
        const out = new Set();
        related.forEach((others, key) => {
            if (wordScore(q, key) >= 0.7) { out.add(key); others.forEach(o => out.add(o)); }
        });
        out.delete(q);
        return out;
    }

    function prepare(title, text) {
        const keep = list => withJoined(list).filter(w => !STOP.has(w));
        return { title: keep(words(title)), text: keep(words(text)) };
    }

    function score(query, doc) {
        let terms = words(query).filter(w => !STOP.has(w));
        if (!terms.length) terms = words(query); // e.g. just "a" typed: still match names starting with it
        if (!terms.length) return 1;
        let total = 0, matched = 0;
        for (const q of terms) {
            // 1-2 letters (still typing): match the start of words in document names
            if (q.length < 3) {
                const s = doc.title.some(w => w.startsWith(q)) ? 2.5 : 0;
                if (s) { matched++; total += s; }
                continue;
            }
            let s = Math.max(bestIn(q, doc.title) * 3, bestIn(q, doc.text));
            if (s < 1.5) {
                for (const r of relatedTo(q)) {
                    s = Math.max(s, bestIn(r, doc.title) * 3 * 0.6, bestIn(r, doc.text) * 0.6);
                }
            }
            if (s > 0) { matched++; total += s; }
        }
        // Documents matching more of the typed words rank first
        return matched ? total * (matched / terms.length) : 0;
    }

    return { prepare, score, words };
})();
/* doc-search:end */

(() => {
    const input = document.getElementById('doc-search');
    if (!input) return;
    const clearBtn = document.getElementById('doc-search-clear');
    const category = document.getElementById('doc-category');
    const status = document.getElementById('doc-filter-status');
    const empty = document.getElementById('doc-no-results');
    const groups = [...document.querySelectorAll('.doc-group')];
    const rows = [...document.querySelectorAll('.doc-row')].map((el, i) => {
        const nameEl = el.querySelector('.doc-name span');
        return { el, i, nameEl, title: el.dataset.title, tbody: el.parentNode, data: DocSearch.prepare(el.dataset.title, el.dataset.text) };
    });
    const total = rows.length;
    const esc = s => s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    function highlight(title, query) {
        const terms = DocSearch.words(query);
        if (!terms.length) return esc(title);
        return title.split(/(\s+)/).map(part => {
            const w = DocSearch.words(part)[0];
            return w && terms.some(t => w.startsWith(t) || (t.startsWith(w) && w.length >= 4)) ? '<mark>' + esc(part) + '</mark>' : esc(part);
        }).join('');
    }

    function apply() {
        const q = input.value.trim();
        const cat = category.value;
        clearBtn.hidden = !q;
        let shown = 0;
        const scored = rows.map(r => ({ r, s: q ? DocSearch.score(q, r.data) : 1 }));
        scored.forEach(({ r, s }) => {
            const visible = s > 0 && (!cat || r.el.closest('.doc-group').dataset.category === cat);
            r.el.hidden = !visible;
            r.nameEl.innerHTML = visible && q ? highlight(r.title, q) : esc(r.title);
            if (visible) shown++;
        });
        // Best matches first inside each table; original order when not searching
        scored.sort((a, b) => q ? (b.s - a.s) || (a.r.i - b.r.i) : a.r.i - b.r.i)
              .forEach(({ r }) => r.tbody.appendChild(r.el));
        groups.forEach(g => { g.hidden = !g.querySelector('.doc-row:not([hidden])'); });
        empty.hidden = shown > 0;
        status.textContent = (q || cat) ? (shown ? `Showing ${shown} of ${total} documents` : '') : '';
    }

    let timer;
    input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(apply, 120); });
    category.addEventListener('change', apply);
    clearBtn.addEventListener('click', () => { input.value = ''; apply(); input.focus(); });
    input.addEventListener('keydown', e => { if (e.key === 'Escape') { input.value = ''; apply(); } });
})();
</script>
@endsection
