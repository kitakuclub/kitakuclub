<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Http\Integrations\Kodik\Requests\GetTranslationsRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('kodik:test')]
#[Description('Command description')]
class TestCommand extends Command
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
    public function handle()
    {
        $this->connector->query()->add('limit', 3);
        $this->connector->query()->add('types', 'anime,anime-serial');

        $res = $this->connector->send(
            new GetMaterialsRequest(),
//            new GetTranslationsRequest(),
        );

        dd($res->dto(), $res->array());

        $this->info('Test command started');
    }
}
