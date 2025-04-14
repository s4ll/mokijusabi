<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Role;
use Illuminate\Support\Facades\Route;


Route::get('/', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'loginAuth'])->name('loginAuth');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::middleware([Role::class . ':admin'])->prefix('user/')->name('user.')->group(function(){
         //user routes
         Route::get('', [UserController::class, 'index'])->name('index');
         Route::get('create', [UserController::class, 'create'])->name('create');
         Route::post('store', [UserController::class, 'store'])->name('store');
         Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
         Route::put('update/{id}', [UserController::class, 'update'])->name('update');
         Route::delete('delete/{id}', [UserController::class, 'destroy'])->name('delete');
    });

    Route::middleware([Role::class . ':admin,employee'])->group(function(){
        Route::prefix('product/')->name('product.')->group(function(){
            Route::get('', [ProductController::class, 'index'])->name('index');
            Route::get('create', [ProductController::class, 'create'])->name('create');
            Route::post('store', [ProductController::class, 'store'])->name('store');
            Route::get('edit/{id}', [ProductController::class, 'edit'])->name('edit');
            Route::put('update/{id}', [ProductController::class, 'update'])->name('update');
            // Route::get('editStock/{id}', [ProductController::class, 'editStock'])->name('editStock');
            Route::patch('updateStock/{id}', [ProductController::class, 'updateStock'])->name('updateStock');
            Route::delete('delete/{id}', [ProductController::class, 'destroy'])->name('delete');
        });

        Route::prefix('purchase/')->name('purchase.')->group(function(){
            Route::get('', [PurchaseController::class, 'index'])->name('index');
            
            Route::get('create', [PurchaseController::class, 'create'])->name('create');
            Route::post('create', [PurchaseController::class, 'createStore'])->name('createStore');
            
            Route::get('cart', [PurchaseController::class, 'cart'])->name('cart');
            Route::post('cart', [PurchaseController::class, 'cartStore'])->name('cartStore');
        
            Route::get('order', [PurchaseController::class, 'order'])->name('order');
            Route::post('order', [PurchaseController::class, 'orderStore'])->name('orderStore');
        
            Route::get('detail', [PurchaseController::class, 'detail'])->name('detail');

            Route::get('export', [PurchaseController::class, 'export'])->name('export');
            Route::get('downloadReceipt/{purchase}', [PurchaseController::class, 'downloadReceipt'])->name('downloadReceipt');
        });
    });
});

Route::any('{any}', function () {
    return response()->view('pages.notfound', [], 404);
})->where('any', '.*');
