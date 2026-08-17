@props([
    'items' => []
])

<div class="card rounded-0 shadow-none border-0">
    <div class="list-group list-group-horizontal list-group-flush row">
        @foreach($items as $release)
            <div class="col-6 p-0">
            <a
                href="{{ route('watch', ['r' => $release->release_code]) }}"
                class="list-group-item list-group-item-action p-0"
            >
                <div class="row">
                    <div class="col-2" style="height: 100px;">
                        <img
                            src="{{ $release->poster_url }}"
                            alt="Bleach"
                            width="55"
                            height="80"
                            loading="lazy"
                            style="width: 100%; height: 100%; object-fit: cover;"
                        >
                    </div>
                    <div class="col-10">
                        <div class="py-3 pe-3">
                            <div class="fw-bold">{{ $release->anime_name }}</div>
                            <div class="text-secondary">
                                — Озвучка {{ $release->funteam_name }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            </div>
        @endforeach
    </div>
</div>
