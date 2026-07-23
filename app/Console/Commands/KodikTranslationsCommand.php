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
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

        /** @var Collection<Funteam> $funteams */
        $funteams = Funteam::all(['id', 'name']);
        $funteams->each(fn (Funteam $funteam) => $funteam->name = Str::lower($funteam->name));

        do
        {
            $this->connector->query()->add('next', $next_page);

            $res = $this->connector->send(
                new GetMaterialsRequest
            );

            /** @var KodikMaterialsData $dto */
            $dto = $res->dto();

            $this->withProgressBar(
                $dto->results->toArray(),
                static function (MaterialDto $_material) use ($funteams) {

                    /** @var Funteam $funteam */
                    $funteam = $funteams->firstOrFail('name', Str::lower($_material->translation->title));

                    $translation = Translation::firstOrCreate(
                        [
                            'source' => TranslationSource::KODIK,
                            'external_id' => $_material->translation->id
                        ],
                        [
                            'funteam_id' => $funteam->id,
                            'source' => TranslationSource::KODIK,
                            'external_id' => $_material->translation->id,
                            'kind' => match ($_material->translation->type) {
                                'voice' => TranslationKind::DUB,
                                'subtitles' => TranslationKind::SUB,
                                default => throw new \InvalidArgumentException('Unknown translation type.'),
                            },
                            'locale' => EntryLocale::RU,
                        ]
                    );

                    if (is_null($_material->seasons))
                        return; // @todo strategy pattern or other *Command by type "movie-*"

                    $_material->seasons->each(static function (SeasonDto $_season) use ($translation) {

                        if (floatval($_season->number) < 0)
                            return; // @todo example kodik "serial-59967"

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

                        /** @var Collection<Episode> $episodes */
                        $episodes = new Collection;

                        $_season->episodes->each(static fn(EpisodeDto $_episode) => $episodes->add(
                            $season->episodes()->firstOrCreate(
                                [
                                    'season_id' => $season->id,
                                    'number' => $_episode->number,
                                ],
                                [
                                    'number' => $_episode->number,
                                    'link' => $_episode->link,
                                ],
                            )
                        ));

                        $translation->episodes()->syncWithoutDetaching(
                            $episodes->pluck('id')->all()
                        );
                    });
                }
            );

            $this->info(' success');

            $next_page = $dto->next_page;
        }
        while ($dto->next_page);
    }
}
