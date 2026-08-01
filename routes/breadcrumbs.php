<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Главная', '/');
});

Breadcrumbs::for('animes', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Аниме', route('animes'),[]);
});

Breadcrumbs::for('animes.catalog', function (BreadcrumbTrail $trail) {
    $trail->parent('animes');
    $trail->push('Каталог', route('animes.catalog'),[]);
});
