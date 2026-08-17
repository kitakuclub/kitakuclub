<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Anime;
use App\Values\AnimeFeedLatestUpdateData;
use App\Values\AnimeFeedLatestReleaseData;
use Illuminate\Support\Collection;

/** @extends Repository<Anime> */
interface AnimeRepository extends Repository
{
    /** @return Collection<int, AnimeFeedLatestUpdateData> */
    public function latestUpdates(int $limit = 10, int $bufferDays = 7): Collection;

    /** @return Collection<int, AnimeFeedLatestReleaseData> */
    public function latestReleases(int $limit = 10): Collection;
}
