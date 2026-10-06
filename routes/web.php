<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminExpenseController;
use App\Http\Controllers\AdminInventoryController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminMasterDataController;
use App\Http\Controllers\AdminSupplierController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\CustomerOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'home'])->name('home');

Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{unit}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{unit}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CartController::class, 'store'])->name('checkout.store');

Route::get('/pesanan-saya', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
Route::get('/pesanan-saya/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
Route::post('/pesanan-saya/{order}/bukti-pembayaran', [CustomerOrderController::class, 'uploadProof'])->name('customer.orders.proof');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:admin-login')->name('admin.login.store');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/account', [AdminSettingsController::class, 'updateAccount'])->name('settings.account.update');
    Route::put('/settings/password', [AdminSettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::get('/notifications/orders', [AdminController::class, 'newOrders'])->name('notifications.orders');
    Route::get('/modul/{module}', [AdminController::class, 'module'])->name('module');
    Route::post('/products/{product}/images', [AdminProductController::class, 'uploadImages'])->name('products.images.upload');
    Route::patch('/products/{product}/images/{image}/thumbnail', [AdminProductController::class, 'setThumbnail'])->name('products.images.thumbnail');
    Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('/stock', [AdminInventoryController::class, 'index'])->name('stock.index');
    Route::get('/stock/reconciliation', [AdminInventoryController::class, 'reconciliation'])->name('stock.reconciliation');
    Route::get('/stock/{product}', [AdminInventoryController::class, 'show'])->name('stock.show');
    Route::post('/stock/{product}/adjustments', [AdminInventoryController::class, 'adjust'])->name('stock.adjustments.store');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}/payments/{payment}/proof', [AdminOrderController::class, 'proof'])->name('orders.payments.proof');
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}', [AdminPaymentController::class, 'update'])->name('payments.update');
    Route::resource('expenses', AdminExpenseController::class);
    Route::get('/activities', [AdminOrderController::class, 'activities'])->name('activities.index');
    Route::get('/master/{module}', [AdminMasterDataController::class, 'index'])->name('master.index');
    Route::post('/master/{module}', [AdminMasterDataController::class, 'store'])->name('master.store');
    Route::patch('/master/{module}/{record}', [AdminMasterDataController::class, 'update'])->name('master.update');
    Route::delete('/master/{module}/{record}', [AdminMasterDataController::class, 'destroy'])->name('master.destroy');
    Route::get('/suppliers', [AdminSupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [AdminSupplierController::class, 'store'])->name('suppliers.store');
    Route::patch('/suppliers/{supplier}', [AdminSupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [AdminSupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
});
