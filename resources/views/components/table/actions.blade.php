@php
    $defaultActions = [
        'edit' => [
            'order' => 1,
            'label' => 'Edit',
            'color' => 'primary',
            'wire:click' => fn($data) => "edit({$data->id})",
        ],
        'archive' => [
            'order' => 2,
            'label' => 'Archive',
            'color' => 'warning',
            'wire:click' => fn($data) => "archive({$data->id})",
            'condition' => fn($data) => !$data->is_archived,
        ],
        'unarchive' => [
            'order' => 3,
            'label' => 'Unarchive',
            'color' => 'secondary',
            'wire:click' => fn($data) => "unarchive({$data->id})",
            'condition' => fn($data) => $data->is_archived,
        ],
        'delete' => [
            'order' => 4,
            'label' => 'Delete',
            'color' => 'danger',
            'wire:confirm' => 'Are you sure you want to delete this record?',
            'wire:click' => fn($data) => "delete({$data->id})",
            'condition' => fn($data) => !$data->hasDependents(),
        ],
    ];
@endphp

@props(['data', 'actions' => $defaultActions, 'mergeDefaultActions' => true])

@php
    if (!function_exists('deepMerge')) {
        function deepMerge(array $array1, array $array2): array
        {
            foreach ($array2 as $key => $value) {
                if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                    $array1[$key] = deepMerge($array1[$key], $value);
                } elseif (is_int($key)) {
                    $array1[] = $value;
                } else {
                    $array1[$key] = $value;
                }
            }
            return $array1;
        }
    }

    $actions = $mergeDefaultActions ? deepMerge($defaultActions, $actions) : $actions;
    $actions = collect($actions)->sortBy(fn($x) => $x['order'] ?? PHP_INT_MAX);

    if (!function_exists('evalCondition')) {
        function evalCondition(callable|bool $condition, mixed $data): bool
        {
            if (is_callable($condition)) {
                return $condition($data);
            }

            return $condition;
        }
    }

@endphp


@foreach ($actions as $action)
    @php
        $color = $action['color'] ?? 'default';
        $condition = $action['condition'] ?? true;
        $label = $action['label'];
        $wireClick = $action['wire:click'] ?? null;
        $wireConfirm = $action['wire:confirm'] ?? null;
        $type = $action['type'] ?? 'button';
        $href = $action['href'] ?? null;

        $cssClass = match ($color) {
            'danger' => 'bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600',
            'warning' => 'bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600',
            'primary' => 'bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600',
            'secondary' => 'bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600',
            default => 'bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600',
        };
    @endphp

    @if (evalCondition($condition, $data))
        @if ($type === 'link')
            <button class="{{ $cssClass }}"
                @isset($href)
                    @if (is_callable($href))
                        x-on:click="Livewire.navigate('{{ $href($data) }}')"
                    @else
                        x-on:click="Livewire.navigate('{{ $href }}')"
                    @endif
                @endisset>
                {{ $label }}
            </button>
        @elseif ($type === 'button')
            <button
                @isset($wireClick)
                    @if (is_callable($wireClick))
                        wire:click='{{ $wireClick($data) }}'
                    @else
                        wire:click='{{ $wireClick }}'
                    @endif
                @endisset
                @isset($wireConfirm)
                    @if (is_callable($wireConfirm))
                        wire:confirm='{{ $wireConfirm($data) }}'
                    @else
                        wire:confirm='{{ $wireConfirm }}'
                    @endif
                @endisset
                class="{{ $cssClass }}">
                {{ $label }}
            </button>
        @endif
    @endif
@endforeach
