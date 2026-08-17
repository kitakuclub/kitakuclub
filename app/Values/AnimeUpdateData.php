<?php

declare(strict_types=1);

namespace App\Values;

use App\Enums\EntryStatus;
use App\Http\Integrations\Kodik\DTOs\MaterialDataDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

final readonly class AnimeUpdateData implements Arrayable
{
    public function __construct(
        public EntryStatus $status,
        public string|null $released_at,
        public int $episodes_total,
        public int $episodes_aired,
        public int $duration,
        public CarbonInterface|null $next_episode_at,
    )
    {
    }

    public static function fromKodik(MaterialDto $dto): self
    {
        /** @var MaterialDataDto $info */
        $info = $dto->material_data;

        return self::make(
            status: EntryStatus::from($info->anime_status),
            released_at: $info->released_at,
            episodes_total: $info->episodes_total,
            episodes_aired: $info->episodes_aired,
            duration: $info->duration,
            next_episode_at: Carbon::make($info->next_episode_at),
        );
    }

    public static function make(
        EntryStatus $status,
        string|null $released_at,
        int $episodes_total,
        int $episodes_aired,
        int $duration,
        CarbonInterface|null $next_episode_at,
    ) : self
    {
        return new self(
            status: $status,
            released_at: $released_at,
            episodes_total: $episodes_total,
            episodes_aired: $episodes_aired,
            duration: $duration,
            next_episode_at: $next_episode_at,
        );
    }

    /** @inheritdoc */
    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'released_at' => $this->released_at,
            'episodes_total' => $this->episodes_total,
            'episodes_aired' => $this->episodes_aired,
            'duration' => $this->duration,
            'next_episode_at' => $this->next_episode_at,
        ];
    }
}
