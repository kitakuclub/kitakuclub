<?php

declare(strict_types=1);

namespace App\Models\Concerns\Episodes;

use App\Models\Release;
use App\Models\Season;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasEpisodeRelationships
{
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function releases(): BelongsToMany
    {
        return $this->belongsToMany(Release::class, 'episode_release', 'episode_id', 'release_id');
    }
}
