<?php

declare(strict_types=1);

use App\Enums\SourceName;
use App\Http\Integrations\Kodik\DTOs\SourceDto;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

function kodik_title(string $value): string
{
    return Str::before($value, '.Subtitles');
}

function kodik_type(string $value): string
{
    return Str::endsWith($value, '.Subtitles') ? 'subtitles' : 'voice';
}

/**
 * @param Collection<SourceDto> $sources
 * @param array<SourceName> $allowedNames
 * @return Collection<SourceDto>
 */
function filter_sources_by_names(Collection $sources, array $allowedNames): Collection
{
    return $sources->filter(
        static fn(SourceDto $source) => in_array(
            SourceName::tryFrom($source->name),
            $allowedNames,
            true
        )
    );
}
