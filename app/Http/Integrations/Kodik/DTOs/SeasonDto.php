<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

use Illuminate\Support\Collection;

final readonly class SeasonDto
{
    /**
     * @param int $number
     * @param Collection<EpisodeDto>|null $episodes
     * @param string $link
     */
    public function __construct(
        public int $number,
        public Collection|null $episodes,
        public string $link,
    )
    {
    }
}
