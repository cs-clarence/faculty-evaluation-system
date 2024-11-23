@props(['title' => 'SPCF-TES'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <title>{{ $title ?? 'Page Title' }}</title>

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @livewireStyles
    @isset($head)
        {{ $head }}
    @endisset
</head>

<body class="min-h-dvh bg-gray-100">
    @isset($slot)
        {{ $slot }}
    @else
        Blank Page
    @endisset
</body>
@livewireScripts

</html>
