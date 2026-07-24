<?php

declare(strict_types=1);

namespace App\Values;

use App\Enums\SourceName;
use App\Http\Integrations\Kodik\DTOs\EpisodeDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDataDto;
use App\Http\Integrations\Kodik\DTOs\ScreenshotDto;
use App\Http\Integrations\Kodik\DTOs\SeasonDto;
use App\Http\Integrations\Kodik\DTOs\SourceDto;
use App\Http\Integrations\Kodik\DTOs\TranslationDto;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class KodikMaterialsData implements Arrayable
{
    /**
     * @param string $time
     * @param int $total
     * @param string|null $prev_page
     * @param string|null $next_page
     * @param Collection<MaterialDto> $results
     */
    private function __construct(
        public string $time,
        public int $total,
        public ?string $prev_page,
        public ?string $next_page,
        public Collection $results,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        $results = new Collection;

        $url_query_param = static fn(?string $url, string $key) => $url ? (new \Uri($url))->query()->get($key) : null;

        /** @var array $item */
        foreach ($json['results'] as $item) {
            /** @var array $translation */
            $translation = $item['translation'];

            $translation = new TranslationDto(
                id: $translation['id'],
                title: kodik_title($translation['title']),
                type: $translation['type'],
            );

            $seasons = new Collection(Arr::map(
                $item['seasons'] ?? [],
                static function (array $season, int $number): SeasonDto {

                    $episodes = new Collection(Arr::map(
                        $season['episodes'] ?? [],
                        static fn(string $link, int $number): EpisodeDto => new EpisodeDto(
                            number: $number,
                            link: $link,
                        ),
                    ));

                    return new SeasonDto(
                        number: $number,
                        episodes: $episodes->isEmpty() ? null : $episodes,
                        link: $season['link'],
                    );
                },
            ));

            $screenshots = new Collection(Arr::map(
                $item['screenshots'],
                static fn(string $link): ScreenshotDto => new ScreenshotDto(
                    link: $link,
                ),
            ));

            /** @var Collection<SourceDto> $sources */
            $sources = new Collection(Arr::map(
                Arr::only($item, ['imdb_id', 'kinopoisk_id', 'myanimelist_id', 'shikimori_id']),
                static fn(string $external_id, string $name) => new SourceDto(
                    name: SourceName::fromKodik($name)->value,
                    external_id: $external_id,
                ),
            ));

            /** @var MaterialDataDto|null $material_data */
            $material_data = isset($item['material_data'])
                ? new MaterialDataDto(
                    ...Arr::only(
                        $item['material_data'],
                        [
                            'anime_kind',
                            'anime_status',
                            'rating_mpaa',
                        ]
                    )
                )
                : null; // @todo strategy pattern

            $results->add(new MaterialDto(
                id: $item['id'],
                link: $item['link'],
                name: $item['title_orig'],
                translation: $translation,
                seasons: $seasons->isEmpty() ? null : $seasons,
                screenshots: $screenshots,
                sources: $sources->isEmpty() ? null : $sources->values(),
                material_data: $material_data,
            ));
        }

        return new self(
            time: $json['time'],
            total: $json['total'],
            prev_page: $url_query_param($json['prev_page'], 'prev'),
            next_page: $url_query_param($json['next_page'], 'next'),
            results: $results,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [];
    }
}
