<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class MaterialDataDto
{
    /**
     * @param string|null $anime_kind
     * @param string|null $anime_status
     */
    public function __construct(
        public string|null $anime_kind = null,
        public string|null $anime_status = null,
    )
    {
    }
}
