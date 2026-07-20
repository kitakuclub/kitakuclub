<?php

declare(strict_types=1);

namespace App\Values\Integrations\Kodik;

use Illuminate\Support\Str;

final readonly class KodikMaterialsDataTranslation
{
    public function __construct(
        public int $id,
        public string $title,
        public string $type,
    )
    {
    }

    public static function make(array $translation): self
    {
        return new self(
            $translation['id'],
            Str::beforeLast($translation['title'], '.'),
            $translation['type'],
        );
    }
}
