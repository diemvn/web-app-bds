<?php

use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\FilterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ListingController;
use App\Http\Controllers\Public\MapController;
use App\Http\Controllers\Public\RobotsController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\PortalController;
use App\Http\Controllers\Webhook\SepayWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class);
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/bo-loc', [FilterController::class, 'index'])->name('filters');
Route::get('/tim-phong', [MapController::class, 'index'])->name('map.index');
Route::get('/phong/{slug}', [ListingController::class, 'show'])->name('listing.show');
Route::get('/phong/{slug}/lien-he', [ContactController::class, 'create'])->name('listing.contact');
Route::post('/phong/{slug}/lien-he', [ContactController::class, 'store'])->name('listing.contact.store');

Route::post('/webhook/sepay', SepayWebhookController::class)->name('webhook.sepay');

Route::prefix('khach')->name('tenant.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/dang-nhap', [AuthController::class, 'login']);
    });

    Route::middleware(['auth', 'tenant'])->group(function () {
        Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [PortalController::class, 'home'])->name('home');
        Route::get('/hoa-don', [PortalController::class, 'invoices'])->name('invoices');
        Route::get('/hoa-don/{invoice}', [PortalController::class, 'showInvoice'])->name('invoices.show');
        Route::get('/hop-dong', [PortalController::class, 'contract'])->name('contract');
        Route::get('/thong-bao', [PortalController::class, 'notifications'])->name('notifications');
        Route::post('/thong-bao/{notification}/read', [PortalController::class, 'markNotificationRead'])->name('notifications.read');
        Route::get('/tai-khoan', [PortalController::class, 'profile'])->name('profile');
    });
});
