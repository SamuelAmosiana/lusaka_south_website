<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Models\Faq;
use App\Models\Download;
use Illuminate\Support\Facades\Route;

// =============================================
// PUBLIC ROUTES
// =============================================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/about', fn() => view('about.overview'))->name('about');
Route::get('/about/team', fn() => view('about.team'))->name('about.team');
Route::get('/about/partnerships', fn() => view('about.partnerships'))->name('about.partnerships');
Route::get('/about/accreditation', fn() => view('about.accreditation'))->name('about.accreditation');

// Schools / Programs
Route::get('/schools', fn() => view('schools'))->name('schools');

// News & Events
Route::get('/news', [NewsController::class, 'index'])->name('news');
Route::get('/news/{event}', [NewsController::class, 'show'])->name('news.show');

// Student Services
Route::get('/services/portal', fn() => view('services.portal'))->name('services.portal');
Route::get('/services/elearning', fn() => view('services.elearning'))->name('services.elearning');
Route::get('/services/research', fn() => view('services.research'))->name('services.research');
Route::get('/services/elibrary', fn() => view('services.elibrary'))->name('services.elibrary');

// Resources
Route::get('/faqs', function () {
    $faqs = Faq::active()->get()->groupBy('category');
    return view('faqs', compact('faqs'));
})->name('faqs');

Route::get('/gallery', fn() => view('gallery'))->name('gallery');

Route::get('/downloads', function () {
    $downloads = Download::active()->get()->groupBy('category');
    return view('downloads', compact('downloads'));
})->name('downloads');

Route::get('/e-resources', fn() => view('e-resources'))->name('e-resources');

// Contact
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Pay Online
Route::view('/pay-online', 'pay.index')->name('pay.online');
Route::view('/pay-online/donate', 'pay.donate')->name('pay.donate');
Route::post('/pay-online/initiate', [PaymentController::class, 'initiate'])->middleware('throttle:10,1')->name('pay.initiate');
Route::get('/pay-online/status/{payment:transaction_reference}', [PaymentController::class, 'status'])->middleware('throttle:30,1')->name('pay.status');
Route::match(['get', 'post'], '/payments/swish/callback', [PaymentController::class, 'callback'])->name('pay.swish.callback');

// Apply / Applications
Route::get('/apply/payment', [ApplicationController::class, 'paymentInstructions'])->name('apply.payment');
Route::get('/apply/undergraduate', fn() => redirect()->away(config('site.apply.undergraduate')))->name('apply.undergraduate');
Route::get('/apply/short-courses', fn() => redirect()->away(config('site.apply.short_courses')))->name('apply.short-courses');
Route::get('/apply/corporate-training', fn() => redirect()->away(config('site.apply.corporate_training')))->name('apply.corporate-training');
Route::get('/apply/extra-curricular', [ApplicationController::class, 'extraCurricular'])->name('apply.extra-curricular');

// =============================================
// ADMIN ROUTES (auth + admin role required)
// =============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', EventController::class);
    Route::resource('faqs', FaqController::class);
    Route::resource('downloads', DownloadController::class);

    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/export', [AdminPaymentController::class, 'export'])->name('payments.export');
    Route::get('payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/confirm', [AdminPaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    Route::post('payments/{payment}/check', [AdminPaymentController::class, 'check'])->name('payments.check');
});

// Redirect /admin/login to the standard login page
Route::get('/admin/login', fn() => redirect()->route('login'))->name('admin.login');

// =============================================
// BREEZE AUTH ROUTES
// =============================================
require __DIR__ . '/auth.php';
