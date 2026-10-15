<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Customer\CateringController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Merchant\MenuController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\Merchant\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// ---- Tamu (belum login) ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:6,1')->name('login.store');

    Route::get('/register/merchant', [RegisterController::class, 'showMerchant'])->name('register.merchant');
    Route::post('/register/merchant', [RegisterController::class, 'storeMerchant'])->name('register.merchant.store');

    Route::get('/register/customer', [RegisterController::class, 'showCustomer'])->name('register.customer');
    Route::post('/register/customer', [RegisterController::class, 'storeCustomer'])->name('register.customer.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// ---- Portal Merchant (Katering) ----
Route::middleware(['auth', 'role:merchant'])->prefix('merchant')->name('merchant.')->group(function () {
    Route::get('/', [MerchantOrderController::class, 'dashboard'])->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('menus', MenuController::class)->except('show');

    Route::get('orders', [MerchantOrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/status', [MerchantOrderController::class, 'updateStatus'])->name('orders.status');
});

// ---- Portal Customer (Kantor) ----
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::redirect('/', '/customer/katering')->name('dashboard');

    Route::get('katering', [CateringController::class, 'index'])->name('caterings.index');
    Route::get('katering/{merchant}', [CateringController::class, 'show'])->name('caterings.show');
    Route::post('katering/{merchant}/order', [CustomerOrderController::class, 'store'])->name('orders.store');

    Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
});

// ---- Invoice (merchant & customer pemilik order) ----
Route::get('/invoices/{order}', [InvoiceController::class, 'show'])->middleware('auth')->name('invoices.show');
