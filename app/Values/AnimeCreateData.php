<?php

declare(strict_types=1);

namespace App\Values;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntrySeason;
use App\Enums\EntryStatus;
use App\Http\Integrations\Kodik\DTOs\MaterialDataDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use Carbon\CarbonInterface;
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
        public CarbonInterface|null $aired_at,
        public int|null $aired_year,
        public EntrySeason $aired_season,
        public CarbonInterface|null $released_at,
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

        /** @var CarbonInterface|null $aired_at */
        $aired_at = Carbon::make($info->aired_at);

        $aired_year = is_null($aired_at) ? null : (int)$aired_at->format('Y');
        $aired_season = is_null($aired_at) ? EntrySeason::UNKNOWN : EntrySeason::fromDate($aired_at);

        return self::make(
            name: $dto->title,
            kind: AnimeKind::from($info->anime_kind),
            rating: EntryRating::fromKodik($info->rating_mpaa),
            status: EntryStatus::from($info->anime_status),
            slug: uniqid(),
            aired_at: $aired_at,
            aired_year: $aired_year,
            aired_season: $aired_season,
            released_at: Carbon::make($info->released_at),
            episodes_total: $info->episodes_total,
            episodes_aired: $info->episodes_aired,
            duration: $info->duration,
            next_episode_at: Carbon::make($info->next_episode_at),
        );
    }

    public static function make(
        string $name,
        AnimeKind $kind,
        EntryRating $rating,
        EntryStatus $status,
        string $slug,
        CarbonInterface|null $aired_at,
        int|null $aired_year,
        EntrySeason $aired_season,
        CarbonInterface|null $released_at,
        int $episodes_total,
        int $episodes_aired,
        int $duration,
        CarbonInterface|null $next_episode_at,
    ) : self
    {
        return new self(
            $name,
            $kind,
            $rating,
            $status,
            $slug,
            $aired_at,
            $aired_year,
            $aired_season,
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
            'aired_year' => $this->aired_year,
            'aired_season' => $this->aired_season->value,
            'released_at' => $this->released_at,
            'episodes_total' => $this->episodes_total,
            'episodes_aired' => $this->episodes_aired,
            'duration' => $this->duration,
            'next_episode_at' => $this->next_episode_at,
        ];
    }
}
