<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SourceName;
use App\Enums\TranslationSource;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\DTOs\SeasonDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Anime;
use App\Models\Episode;
use App\Models\Release;
use App\Models\Translation;
use App\Values\KodikMaterialsData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

#[Signature('kodik:releases')]
#[Description('Command description')]
class KodikReleasesCommand extends Command
{
    public function __construct(
        private readonly KodikConnector $connector,
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->connector->query()->add('limit', 100);
        $this->connector->query()->add('types', 'anime,anime-serial');
        $this->connector->query()->add('has_field', 'shikimori_id');
        $this->connector->query()->add('sort', 'year');
        $this->connector->query()->add('order', 'asc');
        $this->connector->query()->add('with_material_data', true);
        $this->connector->query()->add('with_seasons', true);
        $this->connector->query()->add('with_episodes', true);

        /** @var Collection<Translation> $translations */
        $translations = Translation::all(['id', 'source', 'external_id']);

        do
        {
            $res = $this->connector->send(
                new GetMaterialsRequest
            );

            /** @var KodikMaterialsData $dto */
            $dto = $res->dto();

            $this->withProgressBar(
                $dto->results->toArray(),
                static function (MaterialDto $_anime) use ($translations) {

                    if (is_null($_anime->sources))
                        return;

                    /** @var Anime|null $anime */
                    $anime = Anime::query()
                        ->whereHasSourcesByNames(
                            $_anime->sources,
                            [
                                SourceName::KODIK,
                                SourceName::SHIKIMORI,
                            ]
                        )
                        ->first();

                    if (is_null($anime))
                        return;

                    /** @var Translation|null $translation */
                    $translation = $translations
                        ->where('source', TranslationSource::KODIK)
                        ->where('external_id', $_anime->translation->id)
                        ->first();

                    if (is_null($translation))
                        return;

                    /** @var Release|null $release */
                    $release = $anime
                        ->releases
                        ->where('translation_id', $translation->id)
                        ->where('external_id', $_anime->id)
                        ->first();

                    if (is_null($release)) {
                        /** @var Release $release */
                        $release = $anime
                            ->releases()
                            ->save(
                                Release::make([
                                    'translation_id' => $translation->id,
                                    'code' => Str::random(16),
                                    'external_id' => $_anime->id,
                                    'link' => $_anime->link,
                                ])
                            );
                    }

                    /** @var Collection<SeasonDto>|null $_seasons */
                    $_seasons = $_anime->seasons;

                    if (is_null($_seasons) || $_seasons->isEmpty())
                        return;

                    // 1. Upsert сезонов одним запросом
                    $release->seasons()->upsert(
                        $_seasons->map(fn (SeasonDto $_season) => [
                            'release_id' => $release->id,
                            'number' => $_season->number,
                            'link' => $_season->link,
                        ])->toArray(),
                        uniqueBy: ['release_id', 'number'],
                        update: [],
                    );

                    // 2. Подтягиваем id сезонов, чтобы связать с эпизодами
                    $seasonsByNumber = $release
                        ->seasons()
                        ->get(['id', 'number'])
                        ->keyBy('number');

                    // 3. Собираем эпизоды всех сезонов в один плоский массив
                    $episodesRows = $_seasons->flatMap(
                        function (SeasonDto $_season) use ($seasonsByNumber) {
                            $season = $seasonsByNumber->get($_season->number);

                            if (is_null($season) || is_null($_season->episodes))
                                return [];

                            return $_season->episodes->map(fn ($_episode) => [
                                'season_id' => $season->id,
                                'number' => $_episode->number,
                                'link' => $_episode->link,
                            ]);
                        }
                    );

                    if ($episodesRows->isEmpty())
                        return;

                    // 4. Upsert эпизодов одним запросом
                    Episode::query()->upsert(
                        $episodesRows->toArray(),
                        uniqueBy: ['season_id', 'number'],
                        update: [],
                    );

                    // 5. Подтягиваем id эпизодов по season_id, чтобы связать с релизом
                    $episodeIds = Episode::query()
                        ->whereIn('season_id', $seasonsByNumber->pluck('id'))
                        ->pluck('id');

                    // 6. Upsert связей release <-> episode в pivot-таблицу
                    if ($episodeIds->isNotEmpty())
                        $release->episodes()->syncWithoutDetaching($episodeIds);
                }
            );

            $this->connector->query()->add('next', $dto->next_page);

            $this->info(' success');
        }
        while ($dto->next_page);
    }
}
