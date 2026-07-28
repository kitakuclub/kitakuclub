<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\DTOs;

use Illuminate\Support\Collection;

final readonly class MaterialDto
{
    /**
     * @param string $id
     * @param string $link
     * @param string $title
     * @param string $title_orig
     * @param TranslationDto $translation
     * @param Collection<SeasonDto>|null $seasons
     * @param Collection<ScreenshotDto> $screenshots
     * @param Collection<SourceDto>|null $sources
     * @param MaterialDataDto|null $material_data
     */
    public function __construct(
        public string $id,
        public string $link,
        public string $title,
        public string $title_orig,
        public TranslationDto $translation,
        public Collection|null $seasons,
        public Collection $screenshots,
        public Collection|null $sources,
        public MaterialDataDto|null $material_data,
    )
    {
    }
}
