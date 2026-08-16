<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @stack('head-meta')
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/css/scss/app.scss', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dela+Gothic+One&family=Potta+One&family=Reggae+One&family=Yuji+Boku&family=Yuji+Mai&display=swap" rel="stylesheet">
    @stack('head-style')
    @stack('head-script')
    @stack('head-ld+json')
</head>
