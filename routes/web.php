<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::resource('activities', ActivityController::class);
Route::post('activities/{activity}/publish', [ActivityController::class, 'publish'])
    ->name('activities.publish');
Route::post('activities/{activity}/complete', [ActivityController::class, 'complete'])
    ->name('activities.complete');

Route::resource('categories', CategoryController::class)->except('show');