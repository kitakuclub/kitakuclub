<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeKind: string
{
    case UNKNOWN = 'unknown';
    case TV = 'tv';
    case MOVIE = 'movie';
    case OVA = 'ova';
    case ONA = 'ona';
    case SPECIAL = 'special';
    case TV_SPECIAL = 'tv_special';
    case MUSIC = 'music';
    case PV = 'pv';
    case CM = 'cm';

    public function label(): string
    {
        return match ($this) {
            self::UNKNOWN => 'Unknown',
            self::TV => 'TV Сериал',
            self::MOVIE => 'Фильм',
            self::OVA => 'OVA',
            self::ONA => 'ONA',
            self::SPECIAL => 'Спецвыпуск',
            self::TV_SPECIAL => 'TV Спецвыпуск',
            self::MUSIC => 'Клип',
            self::PV => 'Проморолик',
            self::CM => 'Реклама',
        };
    }
}
