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

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [ProductController::class, 'menu'])
    ->name('menu');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (auth + admin middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Produits
        Route::resource('products', AdminProductController::class);

        // Commandes
        Route::resource('orders', AdminOrderController::class)
            ->only(['index', 'show']);

        Route::put('orders/{order}/status',
            [AdminOrderController::class, 'updateStatus'])
            ->name('orders.updateStatus');

        // Utilisateurs
        Route::resource('users', AdminUserController::class);

        Route::put('users/{user}/toggle-status',
            [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggleStatus');

        // Notifications
        Route::get('notifications', function () {
            return view('admin.notifications');
        })->name('notifications');
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

        Route::get('/dashboard',
            [DashboardClientController::class, 'index'])
            ->name('dashboard');

        Route::get('/orders',
            [DashboardClientController::class, 'orders'])
            ->name('orders');

        Route::get('/orders/{order}',
            [DashboardClientController::class, 'show'])
            ->name('orders.show');
});


/*
|--------------------------------------------------------------------------
| CART + CHECKOUT (auth seulement)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/cart',
        [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add/{id}',
        [CartController::class, 'add'])
        ->name('cart.add');

    Route::patch('/cart/update/{id}',
        [CartController::class, 'update'])
        ->name('cart.update');

    Route::delete('/cart/remove/{id}',
        [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::post('/checkout',
        [OrderController::class, 'store'])
        ->name('checkout');
});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile',
        [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__.'/auth.php';
