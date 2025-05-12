<?php

use App\Http\Controllers\RewardController;
use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Support\Facades\Route;

//Crud routes for rewards
Route::get('/rewards/crud', [RewardController::class, 'index_crud'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.index_crud');

Route::get('/rewards/create', [RewardController::class, 'create'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.create');

Route::post('/rewards/store', [RewardController::class, 'store'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.store');

Route::get('/rewards/edit/{id?}', [RewardController::class, 'edit'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.edit');

Route::put('/rewards/update/{id?}', [RewardController::class, 'update'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.update');

Route::delete('/rewards/destroy/{id}', [RewardController::class, 'destroy'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.destroy');

Route::get('/rewards/show/{id}', [RewardController::class, 'show'])
    ->middleware('auth', 'role:admin', 'verified')
    ->name('rewards.show');

//Routes for users to view and redeem rewards
Route::get('/rewards', [RewardController::class, 'index'])
    ->middleware('auth', 'verified')
    ->name('rewards.index');

Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])
    ->middleware('auth', 'verified')
    ->name('rewards.redeem');
