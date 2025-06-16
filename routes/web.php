<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SiteController;
use App\Models\Category;
use App\Models\Product;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('orders', [AdminController::class, 'orders'])->name('orders.indexAdmin');
    Route::post('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/categorias', [SiteController::class, 'categories'])->name('categories');

Route::get('/cart/{category_id?}', [SiteController::class, 'shop'])->name('shop');
Route::get('/checkout', [SiteController::class, 'checkout'])->name('checkout');

Route::get('/order/success', [SiteController::class, 'orderSuccess'])->name('orders.success');

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::post('/checkout/mercado-pago', [CheckoutController::class, 'processarPagamento'])
    ->middleware('mercado_pago_enabled')
    ->name('checkout.mercado_pago');

Route::get('/checkout/success', function () {
    return view('checkout.success'); // Página de sucesso
})->name('checkout.success');

Route::get('/checkout/fail', function () {
    return view('checkout.fail'); // Página de falha
})->name('checkout.fail');

Route::get('/meus-pedidos', [OrderController::class, 'index'])->name('meus_pedidos');
