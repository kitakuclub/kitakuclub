<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Release;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MorphsToReleases
{
    public function releases(): MorphMany
    {
        return $this->morphMany(Release::class, 'releasable');
    }
}
