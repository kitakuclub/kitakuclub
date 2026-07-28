<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class MaterialDataDto
{
    /**
     * @param string|null $title_en
     * @param string|null $anime_kind
     * @param string|null $anime_status
     * @param string|null $rating_mpaa
     */
    public function __construct(
        public string|null $title_en = null,
        public string|null $anime_kind = null,
        public string|null $anime_status = null,
        public string|null $rating_mpaa = null,
    )
    {
    }
}
