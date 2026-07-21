<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

use Illuminate\Support\Collection;

final readonly class MaterialDto
{
    /**
     * @param string $id
     * @param string $title
     * @param TranslationDto $translation
     * @param Collection<SeasonDto> $seasons
     * @param Collection<ScreenshotDto> $screenshots
     */
    public function __construct(
        public string $id,
        public string $title,
        public TranslationDto $translation,
        public Collection $seasons,
        public Collection $screenshots,
    )
    {
    }
}
