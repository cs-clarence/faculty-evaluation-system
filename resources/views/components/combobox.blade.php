@php
    if (!isset($defaultGetLabel)) {
        $defaultGetLabel = function ($option) {
            if (is_array($option)) {
                return $option['label'];
            }

            if (is_object($option)) {
                return $option->label;
            }

            throw new Exception('Not supported object on defaultGetLabel', 1);
        };
    }

    if (!isset($defaultGetValue)) {
        $defaultGetValue = function ($option) {
            if (is_array($option)) {
                return $option['value'];
            }

            if (is_object($option)) {
                return $option->value;
            }

            throw new Exception('Not supported object on defaultGetValue', 1);
        };
    }

    if (!function_exists('walkPath')) {
        function walkPath($data, $path)
        {
            $paths = preg_split('/\./', $path);
            $currentValue = $data;

            foreach ($paths as $path) {
                if (is_array($currentValue)) {
                    $currentValue = $currentValue[$path];
                } elseif (is_object($data)) {
                    $currentValue = $currentValue->$path;
                }
            }

            return $currentValue;
        }
    }

    if (!function_exists('execSelect')) {
        function execSelect($fn, $option)
        {
            if (is_string($option) || is_integer($option) || is_float($option) || is_bool($option)) {
                return $option;
            }

            if (is_string($fn)) {
                return walkPath($option, $fn);
            }

            return $fn($option);
        }
    }
@endphp


@props([
    'options' => [],
    'label' => $defaultGetLabel,
    'value' => $defaultGetValue,
    'key' => null,
    'empty' => null,
    'placeholder' => null,
])

@php
    $base = [
        'class' =>
            'w-full px-3 py-2 border-2 inset-ring-1 inset-ring-gray-400 border-blue-500/0 focus:border-blue-500 focus:inset-ring-0 focus:ring-0 rounded-lg disabled:opacity-50',
        'id' => $key,
        'name' => $key,
        'x-on:clear-inputs.window' => "\$el.value = ''",
        'x-init' => 'new TomSelect($el)',
    ];

    if (isset($key)) {
        $base['wire:key'] = $key . '-' . rand(0, 1000);
    }
@endphp

<select {{ $attributes->merge($base) }}>
    @if (isset($placeholder))
        <option selected value="">{{ execSelect($label, $placeholder) }}</option>
    @endif
    @forelse ($options as $option)
        <option value="{{ execSelect($value, $option) }}">{{ execSelect($label, $option) }}</option>
    @empty
        @isset($empty)
            <option disabled value="">{{ execSelect($label, $empty) }}</option>
        @endisset
    @endforelse
</select>
