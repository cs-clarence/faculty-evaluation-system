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

    if (!function_exists('execSelect')) {
        function execSelect($fn, $option)
        {
            if (is_string($option) || is_integer($option) || is_float($option) || is_bool($option)) {
                return $option;
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

<select {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg', 'id' => $key, 'name' => $key]) }}>
    @isset($placeholder)
        <option selected value="">{{ execSelect($label, $placeholder) }}</option>
    @endisset
    @forelse ($options as $option)
        <option value="{{ execSelect($value, $option) }}">{{ execSelect($label, $option) }}</option>
    @empty
        @isset($empty)
            <option disabled value="">{{ execSelect($label, $empty) }}</option>
        @endisset
    @endforelse
</select>
