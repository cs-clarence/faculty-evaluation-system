@props([
    'el' => 'p',
    'text' => '',
    'multiline' => false,
    'placeholder' => null,
    'empty' => null,
    'required' => false,
    'inputContainerClass' => '',
])

@php
    $randomId = 'editableText.' . uniqid();
    $attr = $attributes->merge([
        'class' => '',
    ]);

    $cssBase = 'flex w-full gap-2 outline-2 outline-blue-500 rounded-md p-2';

    $cssClasses = $multiline ? $cssBase . ' flex-col items-start' : $cssBase . ' flex-row items-center';

    $textClasses = $attr->get('class');
    $inputClasses = $textClasses . ' w-full outline-none border-none focus:ring-0 p-0 m-0';

    $data = [
        'edit' => false,
        'text' => $text,
        'savedText' => $text,
        'placeholder' => $placeholder,
        'empty' => $empty,
        'required' => $required,
    ];

    $jsonData = json_encode($data);
@endphp

<div {{ $attr->except('class')->merge(['class' => 'contents', 'x-data' => "editableText($jsonData)"]) }}
    wire:key="{{ rand() }}">
    <{{ $el }} class="{{ $textClasses }}" x-text="savedText.trim() ? savedText : empty" x-show="!edit"
        x-on:click="edit = true; $focus.focus($refs['{{ $randomId }}'])">
        </{{ $el }}>
        @isset($input)
            <div
                {{ $input->attributes->merge(['class' => 'flex flex-col gap-2', 'x-show' => 'edit', 'x-on:click.outside' => 'cancel($event)']) }}>
            @else
                <div class="flex flex-col gap-2" x-show="edit" x-on:click.outside="cancel($event)">
                @endisset
                <div class="{{ $cssClasses }}" x-bind:class="{ 'outline-red-500': error }">
                    @if ($multiline)
                        <textarea x-bind:placeholder="placeholder" x-bind:rows="rows" required x-ref="{{ $randomId }}"
                            id="{{ $randomId }}" class="{{ $inputClasses }}" title="edit text" x-model="text"></textarea>
                    @else
                        <input x-bind:placeholder="placeholder" required x-ref="{{ $randomId }}"
                            id="{{ $randomId }}" class="{{ $inputClasses }}" title="edit text" x-model="text"
                            x-on:keyup.enter="save($event, $dispatch)" />
                    @endif

                    <div class="flex flex-row gap-2">
                        <x-button variant="text" size="sm" color="neutral" type="button"
                            x-bind:disabled="unchanged" x-on:click="cancel($event)">Cancel</x-button>
                        <x-button variant="outlined" size="sm" color="neutral" type="button"
                            x-bind:disabled="error" x-on:click="save($event, $dispatch)">Save</x-button>
                    </div>
                </div>
                <x-error-text x-show="error">This field is required</x-error-text>
            </div>
        </div>
