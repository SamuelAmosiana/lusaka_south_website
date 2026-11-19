<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\LearningController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
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
Route::get('/about', [AboutController::class, 'show'])->defaults('slug', 'overview')->name('about');
Route::get('/about/team', [AboutController::class, 'show'])->defaults('slug', 'team')->name('about.team');
Route::get('/about/partnerships', [AboutController::class, 'show'])->defaults('slug', 'partnerships')->name('about.partnerships');
Route::get('/about/accreditation', [AboutController::class, 'show'])->defaults('slug', 'accreditation')->name('about.accreditation');

// Schools / Programs
Route::get('/schools', fn() => view('schools', [
    'settings' => \App\Models\SchoolSetting::current(),
    'schools'  => \App\Models\School::active()->ordered()->with('activeProgrammes')->get(),
]))->name('schools');

// Scholarships
Route::get('/scholarships', fn() => view('scholarships'))->name('scholarships');

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
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

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
    Route::post('counters/batch-update', [CounterController::class, 'batchUpdate'])->name('counters.batch-update');
    Route::resource('counters', CounterController::class);

    // Learning Modes & Video Management
    Route::get('learning', [LearningController::class, 'index'])->name('learning.index');
    Route::post('learning/settings', [LearningController::class, 'updateSettings'])->name('learning.settings');
    Route::get('learning/features/create', [LearningController::class, 'createFeature'])->name('learning.features.create');
    Route::post('learning/features', [LearningController::class, 'storeFeature'])->name('learning.features.store');
    Route::get('learning/features/{feature}/edit', [LearningController::class, 'editFeature'])->name('learning.features.edit');
    Route::put('learning/features/{feature}', [LearningController::class, 'updateFeature'])->name('learning.features.update');
    Route::delete('learning/features/{feature}', [LearningController::class, 'destroyFeature'])->name('learning.features.destroy');
    Route::post('learning/features/batch', [LearningController::class, 'batchUpdateFeatures'])->name('learning.features.batch');

    // Schools & Programmes page
    Route::post('schools/settings', [AdminSchoolController::class, 'updateSettings'])->name('schools.settings');
    Route::post('schools/batch', [AdminSchoolController::class, 'batchUpdate'])->name('schools.batch');
    Route::resource('schools', AdminSchoolController::class)->except('show');

    // About Pages (Overview, Team, Partnerships, Accreditation)
    Route::get('about', [AdminAboutController::class, 'index'])->name('about.index');
    Route::get('about/items/{item}/edit', [AdminAboutController::class, 'editItem'])->name('about.items.edit');
    Route::put('about/items/{item}', [AdminAboutController::class, 'updateItem'])->name('about.items.update');
    Route::delete('about/items/{item}', [AdminAboutController::class, 'destroyItem'])->name('about.items.destroy');
    Route::get('about/{page}', [AdminAboutController::class, 'edit'])->name('about.edit');
    Route::put('about/{page}', [AdminAboutController::class, 'updatePage'])->name('about.update');
    Route::post('about/{page}/batch', [AdminAboutController::class, 'batchUpdate'])->name('about.batch');
    Route::get('about/{page}/{group}/create', [AdminAboutController::class, 'createItem'])->name('about.items.create');
    Route::post('about/{page}/{group}', [AdminAboutController::class, 'storeItem'])->name('about.items.store');

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
