<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'apiAdd']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'apiRemove']);
    Route::post('/order/place', [OrderController::class, 'apiPlace']);
    Route::get('/products', [ProductController::class, 'apiIndex']);
});