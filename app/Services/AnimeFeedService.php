<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\AnimeRepository;
use App\Services\Contracts\AnimeFeedService as AnimeFeedServiceContract;
use App\Values\AnimeFeedLatestReleaseData;
use App\Values\AnimeFeedLatestUpdateData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class AnimeFeedService implements AnimeFeedServiceContract
{
    public function __construct(
        private AnimeRepository $animes
    )
    {
    }

    /** @inheritDoc */
    public function latestUpdates(int $limit = 10, int $bufferDays = 7): Collection
    {
        $cached = Cache::remember(
            "anime.latest_updates.{$limit}.{$bufferDays}",
            now()->addMinutes(7),
            fn () => $this->animes->latestUpdates($limit, $bufferDays)
                ->map(fn (AnimeFeedLatestUpdateData $dto) => $dto->toArray())
                ->all(),
        );

        return collect($cached)->map(AnimeFeedLatestUpdateData::fromArray(...));
    }

    /** @inheritDoc */
    public function latestReleases(int $limit = 10): Collection
    {
        $cached = Cache::remember(
            "anime.latest_releases.{$limit}",
            now()->addMinutes(7),
            fn () => $this->animes->latestReleases($limit)
                ->map(fn (AnimeFeedLatestReleaseData $dto) => $dto->toArray())
                ->all(),
        );

        return collect($cached)->map(AnimeFeedLatestReleaseData::fromArray(...));
    }
}
