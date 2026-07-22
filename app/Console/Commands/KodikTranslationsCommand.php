<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EntryLocale;
use App\Enums\TranslationKind;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetSearchRequest;
use App\Models\Funteam;
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
        $this->connector->query()->add('types', 'anime,anime-serial');
        $this->connector->query()->add('with_seasons', true);
        $this->connector->query()->add('with_episodes', true);
        $this->connector->query()->add('shikimori_id', 63832);

        $res = $this->connector->send(
            new GetSearchRequest
        );

        /** @var KodikMaterialsData $dto */
        $dto = $res->dto();

        $this->withProgressBar(
            $dto->results->toArray(),
            static function (MaterialDto $anime) {
                /** @var Funteam $translation */
                $translation = Funteam::query()->where('name', $anime->translation->title)->firstOrFail();

                Translation::firstOrCreate(
                    [
                        'source' => 'kodik',
                        'external_id' => $anime->translation->id
                    ],
                    [
                        'funteam_id' => $translation->id,
                        'source' => 'kodik',
                        'external_id' => $anime->translation->id,
                        'kind' => match ($anime->translation->type) {
                            'voice' => TranslationKind::DUB,
                            'subtitles' => TranslationKind::SUB,
                            default => throw new \InvalidArgumentException('unknown translation type.'),
                        },
                        'locale' => EntryLocale::RU,
                        'link' => $anime->link,
                    ]
                );
            }
        );

        $this->newLine();

        $this->info('success');
    }
}
