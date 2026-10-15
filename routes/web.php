<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', ProductController::class);

Route::get('/products/{product}/stock/create', [StockMovementController::class, 'create'])
    ->name('products.stock.create');

Route::post('/products/{product}/stock', [StockMovementController::class, 'store'])
    ->name('products.stock.store');