<?php

declare(strict_types=1);

use App\Models\Anime;
use App\Models\Release;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Главная', '/', []);
});

Breadcrumbs::for('animes', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Аниме', route('animes'));
});

Breadcrumbs::for('animes.catalog', function (BreadcrumbTrail $trail) {
    $trail->parent('animes');
    $trail->push('Каталог', route('animes.catalog'));
});

Breadcrumbs::for('animes.search', function (BreadcrumbTrail $trail) {
    $trail->parent('animes');
    $trail->push('Поиск', route('animes.search'));
});

Breadcrumbs::for('animes.show', function (BreadcrumbTrail $trail, Anime $anime) {
    $trail->parent('animes');
    $trail->push($anime->kind->label(), route('animes.search', ['kind' => $anime->kind]));
    $trail->push($anime->aired_year . ' года', route('animes.search', ['year' => $anime->aired_year]));
    $trail->push($anime->name, route('animes.show', [$anime, $anime->slug]));
});

Breadcrumbs::for('animes.watch', function (BreadcrumbTrail $trail, Anime $anime, Release $release) {
    $trail->parent('animes.show', $anime);
    $trail->push($release->translation->funteam->name);
});
