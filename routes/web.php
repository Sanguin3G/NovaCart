<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\ConfirmationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings;
use Illuminate\Support\Facades\Route;

// Landing page: show product catalog
Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegistrationController::class, 'create'])->name('register-form');
    Route::post('register', [RegistrationController::class, 'store'])->name('register-post');

    Route::get('login', [LoginController::class, 'create'])->name('login-form');
    Route::post('login', [LoginController::class, 'store'])->middleware('login.throttle')->name('login-post');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('verify-email', [VerificationController::class, 'store'])->name('verification.store');
    Route::get('verify-email/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    Route::get('confirm-password', [ConfirmationController::class, 'create'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmationController::class, 'store'])->name('confirmation.store');
});

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth:admin,web', 'verified'])
    ->name('dashboard');

Route::middleware(['auth:admin,web'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    // Products CRUD and DataTables endpoints
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
    Route::match(['get', 'post'], 'products/data', [App\Http\Controllers\Admin\ProductController::class, 'getData'])->name('products.data');
    Route::get('products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
    Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    /* Orders */
    Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    // Update status
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    // Cancel order via AJAX
    Route::post('orders/{order}/cancel', [App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    // Orders list (DataTable)
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::match(['get', 'post'], 'orders/data', [App\Http\Controllers\Admin\OrderController::class, 'getData'])->name('orders.data');

    // Categories CRUD and DataTables endpoints
    Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::match(['get', 'post'], 'categories/data', [App\Http\Controllers\Admin\CategoryController::class, 'getData'])->name('categories.data');
    Route::get('categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::patch('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');

    // Product reviews
    Route::match(['get', 'post'], 'reviews/data', [App\Http\Controllers\Admin\ProductReviewController::class, 'data'])->name('reviews.data');
    Route::get('reviews', [App\Http\Controllers\Admin\ProductReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [App\Http\Controllers\Admin\ProductReviewController::class, 'show'])->name('reviews.show');
    Route::patch('reviews/{review}/disable', [App\Http\Controllers\Admin\ProductReviewController::class, 'disable'])->name('reviews.disable');
});

Route::post('logout', [LoginController::class, 'destroy'])->name('logout');


/*
|--------------------------------------------------------------------------
| Customer Facing Routes
|--------------------------------------------------------------------------
*/

// Customer product browsing
Route::get('/products', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [App\Http\Controllers\Customer\ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\CartController::class, 'view'])->name('view');
    Route::middleware('auth')->group(function () {
        Route::post('/add/{product}', [App\Http\Controllers\Customer\CartController::class, 'add'])->name('add');
        Route::post('/update/{productId}', [App\Http\Controllers\Customer\CartController::class, 'update'])->name('update');
        Route::post('/remove/{productId}', [App\Http\Controllers\Customer\CartController::class, 'remove'])->name('remove');
    });
});

// Checkout process
Route::prefix('checkout')->name('checkout.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Customer\CheckoutController::class, 'show'])->name('show');
    Route::post('/', [App\Http\Controllers\Customer\CheckoutController::class, 'process'])->name('process');
});


// Authenticated customer routes (dashboard, orders)
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('customer.dashboard');

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('show');
        Route::match(['get', 'post'], 'data', [App\Http\Controllers\Customer\OrderController::class, 'getData'])->name('data');

        // Cancel a pending order
        Route::post('/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('cancel');
    });
});

// -----------------------------------------------------------------------------
// Product reviews (customer-facing)
// -----------------------------------------------------------------------------

Route::post('products/{product}/reviews', [App\Http\Controllers\ProductReviewController::class, 'store'])->name('products.reviews.store');
Route::middleware('auth')->group(function () {
    Route::get('my/reviews/todo', [App\Http\Controllers\ProductReviewController::class, 'todo'])->name('reviews.todo');
    Route::get('my/reviews', [App\Http\Controllers\Customer\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('my/reviews/pending/data', [App\Http\Controllers\Customer\ReviewController::class, 'pendingData'])->name('reviews.pending');
    Route::get('my/reviews/mine/data', [App\Http\Controllers\Customer\ReviewController::class, 'mineData'])->name('reviews.mine');
});

Route::get('products/{product}/reviews/data', [App\Http\Controllers\ProductReviewController::class, 'list'])->name('products.reviews.list');
