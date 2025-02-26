@php
    $baseClass =
        '[background-image:none] [&_button]:text-white [&_button]:opacity-100 rounded-md flex flex-row gap-2 p-4 max-w-80 min-h-max justify-between';
@endphp
@if (session('alert-danger'))
    @once
        @push('scripts')
            <script defer>
                Toastify({
                    duration: -1,
                    className: "{!! $baseClass !!} bg-red-500 text-white",
                    close: true,
                    ...{!! json_encode(session('alert-danger')) !!},
                }).showToast();
            </script>
        @endpush
    @endonce
@endif

@if (session('alert-success'))
    @once
        @push('scripts')
            <script defer>
                Toastify({
                    duration: 3000,
                    className: "{!! $baseClass !!} bg-green-500 text-white",
                    close: true,
                    ...{!! json_encode(session('alert-success')) !!},
                }).showToast();
            </script>
        @endpush
    @endonce
@endif

@if (session('alert-warning'))
    @once
        @push('scripts')
            <script defer>
                Toastify({
                    duration: -1,
                    className: "{!! $baseClass !!} bg-yellow-500 text-white",
                    close: true,
                    ...{!! json_encode(session('alert-warning')) !!},
                }).showToast();
            </script>
        @endpush
    @endonce
@endif

@if (session('alert-info'))
    @once
        @push('scripts')
            <script defer>
                Toastify({
                    duration: 3000,
                    className: "{!! $baseClass !!} bg-blue-500 text-white",
                    close: true,
                    ...{!! json_encode(session('alert-info')) !!},
                }).showToast();
            </script>
        @endpush
    @endonce
@endif
