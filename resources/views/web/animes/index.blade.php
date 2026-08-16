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
            <x-ui.subheadline :$label :$href />
            <x-carousels-anime />
            <div class="row mt-5">
                <div class="col">
                    <x-ui.subheadline label="Обновления аниме"/>
                </div>
                <div class="col">
                    <x-ui.subheadline label="Недавно вышедшие аниме"/>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="h2 text-uppercase m-0">Новые аниме на сайте</div>
                    <div>На данной странице отображены аниме, отсортированные по дате добавления</div>
                </div>
                <div class="col-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="h2 text-uppercase m-0">Расписание</div>
                            <div>Даты выхода эпизодов в Японии</div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="h2 text-uppercase m-0">Рекомендуем</div>
                            <div>Блок для наших друзей и спонсоров</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::main>
