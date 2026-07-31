<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\AnimeBuilder;
use App\Models\Concerns\MorphsToReleases;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\AnimeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kind',
    'rating',
    'status',
    'name',
    'slug',
    'aired_at',
    'released_at',
    'episodes_total',
    'episodes_aired',
    'duration',
    'next_episode_at',
])]
#[UseEloquentBuilder(AnimeBuilder::class)]
class Anime extends Model
{
    /** @use HasFactory<AnimeFactory> */
    use HasFactory, MorphsToSources, MorphsToReleases;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): AnimeBuilder
    {
        /** @var AnimeBuilder */
        return parent::query();
    }
}
