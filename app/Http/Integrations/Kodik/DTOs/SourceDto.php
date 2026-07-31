<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

final readonly class SourceDto
{
    /**
     * @param string $name
     * @param string $external_id
     */
    public function __construct(
        public string $name,
        public string $external_id,
    )
    {
    }
}
