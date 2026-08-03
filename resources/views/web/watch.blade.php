@php($title = 'Смотреть аниме ' . $anime->name . '  на Kitaku | Китаку')

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
            <div class="row" style="position: relative; margin-top: -140px;">
                <iframe
                    src="{{ $release->link }}?translations=false&only_season=true"
                    width="100%"
                    height="600"
                    allow="autoplay; fullscreen; encrypted-media; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    </div>
</x-layouts::main>
