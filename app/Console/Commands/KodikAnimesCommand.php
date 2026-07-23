<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\AnimeKind;
use App\Enums\EntryRating;
use App\Enums\EntryStatus;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Anime;
use App\Values\KodikMaterialsData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

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
                static function (MaterialDto $_material) {

                    $kind = $_material->material_data->anime_kind ?? 'unknown';
                    $status = $_material->material_data->anime_status ?? 'unknown';

                    $anime = Anime::firstOrCreate(
                        [
                            'name' => $_material->name,
                        ],
                        [
                            'kind' => AnimeKind::from($kind),
                            'rating' => EntryRating::G, // @todo
                            'status' => EntryStatus::from($status),
                            'name' => $_material->name,
                            'slug' => uniqid(),
                        ]
                    );
                }
            );

            $this->newLine();

            $this->info('success');

            $next_page = $dto->next_page;
        }
        while ($dto->next_page);
    }
}
