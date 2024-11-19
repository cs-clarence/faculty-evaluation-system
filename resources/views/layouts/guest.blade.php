<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Evaluation System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 relative">

        <div class="absolute inset-0 bg-cover bg-center opacity-50" style="background-image: url('/images/background.jpg'); background-size: cover; background-position: center;"></div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-indigo-950 shadow-md overflow-hidden sm:rounded-lg">
                <div class="w-60 h-60 flex justify-center items-center mx-auto">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500 bg-zinc-100" />
                    </a>
                </div>
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>
