<?php

declare(strict_types=1);

namespace App\Models\Concerns\Releases;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasReleaseRelationships
{
    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class);
    }
}
