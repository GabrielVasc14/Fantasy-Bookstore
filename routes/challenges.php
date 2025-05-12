<?php

use App\Http\Controllers\ChallengeController;
use App\Models\Challenge;
use Illuminate\Support\Facades\Route;

//Crud routes for challenges
Route::get('/challenges/crud', [ChallengeController::class, 'index_crud'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.index_crud');

Route::get('/challenges/create', [ChallengeController::class, 'create'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.create');

Route::post('/challenges/store', [ChallengeController::class, 'store'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.store');

Route::get('/challenges/edit/{id?}', [ChallengeController::class, 'edit'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.edit');

Route::put('/challenges/update/{id?}', [ChallengeController::class, 'update'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.update');

Route::delete('/challenges/destroy/{id}', [ChallengeController::class, 'destroy'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.destroy');

Route::get('/challenges/show/{id}', [ChallengeController::class, 'show'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('challenges.show');


//Routes for users to view and complete challenges
Route::get('/challenges', [ChallengeController::class, 'index'])
    ->middleware('auth', 'verified')
    ->name('challenges.index');

Route::post('/challenges/increment/{id}', [ChallengeController::class, 'increment'])
    ->middleware('auth', 'verified')
    ->name('challenges.increment');
