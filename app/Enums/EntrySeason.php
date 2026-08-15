<?php

declare(strict_types=1);

namespace App\Enums;

use DateTimeInterface;

enum EntrySeason: string
{
    case UNKNOWN = 'unknown';
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

    public static function fromKodik(DateTimeInterface $aired_at): self
    {
        return self::fromMonth((int)$aired_at->format('m'));
    }
}
