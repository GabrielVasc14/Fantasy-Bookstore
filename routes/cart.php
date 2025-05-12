<?php

use App\Http\Controllers\BooksController;
use Illuminate\Support\Facades\Route;

//Rotas do carrinho (admin e user)

Route::get('/index_cart', [BooksController::class, 'cart_index']);

Route::get('cart', [BooksController::class, 'cart'])
    ->middleware(['auth', 'verified'])
    ->name('cart');

Route::get('add-to-cart/{id}/{format?}', [BooksController::class, 'addToCart'])
    ->middleware(['auth', 'verified'])
    ->name('add.to.cart');

Route::patch('update-cart', [BooksController::class, 'cart_update'])
    ->middleware(['auth', 'verified'])
    ->name('update.cart');

Route::delete('remove-from-cart', [BooksController::class, 'remove'])
    ->middleware(['auth', 'verified'])
    ->name('remove.from.cart');

Route::delete('/books/destroyAll', [BooksController::class, 'destroyAll'])
    ->middleware(['auth', 'verified'])
    ->name('books.destroyAll');

Route::post('/books/toggle-like/{book}', [BooksController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('books.like');

Route::get('/wishlist', [BooksController::class, 'wishlist'])
    ->middleware(['auth', 'verified'])
    ->name('cart.wishlist');

Route::delete('/books/dislike/{book}', [BooksController::class, 'dislike'])
    ->middleware(['auth', 'verified'])
    ->name('books.dislike');
