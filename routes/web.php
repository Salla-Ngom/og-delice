<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    CartController,
    OrderController,
    CateringRequestController
};
use App\Http\Controllers\Client\DashboardClientController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminLiveController;
use App\Http\Controllers\Vendeur\VendeurOrderController;
use App\Http\Controllers\Admin\AdminCateringController;

Route::get('/traiteur', [CateringRequestController::class, 'create'])
    ->name('traiteur.create');

Route::post('/traiteur', [CateringRequestController::class, 'store'])
    ->name('traiteur.store');

Route::get('/traiteur/confirmation', [CateringRequestController::class, 'confirmation'])
    ->name('traiteur.confirmation');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [ProductController::class, 'menu'])->name('menu');

Route::middleware('auth')->get('/dashboard', function () {
    return match(auth()->user()->role) {
        'admin'   => redirect()->route('admin.dashboard'),
        'vendeur' => redirect()->route('vendeur.pos'),
        default   => redirect()->route('client.dashboard'),
    };
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (auth + admin middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', AdminProductController::class);

        Route::resource('orders', AdminOrderController::class)
            ->only(['index', 'show']);

        Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        Route::resource('users', AdminUserController::class);

        Route::put('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggleStatus');

        Route::get('notifications', [AdminOrderController::class, 'notifications'])
            ->name('notifications');

        Route::post('notifications/mark-all-read', [AdminOrderController::class, 'markAllRead'])
            ->name('notifications.markAllRead');

        Route::get('live/poll', [AdminLiveController::class, 'poll'])
            ->name('live.poll');

        // ✅ Traiteur — dans le groupe admin
        Route::resource('catering', AdminCateringController::class)
            ->only(['index', 'show', 'destroy']);

        Route::patch('catering/{catering}/respond', [AdminCateringController::class, 'respond'])
            ->name('catering.respond');

        Route::patch('catering/{catering}/status', [AdminCateringController::class, 'updateStatus'])
            ->name('catering.updateStatus');
    });


/*
|--------------------------------------------------------------------------
| CLIENT ROUTES (auth + client middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', [DashboardClientController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders', [DashboardClientController::class, 'orders'])
            ->name('orders');

        Route::get('/orders/{order}', [DashboardClientController::class, 'show'])
            ->name('orders.show');

        // ✅ Polling statut — appelé toutes les 20s par show.blade.php
        Route::get('/orders/{order}/status', [DashboardClientController::class, 'pollStatus'])
            ->name('orders.pollStatus');
    });


/*
|--------------------------------------------------------------------------
| CART + CHECKOUT (auth seulement)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');
});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'vendeur'])
    ->prefix('vendeur')
    ->name('vendeur.')
    ->group(function () {

        // Interface POS (caisse)
        Route::get('/pos', [VendeurOrderController::class, 'pos'])
            ->name('pos');

        // Enregistrer une vente (JSON)
        Route::post('/pos', [VendeurOrderController::class, 'store'])
            ->name('pos.store');

        // Historique des ventes
        Route::get('/orders', [VendeurOrderController::class, 'index'])
            ->name('orders.index');

        // Reçu PDF
        Route::get('/orders/{order}/receipt', [VendeurOrderController::class, 'receipt'])
            ->name('orders.receipt');
    });


require __DIR__.'/auth.php';
