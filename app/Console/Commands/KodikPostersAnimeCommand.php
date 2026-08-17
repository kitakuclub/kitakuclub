<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SourceName;
use App\Http\Integrations\Kodik\DTOs\MaterialDto;
use App\Http\Integrations\Kodik\KodikConnector;
use App\Http\Integrations\Kodik\Requests\GetMaterialsRequest;
use App\Models\Anime;
use App\Values\KodikMaterialsData;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

#[Signature('kodik:posters:anime')]
#[Description('Command description')]
class KodikPostersAnimeCommand extends Command
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
        $this->connector->query()->add('sort', 'updated_at');
        $this->connector->query()->add('order', 'desc');
        $this->connector->query()->add('with_material_data', true);

        do
        {
            $res = $this->connector->send(
                new GetMaterialsRequest
            );

            /** @var KodikMaterialsData $dto */
            $dto = $res->dto();

            $posters = [];

            $this->withProgressBar(
                $dto->results->toArray(),
                static function (MaterialDto $_anime) use (&$posters) {

                    if (is_null($_anime->material_data))
                        return;

                    /** @var Anime|null $anime */
                    $anime = Anime::query()
                        ->whereHasSourcesByNames(
                            $_anime->sources,
                            [
                                SourceName::KODIK,
                                SourceName::SHIKIMORI,
                            ]
                        )
                        ->first();

                    if (is_null($anime)) {
                        return;
                    }

                    /** @var string|null $poster_url */
                    $poster_url = $_anime->material_data->poster_url;

                    if ($poster_url && !$anime->hasMedia('poster')) {
                        $posters[$anime->id] = ['anime' => $anime, 'url' => $poster_url];
                    }
                }
            );

            $this->upload($posters);

            $this->connector->query()->add('next', $dto->next_page);

            $this->info(' success');
        }
        while ($dto->next_page);
    }

    /**
     * @param array<int|string, array{
     *     anime: Anime,
     *     url: string,
     * }> $posters
     */
    private function upload(array $posters): void
    {
        if (empty($posters))
            return;

        foreach (array_chunk($posters, 20, true) as $chunk) {

            $responses = Http::pool(static fn(Pool $pool) => collect($chunk)
                ->map(static fn(array $item, int $key) => $pool
                    ->as(strval($key))
                    ->withOptions(['cookies' => new CookieJar()])
                    ->timeout(15)
                    ->get($item['url']))
                ->all());

            foreach ($responses as $key => $response) {

                if (!$response->successful())
                    continue;

                /** @var Anime $anime */
                $anime = $chunk[$key]['anime'];
                /** @var string $poster_url */
                $poster_url = $chunk[$key]['url'];

                $extension = pathinfo(parse_url($poster_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpeg';
                $name = hash('md5', uniqid((string)$key, true));

                $anime
                    ->addMediaFromString($response->body())
                    ->usingFileName($name . '.' . $extension)
                    ->usingName($name)
                    ->toMediaCollection('poster');
            }
        }
    }
}
