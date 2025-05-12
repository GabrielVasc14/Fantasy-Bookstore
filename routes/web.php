<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

//Rota de entrada no site
Route::get('/', function () {
    return view('welcome');
});

//Rotas do breeze (registo e login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::get('/profile/show', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

//Ao logar comoo admin sera redirecionado para esta pagina
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/books', [AdminController::class, 'crud'])
        ->name('books.index');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/books.php';
require __DIR__ . '/cart.php';
require __DIR__ . '/coupons.php';
require __DIR__ . '/checkout.php';
require __DIR__ . '/reviews.php';
require __DIR__ . '/rewards.php';
require __DIR__ . '/challenges.php';
