<?php

declare(strict_types=1);

namespace App\Values;

use App\Http\Integrations\Kodik\DTOs\EpisodeDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\DTOs\ScreenshotDto;
use App\Http\Integrations\Kodik\DTOs\SeasonDto;
use App\Http\Integrations\Kodik\DTOs\TranslationDto;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

        /** @var array $item */
        foreach ($json['results'] as $item) {
            /** @var array $translation */
            $translation = $item['translation'];

            $translation = new TranslationDto(
                id: $translation['id'],
                title: Str::beforeLast($translation['title'], '.'),
                type: $translation['type'],
            );

            $seasons = new Collection(Arr::map(
                $item['seasons'] ?? [],
                static fn(array $season, int $number): SeasonDto => new SeasonDto(
                    number: $number,
                    episodes: new Collection(Arr::map(
                        $season['episodes'] ?? [],
                        static fn(string $link, int $number): EpisodeDto => new EpisodeDto(
                            number: $number,
                            link: $link,
                        ),
                    )),
                    link: $season['link'],
                ),
            ));

            $screenshots = new Collection(Arr::map(
                $item['screenshots'],
                static fn(string $link): ScreenshotDto => new ScreenshotDto(
                    link: $link,
                ),
            ));

            $results->add(new MaterialDto(
                id: $item['id'],
                title: $item['title'],
                translation: $translation,
                seasons: $seasons,
                screenshots: $screenshots,
            ));
        }

        return new self(
            time: $json['time'],
            total: $json['total'],
            prev_page: $json['prev_page'],
            next_page: $json['next_page'],
            results: $results,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [];
    }
}
