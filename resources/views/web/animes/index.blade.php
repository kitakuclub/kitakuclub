@section('title', 'Аниме')

<x-layouts::main>
    <div class="container mt-4">
        {{ Breadcrumbs::render('animes') }}
    </div>
    {{--<div class="page-header">
        <div class="container">
            <div class="page-title">Игры</div>
            <div class="text-secondary">{{ $provider->label() }}</div>
        </div>
    </div>--}}
    <div class="page-body">
        <div class="container">
            animes
        </div>
    </div>
</x-layouts::main>
