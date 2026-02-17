<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    CartController,
    OrderController,
    CateringRequestController
};
use App\Http\Controllers\Client\DashboardClientController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/client/dashboard', [DashboardClientController::class, 'index'])->name('client.dashboard');
Route::get('/client/orders')->name('client.orders');
Route::get('/cart')->name('cart.index');


Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::post('/order', [OrderController::class, 'store'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
