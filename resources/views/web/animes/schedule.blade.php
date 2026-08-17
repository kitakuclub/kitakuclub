@php($title = 'Расписание аниме')
@php($description = 'Календарь выхода аниме')

@section('title', config('app.name') . ' — Аниме расписание')

<x-layouts::main>
    <div class="container mt-4">
        {{ Breadcrumbs::render('animes.schedule') }}
    </div>
    <x-page-header :$title :$description />
    <div class="page-body">
        <div class="container">
            schedule
        </div>
    </div>
</x-layouts::main>
