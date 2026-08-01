<?php

declare(strict_types=1);

namespace App\Enums;

enum EntryStatus: string
{
    case UNKNOWN = 'unknown';
    case ANONS = 'anons';
    case ONGOING = 'ongoing';
    case RELEASED = 'released';

    public function label(): string
    {
        return match ($this) {
            self::UNKNOWN => 'Unknown',
            self::ANONS => 'Анонс',
            self::ONGOING => 'Онгоинг',
            self::RELEASED => 'Вышло',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::UNKNOWN => '#000',
            self::ANONS => '#d63939',
            self::ONGOING => '#ae3ec9',
            self::RELEASED => '#2fb344',
        };
    }
}
