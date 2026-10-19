<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('products.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // プロフィール(Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 管理者・一般ユーザー共通
    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::get('products/{product}/stock/create', [StockMovementController::class, 'create'])->name('products.stock.create');
    Route::post('products/{product}/stock', [StockMovementController::class, 'store'])->name('products.stock.store');

    // 管理者のみ
    Route::middleware('can:admin')->group(function () {
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::resource('categories', CategoryController::class)->except(['create', 'show']);
    });
});



require __DIR__.'/auth.php';