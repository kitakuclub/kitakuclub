<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\Kodik\DTOs\TranslationDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use App\Models\Funteam;
use App\Values\KodikTranslationsData;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('kodik:funteams')]
#[Description('Command description')]
class KodikFunteamsCommand extends Command
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

        $res = $this->connector->send(
            new GetTranslationsRequest
        );

        /** @var KodikTranslationsData $dto */
        $dto = $res->dto();

        $translations = $dto->results->unique(
            static fn(TranslationDto $translation) => Str::lower(trim($translation->title))
        );

        $this->withProgressBar(
            $translations->toArray(),
            static fn(TranslationDto $translation) => Funteam::firstOrCreate(
                ['name' => $translation->title],
                ['slug' => uniqid()],
            )
        );

        $this->newLine();

        $this->info('success');
    }
}
