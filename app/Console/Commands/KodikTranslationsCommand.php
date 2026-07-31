<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EntryLocale;
use App\Enums\TranslationKind;
use App\Enums\TranslationSource;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Funteam;
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
        $this->connector->query()->add('has_field', 'shikimori_id');
        $this->connector->query()->add('sort', 'year');
        $this->connector->query()->add('order', 'asc');

        /** @var Collection<Funteam> $funteams */
        $funteams = Funteam::all(['id', 'name']);
        $funteams->each(fn (Funteam $funteam) => $funteam->name = Str::lower($funteam->name));

        do
        {
            $res = $this->connector->send(
                new GetMaterialsRequest
            );

            /** @var KodikMaterialsData $dto */
            $dto = $res->dto();

            $this->withProgressBar(
                $dto->results->toArray(),
                static function (MaterialDto $_anime) use ($funteams) {

                    /** @var Funteam $funteam */
                    $funteam = $funteams
                        ->where('name', Str::lower($_anime->translation->title))
                        ->firstOrFail();

                    Translation::firstOrCreate(
                        [
                            'source' => TranslationSource::KODIK,
                            'external_id' => $_anime->translation->id
                        ],
                        [
                            'funteam_id' => $funteam->id,
                            'source' => TranslationSource::KODIK,
                            'external_id' => $_anime->translation->id,
                            'kind' => TranslationKind::fromKodik($_anime->translation->type),
                            'locale' => EntryLocale::RU,
                        ]
                    );
                }
            );

            $this->connector->query()->add('next', $dto->next_page);

            $this->info(' success');
        }
        while ($dto->next_page);
    }
}
