<?php

use App\Models\Anime;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

//    /** @var Anime $anime */
    $anime = Anime::with(['sources'])->findOrFail($_GET['anime']);
//\Illuminate\Support\Facades\DB::table('releases')->delete();
    dd(
        $anime->toArray(),
        $anime->releases->toArray(),
        $anime->releases[2]->funteam->toArray(),
    );

    return view('welcome');
});
