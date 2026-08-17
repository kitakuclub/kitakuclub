<?php

declare(strict_types=1);

namespace App\Values;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

final readonly class AnimeFeedLatestReleaseData implements Arrayable
{
    public function __construct(
        public int $anime_id,
        public string $anime_name,
        public string $anime_slug,
        public string $funteam_name,
        public string $release_code,
        public CarbonInterface $added_at,
        public string|null $poster_url = null,
    )
    {
    }

    public static function fromRow(object $row): self
    {
        return self::make(
            anime_id: (int) $row->anime_id,
            anime_name: $row->anime_name,
            anime_slug: $row->anime_slug,
            funteam_name: $row->funteam_name,
            release_code: $row->release_code,
            added_at: Carbon::parse($row->added_at),
        );
    }

    public static function make(
        int $anime_id,
        string $anime_name,
        string $anime_slug,
        string $funteam_name,
        string $release_code,
        CarbonInterface $added_at,
        string|null $poster_url = null,
    ): self
    {
        return new self(
            $anime_id,
            $anime_name,
            $anime_slug,
            $funteam_name,
            $release_code,
            $added_at,
            $poster_url,
        );
    }

    public function withPoster(string|null $poster_url): self
    {
        return self::make(
            $this->anime_id,
            $this->anime_name,
            $this->anime_slug,
            $this->funteam_name,
            $this->release_code,
            $this->added_at,
            $poster_url,
        );
    }

    public static function fromArray(array $data): self
    {
        return self::make(
            anime_id: $data['anime_id'],
            anime_name: $data['anime_name'],
            anime_slug: $data['anime_slug'],
            funteam_name: $data['funteam_name'],
            release_code: $data['release_code'],
            added_at: Carbon::parse($data['added_at']),
            poster_url: $data['poster_url'] ?? null,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'anime_id' => $this->anime_id,
            'anime_name' => $this->anime_name,
            'anime_slug' => $this->anime_slug,
            'funteam_name' => $this->funteam_name,
            'release_code' => $this->release_code,
            'added_at' => $this->added_at->toIso8601String(),
            'poster_url' => $this->poster_url,
        ];
    }
}
