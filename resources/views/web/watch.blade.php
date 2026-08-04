@php use App\Models\Episode; @endphp

@php($title = $anime->name . ' смотреть аниме онлайн')

@section('title', $title)

<x-layouts::main>
    <div class="container mt-4 position-absolute" style="z-index: 999; left: 50%; transform: translateX(-50%);">
        {{ Breadcrumbs::render('animes.watch', $anime, $release) }}
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

            <style>
                :root {
                    --bg-body: #0e1116;
                    --bg-panel: #12151b;
                    --bg-thumb: #171d27;
                    --pill-bg: #242a34;
                    --pill-bg-active: #2c333f;
                    --text-dim: #8b93a1;
                    --text-strong: #e7e9ec;
                    --accent: #ff6a4d;
                }

                .player-card {
                    width: 100%;
                    background: var(--bg-panel);
                    border-radius: .75rem;
                    overflow: hidden;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, .5);
                }

                .player-bar {
                    background: #12203a;
                    border-radius: .5rem;
                    padding: .5rem .75rem;
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    /* без flex-wrap: дочерние элементы сами решают, сжиматься им или скроллиться */
                }

                .icon {
                    width: 1.15rem;
                    height: 1.15rem;
                    flex: 0 0 auto;
                }

                .ep-label {
                    color: #8a94a6;
                    font-size: .85rem;
                    white-space: nowrap;
                    flex: 0 0 auto;
                }

                /* текущий номер серии — выпадающий выбор */
                .ep-current {
                    background: #1c2c4c;
                    border: 1px dashed #4a5a7a;
                    color: #fff;
                    font-weight: 600;
                    border-radius: .4rem;
                    padding: .25rem .85rem;
                    display: flex;
                    align-items: center;
                    gap: .35rem;
                    cursor: pointer;
                    flex: 0 0 auto;
                    white-space: nowrap;
                }

                .ep-current:hover {
                    background: #233457;
                }

                .ep-current .icon {
                    width: .9rem;
                    height: .9rem;
                }

                /* поиск серии внутри дропдауна */
                .ep-search-menu {
                    width: 200px;
                    padding: .5rem;
                }

                .ep-search-input {
                    background: #0d1830;
                    border: 1px solid #2a3a5c;
                    color: #fff;
                    font-size: .9rem;
                    padding: .4rem .6rem;
                    border-radius: .4rem;
                    width: 100%;
                    margin-bottom: .5rem;
                }

                .ep-search-input:focus {
                    outline: none;
                    border-color: #4a5a7a;
                }

                .ep-search-input::placeholder {
                    color: #5b6577;
                }

                .ep-search-results {
                    max-height: 220px;
                    overflow-y: auto;
                    display: flex;
                    flex-direction: column;
                    gap: .15rem;
                }

                .ep-search-item {
                    background: none;
                    border: none;
                    text-align: left;
                    color: #c7ccd6;
                    font-size: .9rem;
                    padding: .35rem .6rem;
                    border-radius: .35rem;
                    width: 100%;
                }

                .ep-search-item:hover {
                    background: #1c2c4c;
                }

                .ep-search-item.active {
                    background: #22314f;
                    color: #e8546b;
                    font-weight: 600;
                }

                .ep-search-empty {
                    color: #5b6577;
                    font-size: .85rem;
                    padding: .35rem .6rem;
                }

                /* === зона со списком серий === */
                .ep-zone {
                    flex: 1 1 auto;
                    min-width: 0; /* без этого flex-элемент не сжимается и рвёт бар */
                    display: flex;
                    align-items: center;
                    gap: .5rem;
                }

                /* обёртка только вокруг скролла — затемнение по краям вешаем именно сюда,
                   а не на всю .ep-zone, иначе оно перекрывает лейбл "Серия" */
                .ep-scroll-wrap {
                    position: relative;
                    flex: 1 1 auto;
                    min-width: 0;
                }

                .ep-scroll {
                    overflow-x: auto;
                    scrollbar-width: none; /* Firefox */
                    -ms-overflow-style: none;
                }

                .ep-scroll::-webkit-scrollbar {
                    display: none;
                }

                /* Chrome/Safari */

                .ep-scroll-inner {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    width: max-content; /* контент не сжимается сам по себе, скроллится контейнер */
                    padding: 0 .6rem;
                }

                /* лёгкое затемнение по краям именно скролл-зоны */
                .ep-scroll-wrap::before,
                .ep-scroll-wrap::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    width: 1.25rem;
                    pointer-events: none;
                    z-index: 1;
                }

                .ep-scroll-wrap::before {
                    left: 0;
                    background: linear-gradient(90deg, #12203a, transparent);
                }

                .ep-scroll-wrap::after {
                    right: 0;
                    background: linear-gradient(270deg, #12203a, transparent);
                }

                .ep-item {
                    background: none;
                    border: none;
                    color: #8a94a6;
                    font-size: .95rem;
                    padding: .25rem .5rem;
                    border-radius: .4rem;
                    white-space: nowrap;
                    flex: 0 0 auto;
                }

                .ep-item:hover {
                    color: #fff;
                }

                .ep-item.active {
                    background: #22314f;
                    color: #e8546b;
                    font-weight: 600;
                }

                .nav-arrow {
                    background: none;
                    border: none;
                    color: #8a94a6;
                    padding: .25rem .4rem;
                    flex: 0 0 auto;
                    display: flex;
                    align-items: center;
                }

                .nav-arrow:hover {
                    color: #fff;
                }

                .nav-arrow:disabled {
                    opacity: .35;
                    pointer-events: none;
                }

                .divider {
                    width: 1px;
                    align-self: stretch;
                    background: #2a3a5c;
                    flex: 0 0 auto;
                }

                .watched-btn {
                    background: none;
                    border: none;
                    color: #8a94a6;
                    font-size: .9rem;
                    display: flex;
                    align-items: center;
                    gap: .4rem;
                    white-space: nowrap;
                    flex: 0 0 auto;
                }

                .watched-btn:hover {
                    color: #fff;
                }

                .bell-btn {
                    background: #1c2c4c;
                    border: none;
                    color: #8a94a6;
                    width: 2.25rem;
                    height: 2.25rem;
                    border-radius: .5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex: 0 0 auto;
                }

                .bell-btn:hover {
                    color: #fff;
                    background: #233457;
                }

                .bell-btn .icon {
                    width: 1.05rem;
                    height: 1.05rem;
                }

                /* на узких экранах текстовые лейблы прячем, чтобы освободить место под сам список */
                @media (max-width: 640px) {
                    .watched-btn span {
                        display: none;
                    }

                    .ep-label:not(.ep-label-current) {
                        display: none;
                    }
                }
            </style>

            <div style="position: relative; margin-top: -140px;">

                <div class="player-card">
                    <div
                        id="playerRoot"
                        data-episodes="{{ $release->episodes->map(static fn (Episode $e) => ['number' => $e->number, 'link' => $e->link])->toJson() }}"
                        data-current="1"
                    >
                        <iframe
                            id="playerFrame"
                            src=""
                            class="shadow"
                            width="100%"
                            height="600"
                            allowfullscreen
                        ></iframe>
                    </div>

                    <div class="player-bar" style="margin:0 auto;">

                        <span class="ep-label ep-label-current">Серия №</span>

                        <button type="button" class="ep-current" data-bs-toggle="dropdown">
                            <span id="currentEp">1</span>
                            <svg
                                style="--tblr-icon-size: 2rem;"
                                class="icon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6 9l6 6l6 -6"/>
                            </svg>
                        </button>

                        <div class="dropdown-menu dropdown-menu-dark ep-search-menu">
                            <input
                                type="text"
                                inputmode="numeric"
                                autocomplete="off"
                                class="ep-search-input"
                                id="epSearchInput"
                                placeholder="Номер серии…"
                            >
                            <div class="ep-search-results" id="epSearchResults"></div>
                        </div>

                        <div class="ep-zone">
                            <span class="ep-label">Серия</span>
                            <div class="ep-scroll-wrap">
                                <div class="ep-scroll" id="epScroll">
                                    <div class="ep-scroll-inner" id="epList">
                                        {{-- кнопки генерируются JS --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="nav-arrow" id="scrollPrev">
                            <svg
                                style="--tblr-icon-size: 2rem;"
                                class="icon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M15 6l-6 6l6 6"/>
                            </svg>
                        </button>
                        <button type="button" class="nav-arrow" id="scrollNext">
                            <svg
                                style="--tblr-icon-size: 2rem;"
                                class="icon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 6l6 6l-6 6"/>
                            </svg>
                        </button>

                        <div class="divider"></div>

                        <button type="button" class="watched-btn">
                            <svg
                                style="--tblr-icon-size: 2rem;"
                                class="icon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/>
                                <path
                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/>
                            </svg>
                            <span>В просмотренное</span>
                        </button>

                        <button type="button" class="bell-btn">
                            <svg
                                style="--tblr-icon-size: 2rem;"
                                class="icon"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path
                                    d="M14.235 19c.865 0 1.322 1.024 .745 1.668a3.992 3.992 0 0 1 -2.98 1.332a3.992 3.992 0 0 1 -2.98 -1.332c-.552 -.616 -.158 -1.579 .634 -1.661l.11 -.006h4.471z"/>
                                <path
                                    d="M12 2c1.358 0 2.506 .903 2.875 2.141l.046 .171l.008 .043a8.013 8.013 0 0 1 4.024 6.069l.028 .287l.019 .289v2.931l.021 .136a3 3 0 0 0 1.143 1.847l.167 .117l.162 .099c.86 .487 .56 1.766 -.377 1.864l-.116 .006h-16c-1.028 0 -1.387 -1.364 -.493 -1.87a3 3 0 0 0 1.472 -2.063l.021 -.143l.001 -2.97a8 8 0 0 1 3.821 -6.454l.248 -.146l.01 -.043a3.003 3.003 0 0 1 2.562 -2.29l.182 -.017l.176 -.004z"/>
                            </svg>
                        </button>

                    </div>
                </div>

            </div>

            <script type="module">
                $(function () {

                    const $root = $('#playerRoot');

                    const episodesArr = $root.data('episodes'); // @todo сделать через api
                    const ACTIVE_EPISODE = Number($root.data('current'));

                    const $epList = $('#epList');
                    const $currentEp = $('#currentEp');
                    const $epScroll = $('#epScroll');
                    const $scrollPrev = $('#scrollPrev');
                    const $scrollNext = $('#scrollNext');
                    const $epToggle = $('.ep-current');
                    const $epSearchInput = $('#epSearchInput');
                    const $epSearchResults = $('#epSearchResults');
                    const $playerFrame = $('#playerFrame');

                    function findEpisode(n) {
                        return episodesArr.find(e => Number(e.number) === Number(n));
                    }

                    // подставляет в iframe ссылку КОНКРЕТНОЙ серии, а не меняет query-параметр,
                    // т.к. у каждой серии свой самостоятельный link
                    function setPlayerEpisode(n) {
                        const iframe = $playerFrame[0];
                        const ep = findEpisode(n);

                        if(!iframe || !ep) {
                            return;
                        }

                        // если к ссылке серии всё ещё нужно приклеить постоянные query-параметры
                        // (translations=false&only_season=true) — делаем это через URL,
                        // а не строкой, чтобы не задвоить "?" если он уже есть в link
                        const url = new URL('https:' + ep.link);

                        url.searchParams.set('translations', 'false');

                        $(iframe).attr('src', url.toString());
                    }

                    function setActive(n) {
                        // подсветка в горизонтальном списке серий
                        $epList.find('.ep-item').each(function () {
                            $(this).toggleClass('active', Number($(this).data('ep')) === Number(n));
                        });

                        $currentEp.text(n);

                        setPlayerEpisode(n);

                        // докручиваем список так, чтобы выбранная серия была видна
                        const $activeBtn = $epList.find('.ep-item.active');

                        if ($activeBtn.length) {
                            const target = $activeBtn[0].offsetLeft - ($epScroll.width() / 2) + ($activeBtn.width() / 2);
                            $epScroll[0].scrollTo({left: Math.max(0, target), behavior: 'smooth'});
                        }
                    }

                    function closeDropdown() {
                        // не полагаемся на глобальный `bootstrap` — при сборке через vite/ESM
                        // (@tabler/core как модуль) он не попадает в window, поэтому закрываем
                        // дропдаун напрямую переключением тех же классов/атрибутов
                        $epToggle.removeClass('show').attr('aria-expanded', 'false');
                        $epToggle.next('.dropdown-menu').removeClass('show');
                    }

                    // рендер отфильтрованного списка результатов поиска
                    function renderSearchResults(query) {
                        const q = query.trim();
                        const filtered = q ? episodesArr.filter(e => String(e.number).includes(q)) : episodesArr;

                        $epSearchResults.empty();

                        if (filtered.length === 0) {
                            $('<div class="ep-search-empty">Ничего не найдено</div>').appendTo($epSearchResults);
                            return;
                        }

                        filtered.forEach(ep => {
                            $('<button type="button" class="ep-search-item"></button>')
                                .toggleClass('active', Number(ep.number) === Number($currentEp.text()))
                                .text('Серия ' + ep.number)
                                .on('click', () => {
                                    setActive(ep.number);
                                    closeDropdown();
                                })
                                .appendTo($epSearchResults);
                        });
                    }

                    $epSearchInput.on('input', function () {
                        renderSearchResults($(this).val());
                    });

                    // Enter — переход на точное совпадение, иначе на первый результат фильтра
                    $epSearchInput.on('keydown', function (e) {
                        if (e.key !== 'Enter') {
                            return;
                        }

                        e.preventDefault();

                        const q = $(this).val().trim();

                        if (!q) {
                            return;
                        }

                        const exact = episodesArr.find(ep => String(ep.number) === q);
                        const firstMatch = episodesArr.find(ep => String(ep.number).includes(q));
                        const match = exact ?? firstMatch;

                        if (match) {
                            setActive(match.number);
                            closeDropdown();
                        }
                    });

                    // при каждом открытии — сброс поля и фокус на нём
                    $epToggle.on('shown.bs.dropdown', function () {
                        $epSearchInput.val('');
                        renderSearchResults('');
                        $epSearchInput.trigger('focus');
                    });

                    // генерация кнопок серий в горизонтальном списке — из реальных номеров коллекции
                    episodesArr.forEach(ep => {
                        $('<button type="button" class="ep-item"></button>')
                            .toggleClass('active', Number(ep.number) === ACTIVE_EPISODE)
                            .attr('data-ep', ep.number)
                            .text(ep.number)
                            .on('click', () => setActive(ep.number))
                            .appendTo($epList);
                    });

                    function updateArrows() {
                        const el = $epScroll[0];

                        $scrollPrev.prop('disabled', el.scrollLeft <= 0);
                        $scrollNext.prop('disabled', el.scrollLeft + el.clientWidth >= el.scrollWidth - 1);
                    }

                    $scrollPrev.on('click', () => $epScroll[0].scrollBy({left: -160, behavior: 'smooth'}));
                    $scrollNext.on('click', () => $epScroll[0].scrollBy({left: 160, behavior: 'smooth'}));
                    $epScroll.on('scroll', updateArrows);
                    $(window).on('resize', updateArrows);

                    updateArrows();
                    setActive(ACTIVE_EPISODE);
                    renderSearchResults('');
                });
            </script>

        </div>
    </div>
</x-layouts::main>
