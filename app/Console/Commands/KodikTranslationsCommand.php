<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EntryLocale;
use App\Enums\TranslationKind;
use App\Enums\TranslationSource;
use App\Http\Integrations\Kodik\DTOs\EpisodeDto;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\DTOs\SeasonDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Episode;
use App\Models\Funteam;
use App\Models\Season;
use App\Models\Translation;
use App\Values\KodikMaterialsData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('kodik:translations')]
#[Description('Command description')]
class KodikTranslationsCommand extends Command
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
        $this->connector->query()->add('with_seasons', true);
        $this->connector->query()->add('with_episodes', true);
        $this->connector->query()->add('with_material_data', true);

        /** @var string|null $next_page */
        $next_page = null;

        do
        {
            $this->connector->query()->add('next', $next_page);

            $res = $this->connector->send(
                new GetMaterialsRequest
            );

            /** @var KodikMaterialsData $dto */
            $dto = $res->dto();

            $this->comment($dto->next_page);

            $this->withProgressBar(
                $dto->results->toArray(),
                static function (MaterialDto $_anime) {
                    dd($_anime);
                    /** @var Funteam $funteam */
                    $funteam = Funteam::query()->where('name', $_anime->translation->title)->firstOrFail();

                    $translation = Translation::firstOrCreate(
                        [
                            'source' => TranslationSource::KODIK,
                            'external_id' => $_anime->translation->id
                        ],
                        [
                            'funteam_id' => $funteam->id,
                            'source' => TranslationSource::KODIK,
                            'external_id' => $_anime->translation->id,
                            'kind' => match ($_anime->translation->type) {
                                'voice' => TranslationKind::DUB,
                                'subtitles' => TranslationKind::SUB,
                                default => throw new \InvalidArgumentException('unknown translation type.'),
                            },
                            'locale' => EntryLocale::RU,
                        ]
                    );

                    $_anime->seasons->each(static function (SeasonDto $_season) use ($translation) {
                        if (floatval($_season->number) < 0)
                            return;

                        /** @var Season $season */
                        $season = $translation->seasons()->firstOrCreate(
                            [
                                'translation_id' => $translation->id,
                                'number' => $_season->number,
                            ],
                            [
                                'number' => $_season->number,
                                'link' => $_season->link,
                            ],
                        );

                        $_season->episodes->each(static function (EpisodeDto $_episode) use ($season, $translation) {
                            /** @var Episode $episode */
                            $episode = $season->episodes()->firstOrCreate(
                                [
                                    'season_id' => $season->id,
                                    'number' => $_episode->number,
                                ],
                                [
                                    'number' => $_episode->number,
                                    'link' => $_episode->link,
                                ],
                            );

                            $translation->episodes()->syncWithoutDetaching($episode);
                        });
                    });
                }
            );

            $this->info(' success');

            $next_page = $dto->next_page;
        }
        while ($dto->next_page);
    }
}
