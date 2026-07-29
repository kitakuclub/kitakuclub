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
        $anime->releases[10]->funteam->toArray(),
        $anime->releases[10]->episodes->toArray(),
    );

    return view('welcome');
});
