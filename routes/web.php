<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('activities.index'));

Route::get('activities/trash', [ActivityController::class, 'trash'])->name('activities.trash');
Route::patch('activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');

Route::resource('activities', ActivityController::class);
Route::post('activities/{activity}/publish', [ActivityController::class, 'publish'])
    ->name('activities.publish');
Route::post('activities/{activity}/complete', [ActivityController::class, 'complete'])
    ->name('activities.complete');

Route::resource('categories', CategoryController::class)->except('show');