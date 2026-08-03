<?php

declare(strict_types=1);

use App\Http\Controllers\AnimeCatalogController;
use App\Http\Controllers\AnimeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/animes', 'as' => 'animes'], function () {

    Route::get('/', [AnimeController::class, 'index']);
    Route::get('/catalog', [AnimeCatalogController::class, 'index'])->name('.catalog');
    Route::get('/{anime}/{slug}', [AnimeController::class, 'show'])->name('.show');

});
