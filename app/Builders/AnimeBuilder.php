<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\SourceName;
use App\Http\Integrations\Kodik\DTOs\SourceDto;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnimeBuilder extends Builder
{
    public function latestReleases(int $limit = 10): Collection
    {
        return $this->buildLatestReleases($limit);
    }

    public function latestUpdates(int $limit = 10, int $bufferDays = 7): Collection
    {
        return $this->buildLatestUpdates($limit, $bufferDays);
    }

    public function whereHasSources(iterable $sources): self
    {
        $sources = $sources instanceof Collection ? $sources : collect($sources);

        if ($sources->isEmpty()) {
            return $this->whereRaw('1 = 0');
        }

        return $this->whereHas(
            'sources',
            static function (Builder $query) use ($sources): void {
                $query->where(
                    static function (Builder $query) use ($sources): void {
                        foreach ($sources as $source) {
                            $query->orWhere(
                                static fn (Builder $query) => $query
                                    ->where('name', $source->name)
                                    ->where('external_id', $source->external_id)
                            );
                        }
                    }
                );
            }
        );
    }

    public function whereHasSourcesByNames(iterable $sources, array $allowedNames): self
    {
        return $this->whereHasSources(
            $sources->filter(
                static fn(SourceDto $source) => in_array(
                    SourceName::tryFrom($source->name),
                    $allowedNames,
                    true
                )
            )
        );
    }

    private function buildLatestUpdates(int $limit, int $bufferDays = 7): Collection
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
                'f.name as funteam_name',
                'r.code as release_code',
                'ep.number as episode_number',
                'ep.created_at as added_at',
                DB::raw('ROW_NUMBER() OVER (
                    PARTITION BY a.id, f.id
                    ORDER BY ep.created_at DESC, ep.number DESC
                ) as rn'),
            ]);

        $updates = DB::query()
            ->fromSub($latestPerAnimeTeam, 'x')
            ->where('rn', 1)
            ->orderByDesc('added_at')
            ->limit($limit)
            ->get();

        return $this->attachPosters($updates);
    }

    private function buildLatestReleases(int $limit): Collection
    {
        $releases = DB::table('releases as r')
            ->join('translations as t', 't.id', '=', 'r.translation_id')
            ->join('funteams as f', 'f.id', '=', 't.funteam_id')
            ->join('animes as a', static function ($join): void {
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

        return $this->attachPosters($releases);
    }

    private function attachPosters(Collection $items): Collection
    {
        $animeIds = $items->pluck('anime_id')->unique();

        $posters = Anime::query()
            ->whereIn('id', $animeIds)
            ->get()
            ->keyBy('id')
            ->map(static fn (Anime $anime): ?string => $anime->getFirstMediaUrl('poster'));

        return $items->map(static function (object $item) use ($posters): object {
            $item->poster_url = $posters->get($item->anime_id);
            return $item;
        });
    }
}
