<?php

declare(strict_types=1);

namespace App\Models\Concerns\Seasons;

use App\Models\Episode;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSeasonRelationships
{
    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
