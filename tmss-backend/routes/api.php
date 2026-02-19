<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\TagController;

Route::prefix('v1')->group(function () {
    // Translations
    Route::apiResource('translations', TranslationController::class);
    Route::get('translations/export/json', [TranslationController::class, 'exportJson']);
    Route::get('translations/search', [TranslationController::class, 'search']);
    
    // Locales
    Route::apiResource('locales', LocaleController::class);
    
    // Tags
    Route::apiResource('tags', TagController::class);
});
