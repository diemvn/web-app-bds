<?php

use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\FavoriteController;
use App\Http\Controllers\Public\FilterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ListingController;
use App\Http\Controllers\Public\MapController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\RobotsController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Public\SupportController;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\PortalController;
use App\Http\Controllers\Webhook\SepayWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class);
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// === Unified Auth ===
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/dang-nhap', [AuthController::class, 'login']);
});
Route::post('/dang-xuat', [AuthController::class, 'logout'])
    ->middleware('auth')->name('auth.logout');

// === Public Pages ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/opt1', function () {
    $listings = \App\Models\Listing::with(['room.building'])->paginate(12);
    return view('public.home-opt1', ['listings' => $listings, 'seo' => \App\Support\SeoData::forHome()]);
});
Route::get('/opt2', function () {
    $listings = \App\Models\Listing::with(['room.building'])->paginate(12);
    return view('public.home-opt2', ['listings' => $listings, 'seo' => \App\Support\SeoData::forHome()]);
});
Route::get('/opt3', function () {
    $listings = \App\Models\Listing::with(['room.building'])->paginate(12);
    return view('public.home-opt3', ['listings' => $listings, 'seo' => \App\Support\SeoData::forHome()]);
});
Route::get('/bo-loc', [FilterController::class, 'index'])->name('filters');
Route::get('/tim-phong', [MapController::class, 'index'])->name('map.index');
Route::get('/phong/{slug}', [ListingController::class, 'show'])->name('listing.show');
Route::get('/phong/{slug}/lien-he', [ContactController::class, 'create'])->name('listing.contact');
Route::post('/phong/{slug}/lien-he', [ContactController::class, 'store'])->name('listing.contact.store');

// === News ===
Route::get('/tin-tuc', [NewsController::class, 'index'])->name('news.index');
Route::get('/tin-tuc/{slug}', [NewsController::class, 'show'])->name('news.show');

// === Favorites ===
Route::get('/yeu-thich', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/yeu-thich/{listing}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// === Support ===
Route::get('/ho-tro', [SupportController::class, 'index'])->name('support.index');
Route::post('/ho-tro', [SupportController::class, 'submit'])->name('support.submit');

// === Webhooks ===
Route::post('/webhook/sepay', SepayWebhookController::class)->name('webhook.sepay');

// === Tenant Portal ===
Route::prefix('khach')->name('tenant.')->middleware(['auth', 'tenant'])->group(function () {
    Route::get('/', [PortalController::class, 'home'])->name('home');
    Route::get('/hoa-don', [PortalController::class, 'invoices'])->name('invoices');
    Route::get('/hoa-don/{invoice}', [PortalController::class, 'showInvoice'])->name('invoices.show');
    Route::get('/hop-dong', [PortalController::class, 'contract'])->name('contract');
    Route::get('/thong-bao', [PortalController::class, 'notifications'])->name('notifications');
    Route::post('/thong-bao/{notification}/read', [PortalController::class, 'markNotificationRead'])->name('notifications.read');
    Route::get('/tai-khoan', [PortalController::class, 'profile'])->name('profile');
});
