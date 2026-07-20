<?php

declare(strict_types=1);

namespace App\Values\Integrations\Kodik;

final readonly class KodikMaterialsDataItem
{
    public function __construct(
        public string $id,
        public string $type,
        public string $link,
        public string $title,
        public string $title_orig,
        public string $other_title,
        public KodikMaterialsDataTranslation $translation,
        public int $year,
        public int $last_season,
        public int $last_episode,
        public int $episodes_count,
        public ?string $kinopoisk_id,
        public string $imdb_id,
        public string $worldart_link,
        public string $shikimori_id,
        public string $quality,
        public bool $camrip,
        public bool $lgbt,
        public array $blocked_countries,
        public array $blocked_seasons,
        public string $created_at,
        public string $updated_at,
        public array $screenshots,
    )
    {
    }

    public static function make(array $item): self
    {
        $translation = KodikMaterialsDataTranslation::make($item['translation']);
        $kinopoisk_id = $item['kinopoisk_id'] ?? null;

        return new self(
            $item['id'],
            $item['type'],
            $item['link'],
            $item['title'],
            $item['title_orig'],
            $item['other_title'],
            $translation,
            $item['year'],
            $item['last_season'],
            $item['last_episode'],
            $item['episodes_count'],
            $kinopoisk_id,
            $item['imdb_id'],
            $item['worldart_link'],
            $item['shikimori_id'],
            $item['quality'],
            $item['camrip'],
            $item['lgbt'],
            $item['blocked_countries'],
            $item['blocked_seasons'],
            $item['created_at'],
            $item['updated_at'],
            $item['screenshots'],
        );
    }
}
