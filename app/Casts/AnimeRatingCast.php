<?php

declare(strict_types=1);

namespace App\Casts;

use App\Enums\EntryRating;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AnimeRatingCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): EntryRating
    {
        return EntryRating::from($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $rating = $value instanceof EntryRating ? $value : EntryRating::tryFrom($value);

        return $rating->value;
    }
}
