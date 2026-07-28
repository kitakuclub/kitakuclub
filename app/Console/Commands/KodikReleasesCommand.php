<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SourceName;
use App\Enums\TranslationSource;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Anime;
use App\Models\Release;
use App\Models\Translation;
use App\Values\KodikMaterialsData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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
                function (MaterialDto $_anime) use ($translations) {

                    if (is_null($_anime->sources))
                        return;

                    $query = Anime::query()->whereHasSources(
                        filter_sources_by_names($_anime->sources, [
                            SourceName::KODIK,
                            SourceName::SHIKIMORI,
                        ])
                    );

                    try {
                        /** @var Anime $anime */
                        $anime = $query->firstOrFail();
                    } catch (\Exception $e) {
                        $this->warn("Anime ($_anime->id) not found.");
                        return;
                    }

                    try {
                        /** @var Translation $translation */
                        $translation = $translations
                            ->where('source', TranslationSource::KODIK)
                            ->where('external_id', $_anime->translation->id)
                            ->firstOrFail();
                    } catch (\Exception $e) {
                        $this->warn('Translation (' . json_encode($_anime->translation) . ') not found.');
                        return;
                    }

                    $anime->releases()->save(Release::make([
                        'translation_id' => $translation->id,
                        'external_id' => $_anime->id,
                        'link' => $_anime->link
                    ]));
                }
            );

            $this->connector->query()->add('next', $dto->next_page);

            $this->info(' success');
        }
        while ($dto->next_page);
    }
}
