<?php

use App\Http\Controllers\ReviewController;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::post('/reviews', [ReviewController::class, 'store'])
    ->middleware('auth', 'verified')
    ->name('reviews.store');

Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])
    ->middleware('auth', 'verified')
    ->name('reviews.destroy');

Route::post('/review-comments', [ReviewController::class, 'storeComment'])
    ->middleware('auth', 'verified')
    ->name('review-comments.store');
