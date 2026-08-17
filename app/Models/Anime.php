<?php

declare(strict_types=1);

namespace App\Models;

use App\AnimePosterPathGenerator;
use App\Builders\AnimeBuilder;
use App\Casts\AnimeKindCast;
use App\Casts\AnimeRatingCast;
use App\Casts\AnimeStatusCast;
use App\Models\Concerns\MorphsToReleases;
use App\Models\Concerns\MorphsToSources;
use Database\Factories\AnimeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidPathGenerator;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;

#[Fillable([
    'kind',
    'rating',
    'status',
    'name',
    'slug',
    'aired_at',
    'aired_year',
    'aired_season',
    'released_at',
    'episodes_total',
    'episodes_aired',
    'duration',
    'next_episode_at',
])]
#[UseEloquentBuilder(AnimeBuilder::class)]
class Anime extends Model implements HasMedia
{
    /** @use HasFactory<AnimeFactory> */
    use HasFactory, InteractsWithMedia, MorphsToSources, MorphsToReleases;

    /** @throws InvalidPathGenerator */
    protected static function booting(): void
    {
        PathGeneratorFactory::setCustomPathGenerators(static::class, AnimePosterPathGenerator::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'kind' => AnimeKindCast::class,
            'rating' => AnimeRatingCast::class,
            'status' => AnimeStatusCast::class,
            'aired_at' => 'datetime',
            'released_at' => 'datetime',
            'next_episode_at' => 'datetime',
        ];
    }

    // @mago-ignore lint:no-redundant-method-override
    public static function query(): AnimeBuilder
    {
        /** @var AnimeBuilder */
        return parent::query();
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('poster')
            ->useFallbackUrl(asset(config('noilty.shikimori_host') . '/assets/globals/missing/main@2x.png'))
            ->useDisk('public')
            ->singleFile();
    }
}
