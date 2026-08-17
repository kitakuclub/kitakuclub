<?php

namespace App\Providers;

use App\Models\Anime;
use App\Repositories\AnimeRepository;
use App\Repositories\Contracts\AnimeRepository as AnimeRepositoryContract;
use App\Services\AnimeFeedService;
use App\Services\Contracts\AnimeFeedService as AnimeFeedServiceContract;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AnimeRepositoryContract::class, AnimeRepository::class);
        $this->app->bind(AnimeFeedServiceContract::class, AnimeFeedService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'anime' => Anime::class,
        ]);
    }
}
