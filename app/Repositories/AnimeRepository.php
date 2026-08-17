<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Anime;
use App\Repositories\Contracts\AnimeRepository as AnimeRepositoryContract;
use App\Values\AnimeFeedLatestUpdateData;
use App\Values\AnimeFeedLatestReleaseData;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @extends Repository<Anime>
 * @implements AnimeRepositoryContract
 */
class AnimeRepository extends Repository implements AnimeRepositoryContract
{
    /** @inheritDoc */
    public function latestUpdates(int $limit = 10, int $bufferDays = 7): Collection
    {
        $recentEpisodes = DB::table('episodes')
            ->select('id', 'season_id', 'number', 'created_at')
            ->where('created_at', '>=', now()->subDays($bufferDays))
            ->orderByDesc('created_at')
            ->orderByDesc('number');

        $latestPerAnimeTeam = DB::query()
            ->fromSub($recentEpisodes, 'ep')
            ->join('seasons as s', 's.id', '=', 'ep.season_id')
            ->join('releases as r', 'r.id', '=', 's.release_id')
            ->join('translations as t', 't.id', '=', 'r.translation_id')
            ->join('funteams as f', 'f.id', '=', 't.funteam_id')
            ->join('animes as a', static function (JoinClause $join): void {
                $join
                    ->on('a.id', '=', 'r.releasable_id')
                    ->where('r.releasable_type', '=', 'anime');
            })
            ->select([
                'a.id as anime_id',
                'a.name as anime_name',
                'a.slug as anime_slug',
                'f.id as funteam_id',
                'f.name as funteam_name',
                'r.code as release_code',
                'ep.number as episode_number',
                'ep.created_at as added_at',
                DB::raw('ROW_NUMBER() OVER (
                    PARTITION BY a.id, f.id
                    ORDER BY ep.created_at DESC, ep.number DESC
                ) as rn'),
            ]);

        $rows = DB::query()
            ->fromSub($latestPerAnimeTeam, 'x')
            ->where('rn', 1)
            ->orderByDesc('added_at')
            ->limit($limit)
            ->get();

        return $this->attachPosters(
            $rows->map(AnimeFeedLatestUpdateData::fromRow(...))
        );
    }

    /** @inheritDoc */
    public function latestReleases(int $limit = 10): Collection
    {
        $rows = DB::table('releases as r')
            ->join('translations as t', 't.id', '=', 'r.translation_id')
            ->join('funteams as f', 'f.id', '=', 't.funteam_id')
            ->join('animes as a', static function (JoinClause $join): void {
                $join
                    ->on('a.id', '=', 'r.releasable_id')
                    ->where('r.releasable_type', '=', 'anime');
            })
            ->select([
                'a.id as anime_id',
                'a.name as anime_name',
                'a.slug as anime_slug',
                'f.name as funteam_name',
                'r.code as release_code',
                'r.created_at as added_at',
            ])
            ->orderByDesc('r.created_at')
            ->limit($limit)
            ->get();

        return $this->attachPosters(
            $rows->map(AnimeFeedLatestReleaseData::fromRow(...))
        );
    }

    /**
     * @template T of AnimeFeedLatestUpdateData|AnimeFeedLatestReleaseData
     * @param Collection<int, T> $items
     * @return Collection<int, T>
     */
    private function attachPosters(Collection $items): Collection
    {
        $animeIds = $items->pluck('anime_id')->unique()->values();

        if ($animeIds->isEmpty()) {
            return $items;
        }

        $posters = Anime::query()
            ->whereIn('id', $animeIds)
            ->get()
            ->keyBy('id')
            ->map(static fn (Anime $anime): string|null => $anime->getFirstMediaUrl('poster'));

        return $items->map(
            static fn ($item) => $item->withPoster($posters->get($item->anime_id))
        );
    }
}
