<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('liveblog.site.title', 'Blog') }}</title>
    <meta name="description" content="{{ $description ?? config('liveblog.site.description', '') }}">
    <meta name="keywords" content="{{ $keywords ?? config('liveblog.site.keywords', '') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-white">

    {{ $slot }}

    @livewireScripts
</body>
</html>