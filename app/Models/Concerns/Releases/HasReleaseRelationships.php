<?php

declare(strict_types=1);

namespace App\Models\Concerns\Releases;

use App\Models\Episode;
use App\Models\Season;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasReleaseRelationships
{
    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class);
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class, 'release_id');
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'episode_release', 'release_id', 'episode_id');
    }
}
