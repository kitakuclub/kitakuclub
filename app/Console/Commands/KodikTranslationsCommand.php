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
                static function (MaterialDto $anime) {
                    /** @var Funteam $funteam */
                    $funteam = Funteam::query()->where('name', $anime->translation->title)->firstOrFail();

                    $translation = Translation::firstOrCreate(
                        [
                            'source' => TranslationSource::KODIK,
                            'external_id' => $anime->translation->id
                        ],
                        [
                            'funteam_id' => $funteam->id,
                            'source' => TranslationSource::KODIK,
                            'external_id' => $anime->translation->id,
                            'kind' => match ($anime->translation->type) {
                                'voice' => TranslationKind::DUB,
                                'subtitles' => TranslationKind::SUB,
                                default => throw new \InvalidArgumentException('unknown translation type.'),
                            },
                            'locale' => EntryLocale::RU,
                        ]
                    );

                    $anime->seasons->each(static function (SeasonDto $s) use ($translation) {
                        if (floatval($s->number) < 0)
                            return;

                        /** @var Season $season */
                        $season = $translation->seasons()->firstOrCreate(
                            [
                                'translation_id' => $translation->id,
                                'number' => $s->number,
                            ],
                            [
                                'number' => $s->number,
                                'link' => $s->link,
                            ],
                        );

                        $s->episodes->each(static function (EpisodeDto $e) use ($season, $translation) {
                            /** @var Episode $episode */
                            $episode = $season->episodes()->firstOrCreate(
                                [
                                    'season_id' => $season->id,
                                    'number' => $e->number,
                                ],
                                [
                                    'number' => $e->number,
                                    'link' => $e->link,
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
