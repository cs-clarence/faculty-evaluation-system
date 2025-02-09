@php
    $defaultActions = [
        'edit' => [
            'label' => 'Edit',
            'variant' => 'primary',
            'wire:click' => fn($data) => "edit({$data->id})",
        ],
        'archive' => [
            'label' => 'Archive',
            'variant' => 'warning',
            'wire:click' => fn($data) => "archive({$data->id})",
            'condition' => fn($data) => !$data->is_archived,
        ],
        'unarchive' => [
            'label' => 'Unarchive',
            'variant' => 'secondary',
            'wire:click' => fn($data) => "unarchive({$data->id})",
            'condition' => fn($data) => $data->is_archived,
        ],
        'delete' => [
            'label' => 'Delete',
            'variant' => 'danger',
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

    if (!function_exists('reorder')) {
        function reorder(array $array, array $keyOrder): array
        {
            $value = [];
            $existingKeys = array_keys($array);
            foreach ($keyOrder as $key) {
                if (array_key_exists($key, $array)) {
                    $value[$key] = $array[$key];
                }
            }

            $diff = array_diff($existingKeys, $keyOrder);

            foreach ($diff as $key) {
                if (array_key_exists($key, $array)) {
                    $value[$key] = $array[$key];
                }
            }

            return $value;
        }
    }

    $actions = $mergeDefaultActions ? deepMerge(reorder($defaultActions, array_keys($actions)), $actions) : $actions;
@endphp


@foreach ($actions as $action)
    @php
        $variant = $action['variant'] ?? 'default';
        $condition = $action['condition'] ?? fn($data) => true;
        $label = $action['label'];
        $wireClick = $action['wire:click'] ?? null;
        $wireConfirm = $action['wire:confirm'] ?? null;

        $cssClass = match ($variant) {
            'danger' => 'bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600',
            'warning' => 'bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600',
            'primary' => 'bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600',
            'secondary' => 'bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600',
            default => 'bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600',
        };
    @endphp

    @if ($condition($data))
        <button @isset($wireClick) wire:click='{{ $wireClick($data) }}' @endisset
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
@endforeach
