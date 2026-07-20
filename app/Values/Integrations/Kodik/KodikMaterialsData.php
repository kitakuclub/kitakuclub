<?php

declare(strict_types=1);

namespace App\Values\Integrations\Kodik;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Saloon\Http\Response;

final readonly class KodikMaterialsData implements Arrayable
{
    public function __construct(
        public string $time,
        public int $total,
        public ?string $prev_page,
        public ?string $next_page,
        public Collection $items,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        $items = new Collection;

        /** @var array $item */
        foreach ($json['results'] as $item) $items->add(
            KodikMaterialsDataItem::make($item)
        );

        return new self(
            time: $json['time'],
            total: $json['total'],
            prev_page: $json['prev_page'],
            next_page: $json['next_page'],
            items: $items,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [];
    }
}
