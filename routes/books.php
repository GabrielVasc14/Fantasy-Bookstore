<?php

use App\Http\Controllers\BooksController;
use App\Models\Books;
use Illuminate\Support\Facades\Route;

//Paginas do CRUD (Admin)
Route::get('/books', [BooksController::class, 'index'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.index');

Route::get('/books/create', [BooksController::class, 'create'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.create');

Route::post('/books/store', [BooksController::class, 'store'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.store');

Route::get('/books/edit/{id?}', [BooksController::class, 'edit'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.edit');

Route::put('/books/update/{id?}', [BooksController::class, 'update'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.update');

Route::delete('/books/destroy/{id}', [BooksController::class, 'destroy'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.destroy');

//Mostrar informacoes (admin ou user)
Route::get('/books/show/{id}', [BooksController::class, 'show'])
    ->name('books.show');

//Procurar e importar da API
Route::get('/books/search-google', [BooksController::class, 'searchGoogleBooks'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.search.google');

Route::post('/books/import', [BooksController::class, 'importFromGoogle'])
    ->middleware(['auth', 'role:admin', 'verified'])
    ->name('books.import.google');

//Pag do livro
Route::get('/books/{id}/details', [BooksController::class, 'details'])
    ->name('books.details');

//Pag de recomendacoes
Route::get('/books/recommendations', [BooksController::class, 'recommendations'])
    ->middleware(['auth', 'verified'])
    ->name('books.recommendations');

Route::get('/books/best-rated', [BooksController::class, 'bestRated'])
    ->name('books.bestRated');
