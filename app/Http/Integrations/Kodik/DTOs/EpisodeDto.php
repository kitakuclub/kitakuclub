<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class EpisodeDto
{
    /**
     * @param int $number
     * @param string $link
     */
    public function __construct(
        public int $number,
        public string $link,
    )
    {
    }
}
