<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    // Auth - generate token (public)
    Route::post('auth/token', [AuthController::class, 'generateToken']);
    
    // Protected routes - require valid API token
    Route::middleware('api.token')->group(function () {
        // Translations
        Route::apiResource('translations', TranslationController::class);
        Route::get('translations/export/json', [TranslationController::class, 'exportJson']);
        Route::get('translations/search', [TranslationController::class, 'search']);
        
        // Locales
        Route::apiResource('locales', LocaleController::class);
        
        // Tags
        Route::apiResource('tags', TagController::class);
    });
});
