<div>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="flex flex-wrap gap-4 items-center">
        <!-- Statistic Card -->
        @foreach ($statistics as $stat)
            @php
                $condition = $stat['condition'] ?? true;
            @endphp
            @if (is_callable($condition) ? $condition() : $condition)
                <a class="bg-white shadow-md rounded-md p-6 flex-1 flex flex-col items-center text-center min-w-64 hover:bg-gray-100"
                    href="{{ $stat['href'] }}" wire:navigate>
                    <div class="text-gray-500 text-sm uppercase">{{ $stat['label'] }}</div>
                    <div class="text-3xl font-semibold mt-2">{{ $stat['value'] }}</div>
                </a>
            @endif
        @endforeach
    </div>
</div>
