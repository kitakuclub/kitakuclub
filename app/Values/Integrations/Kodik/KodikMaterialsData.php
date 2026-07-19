<?php

declare(strict_types=1);

namespace App\Values\Integrations\Kodik;

use Illuminate\Contracts\Support\Arrayable;
use Saloon\Http\Response;

final readonly class KodikMaterialsData implements Arrayable
{
    private function __construct(
        private string $time,
        private int $total,
        private ?string $prev_page,
        private ?string $next_page,
        private array $results,
    )
    {
    }

    public static function fromSaloonResponse(Response $response): self
    {
        $json = $response->json();

        return new self(
            time: $json['time'],
            total: $json['total'],
            prev_page: $json['prev_page'],
            next_page: $json['next_page'],
            results: $json['results'],
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [];
    }
}
