@props([
    'updates' => []
])

<div class="card rounded-0 shadow-none">
    <div class="list-group list-group-flush">
        @foreach($updates as $item)
            <a
                href="{{ route('watch', ['r' => $item->release_code]) }}"
                class="list-group-item list-group-item-action p-0"
            >
                <div class="row">
                    <div class="col-2" style="height: 100px;">
                        <img
                            src="{{ $item->poster_url }}"
                            alt="Bleach"
                            width="55"
                            height="80"
                            loading="lazy"
                            style="width: 100%; height: 100%; object-fit: cover;"
                        >
                    </div>
                    <div class="col-10">
                        <div class="py-3 pe-3">
                            <div class="fw-bold">{{ $item->anime_name }}</div>
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
