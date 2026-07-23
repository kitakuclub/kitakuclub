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
     * @param Collection<SeasonDto>|null $seasons
     * @param Collection<ScreenshotDto> $screenshots
     * @param string $link
     * @param array|null $material_data
     */
    public function __construct(
        public string $id,
        public string $title,
        public TranslationDto $translation,
        public Collection|null $seasons,
        public Collection $screenshots,
        public string $link,
        public array|null $material_data
    )
    {
    }
}
