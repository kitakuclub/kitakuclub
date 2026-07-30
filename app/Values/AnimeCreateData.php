<?php

declare(strict_types=1);

namespace App\Values;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntryStatus;
use App\Http\Integrations\Kodik\DTOs\MaterialDataDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

final readonly class AnimeCreateData implements Arrayable
{
    public function __construct(
        public string $name,
        public AnimeKind $kind,
        public EntryRating $rating,
        public EntryStatus $status,
        public string $slug,
        public string|null $aired_at,
        public string|null $released_at,
        public int $episodes_total,
        public int $episodes_aired,
        public int $duration,
        public DateTimeInterface|null $next_episode_at,
    )
    {
    }

    public static function fromKodik(MaterialDto $dto): self
    {
        /** @var MaterialDataDto $info */
        $info = $dto->material_data;

        return self::make(
            name: $dto->title,
            kind: AnimeKind::from($info->anime_kind ?? 'unknown'),
            rating: EntryRating::fromKodik($info->rating_mpaa ?? 'unknown'),
            status: EntryStatus::from($info->anime_status ?? 'unknown'),
            slug: uniqid(),
            aired_at: $info->aired_at,
            released_at: $info->released_at,
            episodes_total: $info->episodes_total ?? 0,
            episodes_aired: $info->episodes_aired ?? 0,
            duration: $info->duration ?? 0,
            next_episode_at: Carbon::make($info->next_episode_at),
        );
    }

    public static function make(
        string $name,
        AnimeKind $kind,
        EntryRating $rating,
        EntryStatus $status,
        string $slug,
        string|null $aired_at,
        string|null $released_at,
        int $episodes_total,
        int $episodes_aired,
        int $duration,
        DateTimeInterface|null $next_episode_at,
    ) : self
    {
        return new self(
            $name,
            $kind,
            $rating,
            $status,
            $slug,
            $aired_at,
            $released_at,
            $episodes_total,
            $episodes_aired,
            $duration,
            $next_episode_at,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'kind' => $this->kind->value,
            'rating' => $this->rating->value,
            'status' => $this->status->value,
            'slug' => $this->slug,
            'aired_at' => $this->aired_at,
            'released_at' => $this->released_at,
            'episodes_total' => $this->episodes_total,
            'episodes_aired' => $this->episodes_aired,
            'duration' => $this->duration,
            'next_episode_at' => $this->next_episode_at,
        ];
    }
}
