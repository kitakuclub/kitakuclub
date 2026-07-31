<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class ScreenshotDto
{
    /**
     * @param string $link
     */
    public function __construct(
        public string $link,
    )
    {
    }
}
