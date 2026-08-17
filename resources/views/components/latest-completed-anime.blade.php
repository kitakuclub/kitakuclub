@props([
    'items' => []
])

<div class="card rounded-0 shadow-none">
    <div class="list-group list-group-flush">
        @foreach($items as $anime)
            <a
                href="{{ route('animes.show', [$anime, $anime->slug]) }}"
                class="list-group-item list-group-item-action p-0"
            >
                <div class="row">
                    <div class="col-2" style="height: 100px;">
                        <img
                            src="{{ $anime->getFirstMediaUrl('poster') }}"
                            alt="Bleach"
                            width="55"
                            height="80"
                            loading="lazy"
                            style="width: 100%; height: 100%; object-fit: cover;"
                        >
                    </div>
                    <div class="col-10">
                        <div class="py-3 pe-3">
                            <div class="fw-bold">{{ $anime->name }}</div>
                            <div class="text-secondary">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">{{ $anime->kind->label() }}</li>
                                    <li class="breadcrumb-item">{{ $anime->released_at?->year }}</li>
                                    <li class="breadcrumb-item">{{ $anime->episodes_total }} эпизодов</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
