<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnimeBuilder extends Builder
{
    public function whereHasSources(iterable $sources): self
    {
        $sources = $sources instanceof Collection ? $sources : collect($sources);

        if ($sources->isEmpty()) {
            return $this->whereRaw('1 = 0');
        }

        return $this->whereHas(
            'sources',
            static function (Builder $query) use ($sources): void {
                $query->where(
                    static function (Builder $query) use ($sources): void {
                        foreach ($sources as $source) {
                            $query->orWhere(
                                static fn (Builder $query) => $query
                                    ->where('name', $source->name)
                                    ->where('external_id', $source->external_id)
                            );
                        }
                    }
                );
            }
        );
    }
}
