@php
    $defaultSize = 'sm';
    $defaultActions = [
        'edit' => [
            'order' => 1,
            'label' => 'Edit',
            'color' => 'secondary',
            'size' => $defaultSize,
            'wire:click' => fn($data) => "edit({$data->id})",
        ],
        'archive' => [
            'order' => 2,
            'label' => 'Archive',
            'variant' => 'outlined',
            'color' => 'warning',
            'size' => $defaultSize,
            'wire:click' => fn($data) => "archive({$data->id})",
            'condition' => fn($data) => !$data->is_archived,
        ],
        'unarchive' => [
            'order' => 3,
            'label' => 'Unarchive',
            'variant' => 'outlined',
            'color' => 'secondary',
            'size' => $defaultSize,
            'wire:click' => fn($data) => "unarchive({$data->id})",
            'condition' => fn($data) => $data->is_archived,
        ],
        'delete' => [
            'order' => 4,
            'label' => 'Delete',
            'variant' => 'outlined',
            'color' => 'danger',
            'size' => $defaultSize,
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


<div class="flex row gap-0.5">
    @foreach ($actions as $action)
        @php
            $color = $action['color'] ?? 'default';
            $variant = $action['variant'] ?? 'default';
            $condition = $action['condition'] ?? true;
            $label = $action['label'];
            $wireClick = $action['wire:click'] ?? null;
            $wireConfirm = $action['wire:confirm'] ?? null;
            $type = $action['type'] ?? 'button';
            $href = $action['href'] ?? null;
            $size = $action['size'] ?? $defaultSize;

            if (!function_exists('evalProp')) {
                function evalProp($maybeCallable, ...$args)
                {
                    if (is_callable($maybeCallable)) {
                        return $maybeCallable(...$args);
                    } else {
                        return $maybeCallable;
                    }
                }
            }
            $attrWireClick = null;
            if (isset($wireClick)) {
                $attrWireClick = evalProp($wireClick, $data);
            }
            $attrWireConfirm = null;
            if (isset($wireConfirm)) {
                $attrWireConfirm = evalProp($wireConfirm, $data);
            }
            $attrHref = null;
            if (isset($href)) {
                $attrHref = evalProp($href, $data);
            }
        @endphp

        @if (evalCondition($condition, $data))
            @if ($type === 'link')
                <x-button :$color :$size :$variant x-on:click="Livewire.navigate('{{ $attrHref }}')">
                    {{ $label }}
                </x-button>
            @elseif ($type === 'button')
                <x-button :$color :$size :$variant :wire:click="$attrWireClick" :wire:confirm="$attrWireConfirm">
                    {{ $label }}
                </x-button>
            @endif
        @endif
    @endforeach
</div>
