<?php

use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC CONTROLLERS
// ============================================================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ProductVideoController;

// ============================================================
// ADMIN CONTROLLERS
// ============================================================
use App\Http\Controllers\Admin\DashboardController    as AdminDashboardController;
use App\Http\Controllers\Admin\UserController         as AdminUserController;
use App\Http\Controllers\Admin\CategoryController     as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController      as AdminProductController;
use App\Http\Controllers\Admin\OrderController        as AdminOrderController;
use App\Http\Controllers\Admin\SettingController      as AdminSettingController;
use App\Http\Controllers\Admin\CarouselController     as AdminCarouselController;
use App\Http\Controllers\Admin\VoucherController      as AdminVoucherController;
use App\Http\Controllers\Admin\ReviewController       as AdminReviewController;
use App\Http\Controllers\Admin\VideoController        as AdminVideoController;

// ============================================================
// PENJUAL CONTROLLERS
// ============================================================
use App\Http\Controllers\Penjual\DashboardController  as PenjualDashboardController;
use App\Http\Controllers\Penjual\ProductController    as PenjualProductController;
use App\Http\Controllers\Penjual\OrderController      as PenjualOrderController;
use App\Http\Controllers\Penjual\VoucherController    as PenjualVoucherController;
use App\Http\Controllers\Penjual\ReviewController     as PenjualReviewController;
use App\Http\Controllers\Penjual\VideoController      as PenjualVideoController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('category');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
});

