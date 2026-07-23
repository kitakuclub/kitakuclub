<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum EntryRating: string
{
    case UNKNOWN = 'unknown';
    case G = 'g';
    case PG = 'pg';
    case PG_13 = 'pg_13';
    case R = 'r';
    case R_PLUS = 'r_plus';
    case RX = 'rx';

    public static function fromKodik(string $rating): self
    {
        return match (Str::lower($rating)) {
            'g' => self::G,
            'pg' => self::PG,
            'pg13', 'pg-13' => self::PG_13,
            'r' => self::R,
            'r+' => self::R_PLUS,
            'rx' => self::RX,
            default => self::UNKNOWN,
        };
    }
}
