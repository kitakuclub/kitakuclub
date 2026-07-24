<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum SourceName: string
{
    case IMDB = 'imdb';
    case KINOPOISK = 'kinopoisk';
    case MYANIMELIST = 'myanimelist';
    case SHIKIMORI = 'shikimori';

    public static function fromKodik(string $external_id): self
    {
        return match (Str::lower($external_id)) {
            'imdb_id' => self::IMDB,
            'kinopoisk_id' => self::KINOPOISK,
            'myanimelist_id' => self::MYANIMELIST,
            'shikimori_id' => self::SHIKIMORI,
            default => throw new \InvalidArgumentException('Unknown source name.'),
        };
    }
}