// Shorts Video (Public - Shopee Style Video Feed)
Route::prefix('shorts')->name('videos.')->group(function () {
    Route::get('/', [ProductVideoController::class, 'index'])->name('index');
    Route::get('/{video}', [ProductVideoController::class, 'show'])->name('show');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ----------------------------------------------------------
    // CART
    // ----------------------------------------------------------
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/',          [CartController::class, 'index'])->name('index');
        Route::post('/add',      [CartController::class, 'add'])->name('add');
        Route::patch('/{cart}',  [CartController::class, 'update'])->name('update');
        Route::delete('/{cart}', [CartController::class, 'destroy'])->name('destroy');
        Route::delete('/',       [CartController::class, 'clear'])->name('clear');
    });

    // ----------------------------------------------------------
    // CHECKOUT
    // ----------------------------------------------------------
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/',          [CheckoutController::class, 'index'])->name('index');
        Route::post('/process',  [CheckoutController::class, 'process'])->name('process');
    });

    // ----------------------------------------------------------
    // VOUCHER CHECK (AJAX)
    // ----------------------------------------------------------
    Route::post('/voucher/check', [VoucherController::class, 'check'])->name('voucher.check');

    // ----------------------------------------------------------
    // ORDERS
    // ----------------------------------------------------------
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/',                   [OrderController::class, 'index'])->name('index');
        Route::get('/{order}',            [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/cancel',   [OrderController::class, 'cancel'])->name('cancel');
    });

    // ----------------------------------------------------------
    // REVIEWS (PEMBELI)
    // ----------------------------------------------------------
    Route::prefix('reviews')->name('reviews.')->group(function () {
        // Create & Store review
        Route::get('/orders/{order}/products/{product}/create',
            [ReviewController::class, 'create'])->name('create');
        Route::post('/orders/{order}/products/{product}',
            [ReviewController::class, 'store'])->name('store');

        // Edit & Update review
        Route::get('/{review}/edit',  [ReviewController::class, 'edit'])->name('edit');
        Route::patch('/{review}',     [ReviewController::class, 'update'])->name('update');

        // Delete review
        Route::delete('/{review}',    [ReviewController::class, 'destroy'])->name('destroy');

        // Reply to review (Admin & Penjual)
        Route::post('/{review}/reply',          [ReviewController::class, 'reply'])->name('reply');
        Route::delete('/replies/{reply}',       [ReviewController::class, 'deleteReply'])->name('reply.delete');
    });

    // ----------------------------------------------------------
    // VIDEO INTERACTIONS (Like & Comment)
    // ----------------------------------------------------------
    Route::post('/shorts/{video}/like',    [ProductVideoController::class, 'like'])->name('videos.like');
    Route::post('/shorts/{video}/comment', [ProductVideoController::class, 'comment'])->name('videos.comment');

    // ----------------------------------------------------------
    // PROFILE
    // ----------------------------------------------------------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',                   [ProfileController::class, 'index'])->name('index');
        Route::get('/edit',               [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update',           [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password',    [ProfileController::class, 'changePassword'])->name('change-password');
        Route::patch('/update-password',  [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // ----------------------------------------------------------
    // DASHBOARD
    // ----------------------------------------------------------
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------
    // WEBSITE SETTINGS
    // ----------------------------------------------------------
    Route::get('/settings',  [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // ----------------------------------------------------------
    // CAROUSEL
    // ----------------------------------------------------------
    Route::resource('carousels', AdminCarouselController::class);

    // ----------------------------------------------------------
    // VOUCHERS
    // ----------------------------------------------------------
    Route::resource('vouchers', AdminVoucherController::class);
    Route::post('/vouchers/{voucher}/toggle',
        [AdminVoucherController::class, 'toggleStatus'])->name('vouchers.toggle');

    // ----------------------------------------------------------
    // REVIEWS
    // ----------------------------------------------------------
    Route::get('/reviews',                              [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}',                     [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/toggle',             [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggle');
    Route::delete('/reviews/{review}',                  [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/reply',              [AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/replies/{reply}',           [AdminReviewController::class, 'deleteReply'])->name('reviews.reply.delete');

    // ----------------------------------------------------------
    // VIDEOS
    // ----------------------------------------------------------
    Route::get('/videos',                               [AdminVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/create',                        [AdminVideoController::class, 'create'])->name('videos.create');
    Route::post('/videos',                              [AdminVideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}',                       [AdminVideoController::class, 'show'])->name('videos.show');
    Route::post('/videos/{video}/toggle',               [AdminVideoController::class, 'toggleStatus'])->name('videos.toggle');
    Route::delete('/videos/{video}',                    [AdminVideoController::class, 'destroy'])->name('videos.destroy');

    // ----------------------------------------------------------
    // USERS
    // ----------------------------------------------------------
    Route::resource('users', AdminUserController::class);

    // ----------------------------------------------------------
    // CATEGORIES
    // ----------------------------------------------------------
    Route::resource('categories', AdminCategoryController::class);

    // ----------------------------------------------------------
    // PRODUCTS (View & Delete only - Edit by Penjual)
    // ----------------------------------------------------------
    Route::get('/products',              [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}',    [AdminProductController::class, 'show'])->name('products.show');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // ----------------------------------------------------------
    // ORDERS
    // ----------------------------------------------------------
    Route::get('/orders',                                [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',                        [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status',               [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/payment',              [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
});

/*
|--------------------------------------------------------------------------
| PENJUAL ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:penjual'])
    ->prefix('penjual')
    ->name('penjual.')
    ->group(function () {

    // ----------------------------------------------------------
    // DASHBOARD
    // ----------------------------------------------------------
    Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------
    // PRODUCTS
    // ----------------------------------------------------------
    Route::resource('products', PenjualProductController::class);

    // ----------------------------------------------------------
    // ORDERS
    // ----------------------------------------------------------
    Route::get('/orders',         [PenjualOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [PenjualOrderController::class, 'show'])->name('orders.show');

    // ----------------------------------------------------------
    // VOUCHERS (Read Only)
    // ----------------------------------------------------------
    Route::get('/vouchers',           [PenjualVoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/{voucher}', [PenjualVoucherController::class, 'show'])->name('vouchers.show');

    // ----------------------------------------------------------
    // REVIEWS
    // ----------------------------------------------------------
    Route::get('/reviews',                              [PenjualReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}',                     [PenjualReviewController::class, 'show'])->name('reviews.show');
    Route::post('/reviews/{review}/reply',              [PenjualReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/replies/{reply}',           [PenjualReviewController::class, 'deleteReply'])->name('reviews.reply.delete');

    // ----------------------------------------------------------
    // VIDEOS
    // ----------------------------------------------------------
    Route::get('/videos',                               [PenjualVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/create',                        [PenjualVideoController::class, 'create'])->name('videos.create');
    Route::post('/videos',                              [PenjualVideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}',                       [PenjualVideoController::class, 'show'])->name('videos.show');
    Route::delete('/videos/{video}',                    [PenjualVideoController::class, 'destroy'])->name('videos.destroy');
});
