<?php

declare(strict_types=1);

namespace App\Enums;

enum EntryStatus: string
{
    case ANONS = 'anons';
    case ONGOING = 'ongoing';
    case RELEASED = 'released';
}
