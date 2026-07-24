<?php

use App\Models\Anime;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $anime = Anime::with(['sources'])->findOrFail(444);

    dd(
        $anime,
    );

    return view('welcome');
});
