<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntryStatus;
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

            $this->withProgressBar(
                $dto->results->toArray(),
                function (MaterialDto $_anime) {

                    $kind = $_anime->material_data->anime_kind ?? 'unknown';
                    $rating = $_anime->material_data->rating_mpaa ?? 'unknown';
                    $status = $_anime->material_data->anime_status ?? 'unknown';

                    $anime = Anime::firstOrCreate(
                        [
                            'name' => $_anime->name,
                        ],
                        [
                            'kind' => AnimeKind::from($kind),
                            'rating' => EntryRating::fromKodik($rating),
                            'status' => EntryStatus::from($status),
                            'name' => $_anime->name,
                            'slug' => uniqid(),
                        ]
                    );

                    /** @var Collection<Source> $sources */
                    $sources = new Collection;

                    if (is_null($_anime->sources))
                        return;

                    $_anime->sources->each(fn(SourceDto $_source) => $sources->add(Source::make([
                        'name' => $_source->name,
                        'external_id' => $_source->external_id,
                    ])));

                    $sources->each(fn(Source $source) => $anime->sources()->updateOrCreate($source->toArray()));
                }
            );

            $this->newLine();

            $this->info('success');

            $next_page = $dto->next_page;
        }
        while ($dto->next_page);
    }
}
