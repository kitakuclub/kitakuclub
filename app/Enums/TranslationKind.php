<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum TranslationKind: string
{
    case DUB = 'dub';
    case SUB = 'sub';

    public static function fromKodik(string $translation_type): self
    {
        return match (Str::lower($translation_type)) {
            'voice' => self::DUB,
            'subtitles' => self::SUB,
            default => throw new \InvalidArgumentException('Unknown translation kind.'),
        };
    }
}
