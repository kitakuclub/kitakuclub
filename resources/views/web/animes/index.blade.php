@php use App\Enums\EntrySeason; @endphp

@section('title', config('app.name') . ' — Аниме')

<x-layouts::main>
    <div class="container mt-4">
        {{ Breadcrumbs::render('animes') }}
    </div>
    <div class="page-body">
        <div class="container">
            @php
                $season = EntrySeason::fromDate(now());
                $href = route('animes.search', ['year' => now()->year, 'season' => EntrySeason::fromDate(now())]);
                $label = $season->animeSeasonLabel();
            @endphp
            <x-ui.subheadline :$label :$href>
                <x-carousels-anime />
            </x-ui.subheadline>
            <x-ui.subheadline label="Новые релизы">
                <x-newest-anime />
            </x-ui.subheadline>
            <div class="row">
                <div class="col">
                    <x-ui.subheadline label="Обновления аниме">
                        <x-updates-anime />
                    </x-ui.subheadline>
                </div>
                <div class="col">
                    <x-ui.subheadline label="Недавно вышедшие аниме">
                        <x-completed-anime />
                    </x-ui.subheadline>
                </div>
            </div>
            <div class="h2 text-uppercase m-0">Новое на сайте</div>
            <div class="mb-4">На данной странице отображены аниме, отсортированные по дате добавления</div>
            <x-cols-anime :$animes_list />
        </div>
    </div>
</x-layouts::main>
