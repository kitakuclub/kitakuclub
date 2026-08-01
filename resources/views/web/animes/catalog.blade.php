@section('title', 'Каталог аниме')

<x-layouts::main>
    <div class="container mt-4">
        {{ Breadcrumbs::render('animes.catalog') }}
    </div>
    <x-page-header-animes-catalog />
    <div class="page-body">
        <div class="container">

            <!-- Сетка с автоматическим распределением колонок и отступами row-gap -->
            <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 g-4">

                <style>
                    /* Базовые стили анимации для постера */
                    .movie-card .transition-zoom {
                        transition: transform 0.3s ease;
                    }

                    /* Относительное позиционирование для контейнера, чтобы блик не вылетал за края */
                    .card-media-wrap {
                        position: relative;
                        cursor: pointer;
                        /* Защита от багов скругления в некоторых браузерах при анимации */
                        transform: translateZ(0);
                    }

                    /* Создаем невидимую полосу градиента, повернутую на 45 градусов */
                    .vfx-glare {
                        position: absolute;
                        top: 0;
                        left: -150%; /* Прячем блик далеко слева */
                        width: 100%;
                        height: 100%;
                        background: linear-gradient(
                            90deg,
                            rgba(255, 255, 255, 0) 0%,
                            rgba(255, 255, 255, 0.25) 50%,
                            rgba(255, 255, 255, 0) 100%
                        );
                        transform: skewX(-25deg); /* Наклоняем вспышку для динамичности */
                        transition: none;
                    }

                    /* Эффекты при наведении на карточку */
                    .movie-card:hover .transition-zoom {
                        transform: scale(1.08);
                    }

                    /* Заставляем блик быстро пробежать слева направо */
                    .movie-card:hover .vfx-glare {
                        left: 150%;
                        transition: left 0.6s ease-out;
                    }

                    /* Контейнер оверлея: растянут на весь постер, изначально скрыт и слегка уменьшен */
                    .play-overlay {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.2); /* Легкое затемнение постера при ховере */
                        opacity: 0;
                        transform: scale(1);
                        transition: opacity 0.3s ease, transform 0.3s ease;
                        z-index: 2; /* Выше постера, но ниже ленточки/блика по необходимости */
                    }

                    /* Круглая подложка для кнопки Play */
                    .play-icon-circle {
                        width: 56px;
                        height: 56px;
                        background-color: rgba(13, 110, 253, 0.9); /* Фирменный синий Bootstrap (btn-primary), можно заменить на rgba(0,0,0,0.7) */
                        border-radius: 50%;
                        transition: transform 0.2s ease, background-color 0.2s ease;
                    }

                    /* Появление оверлея при наведении на карточку */
                    .movie-card:hover .play-overlay {
                        opacity: 1;
                        transform: scale(1);
                    }

                    /* Эффект легкого увеличения самой кнопки при наведении на нее */
                    .play-overlay:hover .play-icon-circle {
                        transform: scale(1.1);
                        background-color: rgb(13, 110, 253); /* Делает цвет ярче при наведении непосредственно на кнопку */
                    }

                    /* Смещаем блик на слой выше, чтобы он пробегал и поверх иконки тоже */
                    .vfx-glare {
                        z-index: 3;
                    }
                </style>

                @foreach($animes_list as $anime)
                    <a href="/animes/{{ $anime->id }}/{{ $anime->slug }}" class="col text-decoration-none" title="Смотреть {{ $anime->name }}">
                        <div class="border-0 bg-transparent h-100 movie-card">
                            <div class="position-relative overflow-hidden rounded-3 card-media-wrap">
                                @if($anime->next_episode_at)
                                    @php($is_past = $anime->next_episode_at->isPast())
                                    <div
                                        @class([
                                            'p-2',
                                            'badge',
                                            'w-100',
                                            'position-absolute',
                                            'rounded-bottom-0',
                                            'bg-pink' => !$is_past,
                                            'bg-green' => $is_past,
                                        ])
                                        style="z-index: 999;"
                                    >
                                        <span class="text-white">
                                            @if(!$is_past)
                                                след. серия выйдет
                                            @else
                                                серия {{ $anime->episodes_aired }} вышла
                                            @endif
                                            <b>{{ $anime->next_episode_at->ago() }}</b>
                                        </span>
                                    </div>
                                @endif
                                <img
                                    src="https://cdn.myanimelist.net/images/anime/1145/158339.jpg"
                                    class="card-img-top img-fluid transition-zoom"
                                    alt="Постер"
                                >

                                <!-- Иконка Play по центру (SVG для независимости от сторонних шрифтов) -->
                                <div class="play-overlay d-flex align-items-center justify-content-center">
                                    <div class="play-icon-circle d-flex align-items-center justify-content-center shadow">
                                        <svg xmlns="http://w3.org" width="28" height="28" fill="currentColor" class="bi bi-play-fill text-white ms-1" viewBox="0 0 16 16">
                                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                                        </svg>
                                    </div>
                                </div>

                                <div
                                    class="ribbon ribbon-start ribbon-bottom rounded-end-5"
                                    style="background-color: {{ $anime->status->color() }};"
                                >
                                    <span class="h4 m-0">{{ $anime->status->label() }}</span>
                                </div>

                                <div class="vfx-glare"></div>
                            </div>

                            <div class="card-body px-0 pt-2">
                                <h6 class="card-title fw-semibold mb-1 text-truncate">{{ $anime->name }}</h6>
                                <div class="d-flex align-items-center gap-2 text-muted text-uppercase">
                                    <div>{{ $anime->kind->label() }}</div>
                                    <div class="ms-auto">{{ $anime->aired_at?->format('Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
            <div class="col-lg-12 mt-5">
                {{ $animes_list->onEachSide(0)->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</x-layouts::main>
