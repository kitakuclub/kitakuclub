<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class TranslationDto
{
    /**
     * @param int $id
     * @param string $title
     * @param string $type
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $type,
    )
    {
    }
}
