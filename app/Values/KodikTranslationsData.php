<?php

declare(strict_types=1);

namespace App\Values;

use App\Http\Integrations\Kodik\DTOs\TranslationDto;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Saloon\Http\Response;

final readonly class KodikTranslationsData implements Arrayable
{
    private function __construct(
        public string $time,
        public int $total,
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
            $results->add(new TranslationDto(
                id: $item['id'],
                title: Str::before($item['title'], '.Subtitles'),
                type: Str::endsWith($item['title'], '.Subtitles') ? 'subtitles' : 'voice',
            ));
        }

        return new self(
            time: $json['time'],
            total: $json['total'],
            results: $results,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [];
    }
}
