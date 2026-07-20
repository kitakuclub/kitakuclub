<?php

declare(strict_types=1);

namespace App\Values\Integrations\Kodik;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Saloon\Http\Response;

final readonly class KodikTranslationsData implements Arrayable
{
    private function __construct(
        private string $time,
        private int $total,
        private array $results,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        $results = new Collection;

        /** @var array $item */
        foreach ($json['results'] as $item) {
            /** @var string $title */
            $title = $item['title'];

            $results->push([
                'id' => $item['id'],
                'title' => Str::beforeLast($title, '.'),
                'type' => Str::endsWith($title, '.Subtitles') ? 'subtitles' : 'voice',
                'count' => $item['count'],
            ]);
        }

        return new self(
            time: $json['time'],
            total: $json['total'],
            results: $results->toArray(),
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [];
    }
}
