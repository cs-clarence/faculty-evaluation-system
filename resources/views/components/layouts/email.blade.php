@props(['title' => 'SPCF-TES'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Page Title' }}</title>

    @isset($head)
        {{ $head }}
    @endisset
    @stack('styles')
</head>

<body>
    {{ $slot }}
</body>
