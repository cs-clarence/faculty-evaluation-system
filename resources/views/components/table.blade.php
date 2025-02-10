@php
    use Illuminate\Pagination\{LengthAwarePaginator, CursorPaginator};
    if (!function_exists('isPaginated')) {
        function isPaginated($data)
        {
            return $data instanceof AbstractPaginator ||
                $data instanceof LengthAwarePaginator ||
                $data instanceof CursorPaginator;
        }
    }

    if (!function_exists('isCursorPaginated')) {
        function isCursorPaginated($data)
        {
            return $data instanceof CursorPaginator;
        }
    }

    $perPage = isset($paginate) ? (is_numeric($paginate) ? $paginate : $paginate['perPage']) : 15;

    $data = isset($paginate) && !isPaginated($data) ? $data->cursorPaginate($perPage) : $data;
@endphp


@if (isPaginated($data))
    <div class="mb-2 flex row">
        <div class="grow"></div>
        @if (isCursorPaginated($data))
            {{ $data->links('components.table.simple-paginate') }}
        @else
            {{ $data->links('components.table.paginate') }}
        @endif
    </div>
@endif

<table class="min-w-full bg-white rounded-lg overflow-hidden shadow-lg table table-fixed">
    <thead class="bg-gray-200 text-gray-600">
        <tr>
            @foreach ($columns as $column)
                <th class="py-3 px-4 text-left text-sm font-semibold">{{ $column['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody class="text-gray-700">
        @forelse($data as $d)
            <tr wire:key="{{ $getValue($key, $d) }}">
                @foreach ($columns as $column)
                    <td class="py-3 px-4 border-b border-current/20">
                        @if (is_callable($column['render']))
                            {{ $column['render']($d) }}
                        @elseif (str_starts_with($column['render'], 'blade:'))
                            @php
                                $slotName = str_replace('blade:', '', $column['render']);
                                $renderProps = $column['props'] ?? [];
                            @endphp
                            <x-dynamic-component :component="$slotName" :data="$d" :attributes="new Illuminate\View\ComponentAttributeBag($renderProps)" />
                        @else
                            {{ $getValue($column['render'], $d) }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) }}" class="py-3 px-4 text-center text-gray-500">
                    {{ $empty }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
