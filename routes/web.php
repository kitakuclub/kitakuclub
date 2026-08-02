<?php

use App\Models\Anime;
use Illuminate\Support\Facades\Route;

Route::group([], function () {
    require __DIR__ . '/web.animes.php';
});

Route::get('/', function () {

//    \Illuminate\Support\Facades\DB::table('episode_release')->delete();
//    \Illuminate\Support\Facades\DB::table('episodes')->delete();
//    \Illuminate\Support\Facades\DB::table('seasons')->delete();
//    \Illuminate\Support\Facades\DB::table('releases')->delete();

    /** @var Anime $anime */
    $anime = Anime::with(['sources'])->findOrFail($_GET['anime'] ?? 1);

    dd(
        $anime->toArray(),
        $anime->releases->toArray(),
//        $anime->releases[10]->funteam->toArray(),
//        $anime->releases[10]->episodes->toArray(),
        $anime->getFirstMediaUrl('poster'),
    );

    return view('welcome');
});
