<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class MaterialDataDto
{
    /**
     * @param string|null $anime_kind
     * @param string|null $anime_status
     * @param string|null $rating_mpaa
     * @param string|null $aired_at
     * @param string|null $released_at
     * @param int|null $episodes_total
     * @param int|null $episodes_aired
     * @param int|null $duration
     * @param string|null $next_episode_at
     */
    public function __construct(
        public string|null $anime_kind = null,
        public string|null $anime_status = null,
        public string|null $rating_mpaa = null,
        public string|null $aired_at = null,
        public string|null $released_at = null,
        public int|null $episodes_total = null,
        public int|null $episodes_aired = null,
        public int|null $duration = null,
        public string|null $next_episode_at = null,
    )
    {
    }
}
