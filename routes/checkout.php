<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkoutForm'])
        ->middleware(['auth', 'verified'])
        ->name('orders.checkout');

    Route::post('/checkout', [OrderController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('orders.store');

    Route::get('/meus_pedidos', [OrderController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('orders.index');

    Route::post('/orders/{order}/pay', [OrderController::class, 'pay'])
        ->middleware(['auth', 'verified'])
        ->name('orders.pay');
});
