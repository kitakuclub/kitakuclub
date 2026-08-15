<?php

declare(strict_types=1);

namespace App\Casts;

use App\Enums\EntryStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AnimeStatusCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): EntryStatus
    {
        return EntryStatus::from($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $status = $value instanceof EntryStatus ? $value : EntryStatus::tryFrom($value);

        return $status->value;
    }
}
