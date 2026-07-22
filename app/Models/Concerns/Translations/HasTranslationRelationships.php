<?php

declare(strict_types=1);

namespace App\Models\Concerns\Translations;

use App\Models\Episode;
use App\Models\Season;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslationRelationships
{
    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class);
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(
            Episode::class,
            'translation_episode',
            'translation_id',
            'episode_id'
        );
    }
}
