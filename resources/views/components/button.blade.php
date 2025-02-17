@props(['color' => 'primary', 'variant' => 'filled', 'size' => 'md', 'el' => 'button', 'icon' => false])

@php
    $common =
        'flex items-center justify-center font-bold items-center rounded-md disabled:pointer-events-none cursor-pointer disabled:opacity-50 transition-colors gap-1';

    switch ($size) {
        case 'xs':
            $common .= !$icon ? ' px-2 min-w-max min-h-7 text-xs' : ' min-w-7 min-h-7 text-xs';
            break;
        case 'sm':
            $common .= !$icon ? ' px-3 min-w-max min-h-9 text-sm' : ' min-w-9 min-h-9 text-sm';
            break;
        case 'lg':
            $common .= !$icon ? ' px-6 min-w-max min-h-12 text-md' : ' min-w-12 min-h-12 text-md';
            break;
        case 'md':
        default:
            $common .= !$icon ? ' px-4 min-w-max min-h-10 text-lg' : ' min-w-10 min-h-10 text-lg';
            break;
    }

    // by default
    $cssClass = match ($color) {
        'danger' => 'bg-red-500 text-white hover:bg-red-600',
        'warning' => 'bg-yellow-500 text-white hover:bg-yellow-600',
        'secondary' => 'bg-green-500 text-white hover:bg-green-600',
        'primary' => 'bg-blue-500 text-white hover:bg-blue-600',
        'neutral' => 'bg-gray-500 text-white hover:bg-gray-600',
        default => 'bg-blue-500 text-white hover:bg-blue-600',
    };

    if ($variant === 'outlined') {
        $common .= ' border hover:text-white';
        $cssClass = match ($color) {
            'danger' => 'text-red-500 border-red-400 hover:bg-red-600',
            'warning' => 'text-yellow-500 border-yellow-400 hover:bg-yellow-600',
            'secondary' => 'text-green-500 border-green-400 hover:bg-green-600',
            'primary' => 'text-blue-500 border-blue-400 hover:bg-blue-600',
            'neutral' => 'text-gray-500 border-gray-400 hover:bg-gray-600',
            default => 'text-blue-500 border-blue-400 hover:bg-blue-600',
        };
    }

    if ($variant === 'text') {
        $common .= ' hover:bg-black/10';
        $cssClass = match ($color) {
            'danger' => 'text-red-500 hover:text-red-600',
            'warning' => 'text-yellow-500 hover:text-yellow-600',
            'secondary' => 'text-green-500 hover:text-green-600',
            'primary' => 'text-blue-500 hover:text-blue-600',
            'neutral' => 'text-gray-500 hover:text-gray-600',
            default => 'text-blue-500 hover:text-blue-600',
        };
    }
@endphp

<{{ $el }}
    {{ $attributes->filter(fn($value) => isset($value) && $value != null)->merge(['class' => $cssClass . ' ' . $common]) }}>
    {{ $slot }}
    </{{ $el }}>
