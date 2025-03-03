@assets
    <style>
        .accordion-toggle::before {
            transition: transform 0.3s ease-in-out;
            transform: rotate3d(0, 0, 1, 0deg);
        }

        .accordion-toggle-open::before {
            transform: rotate3d(0, 0, 1, 180deg);
        }

        .accordion-body {
            @supports (interpolate: allow-keywords) {
                interpolate-size: allow-keywords;
            }

            transition: transform 0.3s ease-in-out,
            height 0.3s ease-in-out;
            transform: scaleY(0);
            transform-origin: top;
            height: 0px;
        }

        .accordion-body-open {
            transform: scaleY(1);
            height: max-content;
        }
    </style>
@endassets

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2', 'x-data' => 'accordion()']) }}>
    <div class="contents" x-on:open="open($event.detail.key)" x-on:toggle="toggle($event.detail.key)">
        {{ $slot }}
    </div>
</div>
