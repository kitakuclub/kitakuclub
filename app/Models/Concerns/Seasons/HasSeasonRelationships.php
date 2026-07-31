<?php

declare(strict_types=1);

namespace App\Models\Concerns\Seasons;

use App\Models\Episode;
use App\Models\Release;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSeasonRelationships
{
    public function release(): BelongsTo
    {
        return $this->belongsTo(Release::class, 'release_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
