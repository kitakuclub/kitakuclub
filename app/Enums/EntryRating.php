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

    public function label(): string
    {
        return match ($this) {
            self::G => 'G',
            self::PG => 'PG',
            self::PG_13 => 'PG-13',
            self::R => 'R',
            self::R_PLUS => 'R+',
            self::RX => 'RX',
            default => 'Unknown',
        };
    }

    public function desc(): string
    {
        return match ($this) {
            self::G => 'для всех возрастов',
            self::PG => 'для детей',
            self::PG_13 => 'от 13 лет',
            self::R => 'насилие и/или нецензурная лексика',
            self::R_PLUS => 'есть сцены легкой эротики',
            self::RX => 'хентай',
            default => 'Unknown',
        };
    }
}
