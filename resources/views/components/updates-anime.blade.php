@props([
    'updates' => []
])

<div class="card rounded-0 shadow-none">
    <div class="list-group list-group-flush">
        @foreach($updates as $item)
            <a
                href="{{ route('animes.show', [$item->anime_id, $item->anime_slug]) }}"
                class="list-group-item list-group-item-action p-0"
            >
                <div class="row">
                    <div class="col-auto">
                        <img
                            src="{{ $item->poster_url }}"
                            alt="Bleach"
                            width="55"
                            height="80"
                            loading="lazy"
                        >
                    </div>
                    <div class="col">
                        <div class="py-3">
                            <div><b>{{ $item->anime_name }}</b></div>
                            <div class="text-secondary">
                                — Добавлена {{ $item->episode_number }}-я серия:
                                Озвучка {{ $item->funteam_name }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
