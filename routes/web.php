<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryFaqController;
use App\Http\Controllers\CategoryTutorialController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\FlavourController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HardsellingController;
use App\Http\Controllers\HardsellingCtaController;
use App\Http\Controllers\HardsellingFooterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PricelistResellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TestimonialBrandController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\VideoPlayerController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AuthController::class, 'settings'])->name('settings');

    Route::get('/rajaongkir/cities/{province}', [AuthController::class, 'cities']);
    Route::get('/rajaongkir/districts/{city}', [AuthController::class, 'districts']);
    Route::get('/rajaongkir/subdistricts/{district}', [AuthController::class, 'subdistricts']);

    Route::put('/profile/update/{id}', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/shipper/update', [AuthController::class, 'updateShipper'])->name('shipper.update');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('video-players', VideoPlayerController::class);
    Route::resource('sliders', SliderController::class);
    Route::patch('sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('sliders.toggleStatus');
    Route::resource('abouts', AboutController::class);
    Route::resource('footers', FooterController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('flavours', FlavourController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('products', ProductController::class);
    Route::patch('variants/{variant}/toggle-status', [ProductController::class, 'toggleStatus'])->name('variants.toggleStatus');
    Route::resource('flash-sales', FlashSaleController::class);
    Route::patch('/flash-sales/{flashSale}/toggle-status', [FlashSaleController::class, 'toggleStatus'])->name('flash-sales.toggleStatus');
    Route::get('/variants/{variant}/sizes', [FlashSaleController::class, 'getSizes']);


    Route::resource('benefits', BenefitController::class);
    Route::patch('benefits/{benefit}/toggle-status', [BenefitController::class, 'toggleStatus'])->name('benefits.toggleStatus');

    Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');

    Route::put('partner-resellers/{id}', [PartnerController::class, 'update'])->name('partner-resellers.update');
    Route::patch('partner-resellers/{id}/updateStatus', [PartnerController::class, 'updateStatus'])->name('partner-resellers.updateStatus');

    Route::resource('pricelist-resellers', PricelistResellerController::class);
    Route::patch('pricelist-resellers/{pricelistReseller}/toggle-active', [PricelistResellerController::class, 'toggleActive'])->name('pricelist-resellers.toggleActive');

    Route::resource('promotions', PromotionController::class);
    Route::patch('/promotions/{id}/toggle-popup', [PromotionController::class, 'togglePopup'])->name('promotions.togglePopup');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/invoice/{id}', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/pickup', [OrderController::class, 'pickup'])->name('orders.pickup');
    Route::get('orders/print-label', [OrderController::class, 'printLabel'])->name('orders.printLabel');
    Route::post('orders/labelstore', [OrderController::class, 'labelStore'])->name('orders.labelstore');
    Route::post('orders/pickup', [OrderController::class, 'pickupStore'])->name('orders.pickup.store');
    Route::post('orders/request/{order}', [OrderController::class, 'orderRequest'])->name('orders.request-order');

    Route::resource('vouchers', VoucherController::class);

    Route::resource('category-articles', CategoryArticleController::class);
    Route::resource('articles', ArticleController::class);
    Route::patch('articles/{article}/toggle-status', [ArticleController::class, 'toggleStatus'])->name('articles.toggleStatus');

    Route::resource('category-tutorials', CategoryTutorialController::class);
    Route::resource('tutorials', TutorialController::class);
    Route::patch('tutorials/{tutorial}/toggle-status', [TutorialController::class, 'toggleStatus'])->name('tutorials.toggleStatus');

    Route::resource('testimonials', TestimonialController::class);
    Route::patch('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggleStatus');

    Route::resource('testimonial-brands', TestimonialBrandController::class);
    Route::patch('testimonial-brands/{testimonial}/toggle-status', [TestimonialBrandController::class, 'toggleStatus'])->name('testimonial-brands.toggleStatus');

    Route::resource('category-faqs', CategoryFaqController::class);
    Route::resource('faqs', FaqController::class);
    Route::patch('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggleStatus');


    /**
     * HARDSELLING
     */
    Route::get('hardsellings', [HardsellingController::class, 'index'])->name('hardsellings.index');
    Route::get('/hardsellings/create', [HardsellingController::class, 'create'])->name('hardsellings.create');
    Route::post('/hardsellings/store', [HardsellingController::class, 'store'])->name('hardsellings.store');
    Route::get('/hardsellings/edit', [HardsellingController::class, 'editPreview'])->name('hardsellings.editPreview');
    Route::post('/hardsellings/update', [HardsellingController::class, 'update'])->name('hardsellings.update');
    Route::delete('/hardsellings/destroy', [HardsellingController::class, 'destroyAll'])->name('hardsellings.destroy');

    /**
     * HARDSELLING CTA
     */
    Route::get('/hardsellings/cta', [HardsellingCtaController::class, 'indexCta'])->name('hardsellings.cta.index');
    Route::get('/hardsellings/cta/create', [HardsellingCtaController::class, 'createCta'])->name('hardsellings.cta.create');
    Route::post('/hardsellings/cta/store', [HardsellingCtaController::class, 'storeCta'])->name('hardsellings.cta.store');
    Route::get('/hardsellings/cta/edit/{id}', [HardsellingCtaController::class, 'editCta'])->name('hardsellings.cta.edit');
    Route::put('/hardsellings/cta/update/{id}', [HardsellingCtaController::class, 'updateCta'])->name('hardsellings.cta.update');
    Route::delete('/hardsellings/cta/destroy/{id}', [HardsellingCtaController::class, 'destroyCta'])->name('hardsellings.cta.destroy');

    /**
     * HARDSELLING FOOTER
     */
    Route::resource('hardselling-footers', HardsellingFooterController::class, [
        'only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']
    ])->names([
        'index' => 'hardselling-footers.index',
        'create' => 'hardselling-footers.create',
        'store' => 'hardselling-footers.store',
        'edit' => 'hardselling-footers.edit',
        'update' => 'hardselling-footers.update',
        'destroy' => 'hardselling-footers.destroy',
    ]);
});


require __DIR__ . '/auth.php';
