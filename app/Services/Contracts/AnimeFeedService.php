<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Values\AnimeFeedLatestUpdateData;
use App\Values\AnimeFeedLatestReleaseData;
use Illuminate\Support\Collection;

interface AnimeFeedService
{
    /** @return Collection<int, AnimeFeedLatestUpdateData> */
    public function latestUpdates(int $limit = 10, int $bufferDays = 7): Collection;

    /** @return Collection<int, AnimeFeedLatestReleaseData> */
    public function latestReleases(int $limit = 10): Collection;
}
