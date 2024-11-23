<x-layouts.app :title="config('app.name', 'Evaluation System')">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 relative">

        <div class="absolute inset-0 bg-cover bg-center opacity-50"
            style="background-image: url('/images/background.jpg'); background-size: cover; background-position: center;">
        </div>

        <div
            class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-indigo-950 shadow-md overflow-hidden sm:rounded-lg">
            <div class="w-60 h-60 flex justify-center items-center mx-auto">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500 bg-zinc-100" />
                </a>
            </div>
            {{ $slot }}
        </div>

    </div>
</x-layouts.app>
