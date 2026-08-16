<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class UpdatesAnime extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $recentEpisodes = DB::table('episodes')
            ->select('id', 'season_id', 'number', 'created_at')
            ->where('created_at', '>=', now()->subDays(7))
            ->orderByDesc('created_at')
            ->orderByDesc('number');

        $latestPerAnimeTeamSub = DB::query()
            ->fromSub($recentEpisodes, 'ep')
            ->join('seasons as s', 's.id', '=', 'ep.season_id')
            ->join('releases as r', 'r.id', '=', 's.release_id')
            ->join('translations as t', 't.id', '=', 'r.translation_id')
            ->join('funteams as f', 'f.id', '=', 't.funteam_id')
            ->join('animes as a', static function (JoinClause $join) {
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
                'ep.id as episode_id',
                'ep.number as episode_number',
                'ep.created_at as added_at',
                DB::raw('ROW_NUMBER() OVER (
                    PARTITION BY a.id, f.id
                    ORDER BY ep.created_at DESC, ep.number DESC
                ) as rn'),
            ]);

        $updates = DB::query()
            ->fromSub($latestPerAnimeTeamSub, 'x')
            ->where('rn', 1)
            ->orderByDesc('added_at')
            ->limit(5)
            ->get();

        $animeIds = $updates->pluck('anime_id')->unique();

        $posters = Anime::query()
            ->whereIn('id', $animeIds)
            ->get()
            ->keyBy('id')
            ->map(static fn (Anime $anime) => $anime->getFirstMediaUrl('poster'));

        $updates = $updates->map(static function ($update) use ($posters) {
            $update->poster_url = $posters->get($update->anime_id);
            return $update;
        });

        return view('components.updates-anime', compact('updates'));
    }
}
