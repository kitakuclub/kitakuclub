<?php

declare(strict_types=1);

namespace App\Enums;

enum EntrySeason: string
{
    case WINTER = 'winter';
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case FALL = 'fall';

    public static function fromMonth(int $month): self
    {
        return match (true) {
            $month <= 3 => self::WINTER,
            $month <= 6 => self::SPRING,
            $month <= 9 => self::SUMMER,
            default     => self::FALL,
        };
    }
}
