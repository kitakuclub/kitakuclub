<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntryStatus;
use App\Enums\SourceName;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\DTOs\SourceDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Anime;
use App\Models\Source;
use App\Values\KodikMaterialsData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

#[Signature('kodik:animes')]
#[Description('Command description')]
class KodikAnimesCommand extends Command
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

        do
        {
            $res = $this->connector->send(
                new GetMaterialsRequest
            );

            /** @var KodikMaterialsData $dto */
            $dto = $res->dto();

            $this->withProgressBar(
                $dto->results->toArray(),
                static function (MaterialDto $_anime) {

                    if (is_null($_anime->sources))
                        return;

                    if (is_null($_anime->material_data))
                        return; // @todo example serial-77299, serial-65126

                    $name = $_anime->title;
                    $kind = $_anime->material_data->anime_kind ?? 'unknown';
                    $rating = $_anime->material_data->rating_mpaa ?? 'unknown';
                    $status = $_anime->material_data->anime_status ?? 'unknown';

                    /** @var Anime|null $anime */
                    $anime = Anime::query()
                        ->whereHasSources(
                            filter_sources_by_names($_anime->sources, [
                                SourceName::KODIK,
                                SourceName::SHIKIMORI,
                            ])
                        )
                        ->first();

                    if (is_null($anime)) {
                        /** @var Anime $anime */
                        $anime = Anime::create([
                            'name' => $name,
                            'kind' => AnimeKind::from($kind),
                            'rating' => EntryRating::fromKodik($rating),
                            'status' => EntryStatus::from($status),
                            'slug' => uniqid(),
                        ]);
                    }

                    /** @var Collection<SourceDto> $_sources */
                    $_sources = $_anime->sources->reject(
                        static fn(SourceDto $_source) => $anime->sources->contains(
                            static fn(Source $source) => $source->name === $_source->name
                                                      && $source->external_id === $_source->external_id
                        )
                    );

                    if ($_sources->isEmpty())
                        return;

                    $anime->sources()->saveMany(
                        $_sources->map(static fn (SourceDto $_source) => Source::make([
                            'name' => $_source->name,
                            'external_id' => $_source->external_id,
                        ]))
                    );
                }
            );

            $this->connector->query()->add('next', $dto->next_page);

            $this->info(' success');
        }
        while ($dto->next_page);
    }
}
