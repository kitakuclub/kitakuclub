<!DOCTYPE html>
<html lang="{{ $locale = str_replace('_', '-', app()->getLocale()) }}">
@include('head')
<body data-bs-theme="light" data-user="{{ json_encode(['id' => auth()->id()]) }}">
<div class="page">
    @include('header')
    <div class="page-wrapper">
        {{ $slot }}
        @include('footer')
    </div>
</div>
@stack('body-script')
</body>
</html>
