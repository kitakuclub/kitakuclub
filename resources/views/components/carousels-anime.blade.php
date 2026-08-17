@props([
    'items' => [],
    'slidesPerView' => 5,
])

@php
    $carouselId = uniqid();
@endphp

<div class="anime-carousel-wrapper flickity-x">
    <div class="anime-carousel" id="{{ $carouselId }}">
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach($items as $item)
                    <div class="swiper-slide" title="{{ $item['title'] }}">
                        <a href="{{ $item['url'] ?? '#' }}" class="anime-card-link">
                            <div class="anime-card-poster">
                                <img
                                    src="{{ $item['image'] }}"
                                    alt="{{ $item['title'] }}"
                                    loading="lazy"
                                >
                            </div>
                            <div class="anime-card-title text-truncate">
                                {{ $item['title'] }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <button class="flickity-button flickity-prev-next-button next" type="button">
            <svg class="flickity-button-icon" viewBox="0 0 100 100">
                <path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow" transform="translate(100, 100) rotate(180)"></path>
            </svg>
        </button>
        <button class="flickity-button flickity-prev-next-button prev" type="button">
            <svg class="flickity-button-icon" viewBox="0 0 100 100">
                <path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow"></path>
            </svg>
        </button>
    </div>
</div>

@pushonce('head-style')
    <style>
        .flickity-x {
            position: relative;
        }

        .flickity-x:hover .flickity-button {
            opacity: 1;
        }

        .flickity-prev-next-button.next {
            right: 0;
        }

        .flickity-prev-next-button.prev {
            left: 0;
        }

        .flickity-prev-next-button {
            top: 50%;
            transform: translateY(-80%);
            width: 40px;
            height: 50px;
            transition: all .3s;
        }

        .flickity-prev-next-button .flickity-button-icon {
            position: absolute;
            left: 20%;
            top: 20%;
            width: 60%;
            height: 60%;
        }

        .flickity-button {
            position: absolute;
            opacity: 0;
            background: rgba(51, 51, 51, .6);
            border: none;
            color: #fff;
            z-index: 10;
        }

        .flickity-button:hover {
            background: #333;
            cursor: pointer;
        }

        .flickity-button-icon {
            fill: currentColor;
        }

        .anime-card-link {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            color: inherit;
        }

        .anime-card-poster {
            position: relative;
            aspect-ratio: 2 / 3;
            overflow: hidden;
            background: #1a1a1a;
            flex-shrink: 0;
        }

        .anime-card-poster img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform .3s ease;
        }

        .anime-card-poster::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .3);
            opacity: 0;
            transition: opacity .3s ease;
            pointer-events: none;
        }

        .anime-card-link:hover .anime-card-poster img {
            transform: scale(1.10);
        }

        .anime-card-link:hover .anime-card-poster::after {
            opacity: 1;
        }

        .anime-card-title {
            font-size: 14px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            position: absolute;
            bottom: 0;
            padding: 10px;
            width: 100%;
            background-color: rgba(0, 0, 0, .8);
            color: #fff;
            z-index: 1;
        }

        .swiper-slide {
            height: auto;
        }
    </style>
@endpushonce

@pushonce('body-script')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const root = document.getElementById('{{ $carouselId }}');

            if (!root || root.dataset.initialized) return;

            root.dataset.initialized = 'true';

            new swiper(root.querySelector('.swiper'), {
                slidesPerView: 3,
                spaceBetween: 2,
                navigation: {
                    nextEl: root.querySelector('button.next'),
                    prevEl: root.querySelector('button.prev'),
                },
                breakpoints: {
                    480:  { slidesPerView: 3.2, spaceBetween: 2 },
                    768:  { slidesPerView: 4.2, spaceBetween: 2 },
                    1024: { slidesPerView: {{ $slidesPerView }}, spaceBetween: 2 },
                },
                modules: [navigation, pagination],
            });
        });
    </script>
@endpushonce
