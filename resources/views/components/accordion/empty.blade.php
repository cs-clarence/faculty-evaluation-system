<div
    {{ $attributes->merge(['class' => 'text-xl text-gray-600 flex flex-row justify-center mb-2 border border-gray-200 rounded-lg bg-white p-4 items-center']) }}>
    <p>
        @isset($slot)
            {{ $slot }}
        @else
            No data available
        @endisset
    </p>
</div>
