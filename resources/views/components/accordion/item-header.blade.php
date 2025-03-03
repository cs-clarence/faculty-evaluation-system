<div
    {{ $attributes->merge(['class' => 'relative flex flex-row gap-4 border border-gray-200 rounded-lg bg-white p-4 hover:bg-gray-100 cursor-pointer items-center', 'x-on:click.self' => 'toggle()']) }}>
    {{ $slot }}
    <div class="w-4">
    </div>

    <div class="flex flex-col items-center justify-center absolute right-4 origin-center max-h-max transition-transform duration-500"
        x-bind:class="{ 'rotate-180': isOpen(key) }" x-on:click="toggle()">
        <x-icon>keyboard_arrow_down</x-icon>
    </div>
</div>
