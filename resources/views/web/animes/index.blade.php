@section('title', config('app.name') . ' — Аниме')

<x-layouts::main>
    <div class="container mt-4">
        {{ Breadcrumbs::render('animes') }}
    </div>
    <div class="page-body">
        <div class="container">
            animes
        </div>
    </div>
</x-layouts::main>
