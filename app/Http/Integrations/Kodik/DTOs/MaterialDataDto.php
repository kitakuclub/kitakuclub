<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class MaterialDataDto
{
    /**
     * @param string|null $poster_url
     * @param string $anime_kind
     * @param string $anime_status
     * @param string $rating_mpaa
     * @param string|null $aired_at
     * @param string|null $released_at
     * @param int $episodes_total
     * @param int $episodes_aired
     * @param int $duration
     * @param string|null $next_episode_at
     */
    public function __construct(
        public string|null $poster_url = null,
        public string $anime_kind = 'unknown',
        public string $anime_status = 'unknown',
        public string $rating_mpaa = 'unknown',
        public string|null $aired_at = null,
        public string|null $released_at = null,
        public int $episodes_total = 0,
        public int $episodes_aired = 0,
        public int $duration = 0,
        public string|null $next_episode_at = null,
    )
    {
    }
}
