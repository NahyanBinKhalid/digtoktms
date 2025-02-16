<?php

use App\Http\Controllers\Apis\V1\AuthController;
use App\Http\Controllers\Apis\V1\TranslationsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes Version 1
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('api.v1.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.v1.login');
    Route::post('forgot', [AuthController::class, 'forgot'])->name('api.v1.forgot');
    Route::post('reset', [AuthController::class, 'reset'])->name('api.v1.reset');

    Route::post('upload', [AuthController::class, 'upload'])->name('api.v1.upload');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile'])->name('api.v1.profile');
        Route::put('profile', [AuthController::class, 'update'])->name('api.v1.profile.update');
        Route::get('logout', [AuthController::class, 'logout'])->name('api.v1.logout');

        // Translations
        Route::get('translations', [TranslationsController::class, 'index'])->name('api.v1.translations.index');
        Route::post('translations', [TranslationsController::class, 'store'])->name('api.v1.translations.store');
        Route::get('translations/{id}', [TranslationsController::class, 'show'])->name('api.v1.translations.show');
        Route::put('translations/{id}', [TranslationsController::class, 'update'])->name('api.v1.translations.update');
        Route::delete('translations/{id}', [TranslationsController::class, 'destroy'])->name('api.v1.translations.destroy');
    });
});
