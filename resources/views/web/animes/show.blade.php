@php use App\Models\Release; @endphp

@section('title', $anime->name . ' на ' . config('app.name'))

<x-layouts::main>
    <div class="container mt-4 position-absolute" style="z-index: 999; left: 50%; transform: translateX(-50%);">
        {{ Breadcrumbs::render('animes.show', $anime) }}
    </div>
    <div style="overflow:hidden; opacity: 0.5;">
        <style>
            #top-cover-image {
                background-image: url({{ $poster_url = $anime->getFirstMediaUrl('poster') }});
                height: 250px;
                background-position: center;
                background-size: cover;
                filter: blur(25px);
                -webkit-filter: blur(25px);
            }
        </style>
        <div id="top-cover-image"></div>
    </div>
    <div class="page-body">
        <div class="container">
            <div class="row" style="position: relative; margin-top: -140px;">
                <div class="col-4">
                    <div>
                        <div class="poster">
                            <img
                                class="border img-fluid"
                                src="{{ $poster_url }}"
                                alt="{{ $anime->name }}"
                                style="width: 100%;"
                            >
                        </div>
                        <div class="actions d-flex justify-content-between">
                            <div class="nav-link p-3">
                                <a
                                    href="#"
                                    class="disabled link-azure"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="Комментировать"
                                    data-bs-original-title="Комментировать"
                                >
                                    <svg
                                        style="--tblr-icon-size: 2rem;"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-messages"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"/>
                                        <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="nav-link p-3">
                                <a
                                    href="#"
                                    class="disabled link-azure"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="Написать отзыв"
                                    data-bs-original-title="Написать отзыв"
                                >
                                    <svg
                                        style="--tblr-icon-size: 2rem;"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-message-plus"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M8 9h8"/>
                                        <path d="M8 13h6"/>
                                        <path d="M12.01 18.594l-4.01 2.406v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v5.5"/>
                                        <path d="M16 19h6"/>
                                        <path d="M19 16v6"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="nav-link p-3">
                                <a
                                    href="#"
                                    class="disabled link-azure"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="Написать рецензию"
                                    data-bs-original-title="Написать рецензию"
                                >
                                    <svg
                                        style="--tblr-icon-size: 2rem;"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-pencil-plus"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"/>
                                        <path d="M13.5 6.5l4 4"/>
                                        <path d="M16 19h6"/>
                                        <path d="M19 16v6"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="nav-link p-3">
                                <a
                                    href="#"
                                    class="disabled link-azure"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="Добавить в избранное"
                                    data-bs-original-title="Добавить в избранное"
                                >
                                    <svg
                                        style="--tblr-icon-size: 2rem;"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                        class="icon icon-tabler icons-tabler-filled icon-tabler-star"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="p-3">
                                <a
                                    href="#"
                                    class="disabled link-azure"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    aria-label="Редактировать"
                                    data-bs-original-title="Редактировать"
                                >
                                    <svg
                                        style="--tblr-icon-size: 2rem;"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-settings"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065"/>
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="folders accordion" id="folders-accordion">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <div class="input-group">
                                        <div
                                            class="accordion-body list-group list-group-flush bg-azure-lt text-start flex-grow-1 p-0">
                                            <div
                                                class="list-group-item list-group-item-action d-flex align-items-center border-0 disabled">
                                                <svg
                                                    style="--tblr-icon-size: 2rem;"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="24"
                                                    height="24"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-eye"
                                                >
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                                                </svg>
                                                <span><b class="h3 ms-3">Смотрю</b></span>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            class="collapsed p-3"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#fa-collapse"
                                            aria-expanded="false"
                                        >
                                            <style>
                                                #folders-accordion .accordion-header button:not(.collapsed) {
                                                    transform: rotate(-90deg);
                                                    transition: transform 0.3s ease;
                                                }

                                                #folders-accordion .accordion-header button.collapsed {
                                                    transform: rotate(0deg);
                                                    transition: transform 0.3s ease;
                                                }
                                            </style>
                                            <svg
                                                style="--tblr-icon-size: 2rem;"
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-badge-left"
                                            >
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M11 17h6l-4 -5l4 -5h-6l-4 5l4 5"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="accordion-collapse collapse" id="fa-collapse"
                                     data-bs-parent="#accordion-folders">
                                    <div class="accordion-body list-group list-group-flush text-start p-0">
                                        @foreach([
                                            'Просмотрено',
                                            'Брошено',
                                            'Запланировано',
                                            'Пересматриваю',
                                            'Отложено',
                                        ] as $item)
                                            <button
                                                type="button"
                                                class="list-group-item list-group-item-action border-start-0 border-bottom-0 border-start-0 border-end-0"
                                                style="border-top: var(--tblr-list-group-border-width) solid var(--tblr-list-group-border-color);"
                                                disabled
                                            >
                                                <span>{{ $item }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="header">
                        <div class="rating">
                            <div class="d-inline-flex align-items-center">
                                <div>
                                    <svg
                                        style="--tblr-icon-size: 2rem;"
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-ban text-red"
                                    >
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                                        <path d="M5.7 5.7l12.6 12.6"/>
                                    </svg>
                                </div>
                                <div class="ms-3">
                                    <div style="font-size: 20px; margin-bottom: -5px;">
                                        <span class="strong">Рейтинг</span>
                                    </div>
                                    <span>Не доступен</span>
                                </div>
                            </div>
                        </div>
                        <div class="titles">
                            <h1 class="h1 text-truncate m-0">{{ $anime->name }}</h1>
                            <h2 class="h2 text-truncate m-0 text-muted">{{ $anime->name }}</h2>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6 mb-3">
                            <x-ui.subheadline class="shadow" label="Информация"/>
                            <div class="line-container">
                                <div class="line">
                                    <div class="key">Формат</div>
                                    <div class="value">
                                        <a href="/animes/search?kind={{ $anime->kind }}">{{ $anime->kind->label() }}</a>
                                    </div>
                                </div>
                                <div class="line">
                                    <div class="key">Эпизоды</div>
                                    <div class="value">
                                        {{ $anime->episodes_aired ??= '?' }} из {{ $anime->episodes_total }}
                                    </div>
                                </div>
                                @if($anime->next_episode_at)
                                @php($next_episode_at = $anime->next_episode_at)
                                <div class="line nea" data-timestamp="{{ $next_episode_at->timestamp }}">
                                    <div class="key">Следующий эпизод</div>
                                    <div class="value">{{ $next_episode_at }}</div>
                                </div>
                                @endif
                                <div class="line">
                                    <div class="key">Длительность</div>
                                    <div class="value">{{ $anime->duration }} мин. ~ эпизод</div>
                                </div>
                                <div class="line">
                                    <div class="key">Сезон</div>
                                    <div class="value">
                                        @php($year = $anime->aired_at->format('Y'))
                                        @php($month_name = $anime->aired_at->monthName)
                                        <a
                                            href="/animes/search?season={{ $anime->aired_season }}&year={{ $year }}"
                                        >
                                            {{ $month_name }} {{ $year }}
                                        </a>
                                    </div>
                                </div>
                                <div class="line">
                                    <div class="key">Статус</div>
                                    <div class="value" style="color: {{ $anime->status->color() }};">
                                        <a
                                            href="/animes/search?status={{ $anime->status }}"
                                            class="badge link-white text-decoration-none"
                                            style="background-color: {{ $anime->status->color() }};"
                                        >
                                            {{ $anime->status->label() }}
                                        </a>
                                    </div>
                                </div>
{{--                                <div class="line">--}}
{{--                                    <div class="key">Жанры</div>--}}
{{--                                    <div class="value">nope</div>--}}
{{--                                </div>--}}
                                <div class="line">
                                    <div class="key">Рейтинг MPAA</div>
                                    <div class="value text-uppercase" title="{{ $anime->rating->desc() }}">
                                        <a href="/animes/search?mpaa={{ $anime->rating }}">{{ $anime->rating->label() }}</a>
                                    </div>
                                </div>
{{--                                <div class="line">--}}
{{--                                    <div class="key">По-японски</div>--}}
{{--                                    <div class="value">nope</div>--}}
{{--                                </div>--}}
{{--                                <div class="line">--}}
{{--                                    <div class="key">По-английски</div>--}}
{{--                                    <div class="value">nope</div>--}}
{{--                                </div>--}}
{{--                                <div class="line">--}}
{{--                                    <div class="key">Другие названия</div>--}}
{{--                                    <div class="value">nope</div>--}}
{{--                                </div>--}}
                            </div>
                        </div>

                        <div class="col-6">
                            <x-ui.subheadline class="shadow" label="Студия"/>
                        </div>

                        @php($releases = $anime->releases)

                        @php($releases_dub = $releases
                            ->filter(static fn(Release $release) => $release->translation->kind === 'dub')
                            ->sortBy(static fn(Release $release) => $release->translation->funteam->name)
                        )

                        @if($releases_dub->isNotEmpty())
                        <div class="col-12 mb-3">
                            <x-ui.subheadline class="shadow" label="Озвучка"/>
                            <div class="row row-cols-auto g-2">
                                @foreach($releases_dub as $release)
                                    @php($episodes_count = $release->episodes->count())
                                    @php($episodes_aired = $episodes_count >= $anime->episodes_aired)
                                    <div class="col">
                                        <a href="/watch?r={{ $release->code }}" class="btn">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-microphone"
                                            >
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M9 5a3 3 0 0 1 3 -3a3 3 0 0 1 3 3v5a3 3 0 0 1 -3 3a3 3 0 0 1 -3 -3l0 -5"/>
                                                <path d="M5 10a7 7 0 0 0 14 0"/>
                                                <path d="M8 21l8 0"/>
                                                <path d="M12 17l0 4"/>
                                            </svg>
                                            {{ $release->funteam->name }}
                                            <span
                                                @class([
                                                    'badge',
                                                    'bg-green' => $episodes_aired,
                                                    'bg-pink' => !$episodes_aired,
                                                    'text-green-fg',
                                                    'ms-2',
                                                ])
                                            >
                                                {{ $episodes_count }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @php($releases_sub = $releases
                            ->filter(static fn(Release $release) => $release->translation->kind === 'sub')
                            ->sortBy(static fn(Release $release) => $release->translation->funteam->name)
                        )

                        @if($releases_sub->isNotEmpty())
                        <div class="col-12">
                            <x-ui.subheadline class="shadow" label="Субтитры"/>
                            <div class="row row-cols-auto g-2">
                                @foreach($releases_sub as $release)
                                    @php($episodes_count = $release->episodes->count())
                                    @php($episodes_aired = $episodes_count >= $anime->episodes_aired)
                                    <div class="col">
                                        <a href="/watch?r={{ $release->code }}" class="btn">
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-badge-cc"
                                            >
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10"/>
                                                <path d="M10 10.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0"/>
                                                <path d="M17 10.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0"/>
                                            </svg>
                                            {{ $release->funteam->name }}
                                            <span
                                                @class([
                                                    'badge',
                                                    'bg-green' => $episodes_aired,
                                                    'bg-pink' => !$episodes_aired,
                                                    'text-green-fg',
                                                    'ms-2',
                                                ])
                                            >
                                                {{ $episodes_count }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col my-5">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title fw-bold">Описание аниме "{{ $anime->name }}"</h3>
                            <p>desc</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::main>
