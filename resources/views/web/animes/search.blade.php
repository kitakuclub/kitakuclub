@php($title = 'Поиск аниме')
@php($description = 'Найдите своё следующее увлечение')

@section('title', config('app.name') . ' — Аниме поиск')

<x-layouts::main>
    <div class="container mt-4">
        {{ Breadcrumbs::render('animes.search') }}
    </div>
    <x-page-header :$title :$description />
    <div class="page-body">
        <div class="container">
            <x-cols-anime :$animes_list />
            <div class="col-lg-12 mt-5">
                {{ $animes_list->onEachSide(0)->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</x-layouts::main>
