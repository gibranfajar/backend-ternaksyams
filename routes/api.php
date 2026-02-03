<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\MasterAPIController;
use App\Http\Controllers\API\RajaongkirController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\AuthController as ControllersAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/chart', [ControllersAuthController::class, 'dashboardChart'])->name('dashboard.chart');
Route::get('/dashboard/income-overview', [ControllersAuthController::class, 'incomeOverview'])->name('income.overview');

Route::get('/user', [MasterAPIController::class, 'getUser'])->middleware('auth:sanctum');

// Authentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // with google
    Route::get('google', [AuthController::class, 'redirectToGoogle']);
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);
});

// Forgot Password
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/abouts', [MasterAPIController::class, 'abouts']);

Route::get('/video-players', [MasterAPIController::class, 'getVideoPlayers']);

Route::get('/sliders', [MasterAPIController::class, 'getSliders']);

Route::get('/footers', [MasterAPIController::class, 'getFooters']);

Route::get('/testimonials', [MasterAPIController::class, 'getTestimonials']);

Route::prefix('/cart')->group(function () {
    Route::post('add', [CartController::class, 'addToCart']);
    Route::post('list', [CartController::class, 'getCart']);
});

// voucher apply
Route::post('/apply-voucher', [MasterAPIController::class, 'applyVoucher']);
Route::get('/vouchers', [MasterAPIController::class, 'myVouchers'])->middleware('auth:sanctum');

// Brands
Route::get('/brands', [MasterAPIController::class, 'brands']);
Route::get('/brand/{slug}', [MasterAPIController::class, 'detailBrand']);
Route::get('/variant-all-brand', [MasterAPIController::class, 'variantAllBrand']);

// Hardsellings
Route::get('/hardsellings', [MasterAPIController::class, 'hardsellings']);

// Category
Route::get('/categories', [MasterAPIController::class, 'categories']);
// Products
Route::get('/products', [MasterAPIController::class, 'products']);
Route::get('/product/{slug}', [MasterAPIController::class, 'detailProduct']);
// Variants
Route::get('/variants', [MasterAPIController::class, 'variants']);

Route::get('/flash-sale-products', [MasterAPIController::class, 'flashSaleProducts']);
Route::get('/flash-sale-products/{slug}', [MasterAPIController::class, 'detailFlashSaleProduct']);

// Articles
Route::get('/articles', [MasterAPIController::class, 'articles']);
Route::get('/article/{slug}', [MasterAPIController::class, 'detailArticle']);

// Arsip Tutorials
Route::get('/tutorials', [MasterAPIController::class, 'tutorials']);

Route::get('/benefit/resellers', [MasterAPIController::class, 'benefitResellers']);
Route::get('/benefit/affiliates', [MasterAPIController::class, 'benefitAffiliates']);

// Promotions
Route::get('/promotions', [MasterAPIController::class, 'promotions']);

// Reseller Register
Route::post('/reseller/register', [MasterAPIController::class, 'createReseller']);
Route::get('/resellers', [MasterAPIController::class, 'getResellers']);
Route::get('pricelist-resellers', [MasterAPIController::class, 'getPricelistResellers']);

// Affiliate Register
Route::post('/affiliate/register', [MasterAPIController::class, 'createAffiliate']);
Route::get('/affiliates', [MasterAPIController::class, 'getAffiliates']);


// Rajaongkir
Route::get('provinces', [RajaongkirController::class, 'provinces']);
Route::get('cities/{id}', [RajaongkirController::class, 'cities']);
Route::get('districts/{id}', [RajaongkirController::class, 'districts']);
Route::get('subdistricts/{id}', [RajaongkirController::class, 'subdistricts']);
Route::get('cost', [RajaongkirController::class, 'calculateCost']);

// Faqs
Route::get('/faqs', [MasterAPIController::class, 'getFaqs']);

// Transaction
Route::post('/transaction', [TransactionController::class, 'createTransaction']);
Route::get('/transaction', [TransactionController::class, 'getTransactionUser'])->middleware('auth:sanctum');
// Callback Midtrans
Route::post('/midtrans/callback', [TransactionController::class, 'callback']);
// Tracking Order
Route::get('/track-order', [TransactionController::class, 'trackOrder']);
