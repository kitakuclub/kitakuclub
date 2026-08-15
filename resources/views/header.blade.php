<header class="navbar navbar-expand-md py-2 d-print-none">
    <div class="container">
        <button
            class="navbar-toggler me-3"
            style="font-size: 25px;"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar-menu"
            aria-controls="navbar-menu"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal me-3">
            <a
                href="/" class="text-decoration-none d-flex align-items-center link-azure"
                title="{{ config('app.name') }}"
                style="display: flex;"
            >
                <style>
                    .glyph {
                        fill: #4299e1;
                        font-family: "Potta One", serif;
                        font-weight: 400;
                        font-style: normal;
                        font-size: 20px;
                        width: 32px;
                        height: 32px;
                        margin-right: 5px;
                    }
                    .title {
                        font-family: "Potta One", serif;
                        font-weight: 400;
                        font-style: normal;
                        font-size: 34px;
                    }
                </style>
                <svg class="glyph" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="64" height="64">
                    <text x="50%" y="45%" text-anchor="middle" dominant-baseline="central">帰</text>
                </svg>
                <div class="h1 title text-uppercase m-0" title="Китаку">
                    <span>{{ config('app.name') }}</span>
                </div>
            </a>
        </div>
        <div class="flex-grow-1">
            <a
                href="https://t.me/kitakuclub"
                target="_blank"
                rel="noreferrer"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                aria-label="Канал в Telegram"
                data-bs-original-title="Канал в Telegram"
            >
                <img
                    style="margin: 5px;"
                    src="{{ asset('static/media/brands/telegram.svg') }}"
                    width="35"
                    alt="Telegram"
                >
            </a>
        </div>
        <div class="navbar-nav flex-row order-md-last align-items-center">
            @guest
                <a
                    href="#"
                    @class([
                        'btn', 'rounded', 'text-uppercase', 'disabled',
                        'active' => request()->routeIs(['login', 'register'])
                    ])
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-login-2"
                    >
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 8v-2a2 2 0 0 1 2 -2h7a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-2" />
                        <path d="M3 12h13l-3 -3" />
                        <path d="M13 15l3 -3" />
                    </svg>
                    <span>Вход</span>
                </a>
            @else
                @php($user = auth()->user())
                <div class="nav-item me-3">
                    <div class="btn-list">
                        @foreach([
                            ['label' => 'Мои уведомления', 'icon' => 'fa fa-bell'],
                            ['label' => 'Мои сообщения', 'icon' => 'fa fa-envelope'],
                        ] as $link)
                            <a
                                href="/"
                                class="nav-link px-0 disabled"
                                title="{{ $link['label'] }}"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                            >
                                <i class="{{ $link['icon'] }}" style="font-size: 30px;"></i>
                                <small class="badge badge-pill text-light bg-danger" style="top: 10px;">
                                    <span>99</span>
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="nav-item">
                    <a
                        href="#"
                        @class([
                            'nav-link', 'lh-1', 'p-2',
                            'bg-azure-lt' => request()->user?->equals($user) && request()->routeIs('users.show')
                        ])
                    >
                        <span class="avatar avatar-sm" style="background-image: url('{{ $user->gravatar() }}');"></span>
                        <div class="d-none d-lg-block ps-2">
                            <div class="fw-bold">
                                <span>{{ $user->nickname }}</span>
                            </div>
                            <div class="mt-1 small text-muted text-uppercase">Профиль</div>
                        </div>
                    </a>
                </div>
            @endguest
        </div>
        {{--<div class="collapse navbar-collapse" id="navbar-menu"></div>--}}
    </div>
</header>
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar p-0">
            <div class="container flex-row-reverse">
                <ul class="navbar-nav text-uppercase">
                    <li class="nav-item">
                        <a
                            @class(['nav-link', 'bg-azure-lt' => request()->routeIs('animes')])
                            href="{{ route('animes') }}"
                        >
                            <span class="nav-link-title">Главная</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            @class(['nav-link', 'bg-azure-lt' => request()->routeIs('animes.catalog')])
                            href="{{ route('animes.catalog') }}"
                        >
                            <span class="nav-link-title">Каталог</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="/">
                            <span class="nav-link-title">Случайное</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="/">
                            <span class="nav-link-title">Топ-100</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#nav-community"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            role="button"
                            aria-expanded="false"
                        >
                            <span class="nav-link-title me-1">Сообщество</span>
                        </a>
                        <div style="margin-top: 4px;" class="dropdown-menu dropdown-menu-end rounded-0 shadow-none border-top-0">
                            <a class="disabled dropdown-item" href="/">
                                <span>Рецензии</span>
                            </a>
                            <a class="disabled dropdown-item" href="/">
                                <span>Комментарии</span>
                            </a>
                            <div class="dropdown-divider my-1"></div>
                            <a class="dropdown-item" href="https://t.me/kitakuclub_chat" target="_blank">
                                <span>Чат</span>
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
                                    class="ms-1 icon icon-tabler icons-tabler-outline icon-tabler-external-link"
                                >
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
                                    <path d="M11 13l9 -9"></path>
                                    <path d="M15 4h5v5"></path>
                                </svg>
                            </a>
                        </div>
                    </li>
                </ul>
                <div class="flex-grow-1 flex-md-grow-0 order-first order-md-last">
                    <ul class="navbar-nav text-uppercase">
                        <li class="nav-item">
                            <a href="/animes/search?status=ongoing" class="nav-link link-danger">Онгоинги</a>
                        </li>
                        @for($i = 0; $i < 3; $i++)
                            @php($year = date('Y') - $i)
                            <li class="nav-item">
                                <a
                                    href="{{ route('animes.search', ['year' => $year]) }}"
                                    class="nav-link link-danger"
                                >
                                    {{ $year }} год
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
@if (session()->has('flash'))
    <div class="container">
        <div class="alert alert-{{ session('flash.type') }} alert-dismissible fade show m-0 mt-4" role="alert">
            <span>{!! session('flash.message') !!}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
