<?php

declare(strict_types=1);

use App\Models\Anime;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Главная', '/');
});

Breadcrumbs::for('animes', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Аниме', route('animes'), []);
});

Breadcrumbs::for('animes.catalog', function (BreadcrumbTrail $trail) {
    $trail->parent('animes');
    $trail->push('Каталог', route('animes.catalog'));
});

Breadcrumbs::for('animes.show', function (BreadcrumbTrail $trail, Anime $anime) {
    $trail->parent('animes');
    $trail->push($anime->kind->label(), '/animes/search?kind=' . $anime->kind->value);
    $trail->push($anime->aired_at->format('Y') . ' года', '/animes/search?year=' . $anime->aired_at->format('Y'));
    $trail->push($anime->name, route('animes.show', [$anime, $anime->slug]));
});
