<?php

use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

//Paginas do CRUD - Coupons (Admin)
Route::get('/coupons', [CouponController::class, 'index'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.index');

Route::get('/coupons/create', [CouponController::class, 'create'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.create');

Route::post('/coupons/store', [CouponController::class, 'store'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.store');

Route::get('/coupons/edit/{id?}', [CouponController::class, 'edit'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.edit');

Route::put('/coupons/update/{id?}', [CouponController::class, 'update'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.update');

Route::delete('/coupons/destroy/{id}', [CouponController::class, 'destroy'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.destroy');

Route::get('/coupons/show/{id}', [CouponController::class, 'show'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.show');

Route::get('/coupons/admin_index', [CouponController::class, 'index_coupon'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('coupons.admin_index');

//Aplicar e remover cupons

Route::post('/coupons/applyCoupon', [CouponController::class, 'applyCoupon'])
    ->middleware(['auth', 'verified'])
    ->name('apply.coupons');

Route::delete('remove-tag-from-cart', [CouponController::class, 'remove'])
    ->middleware(['auth', 'verified'])
    ->name('remove.tag.from.cart');
