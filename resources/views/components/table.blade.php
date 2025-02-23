@props([
    'data',
    'columns',
    'paginate',
    'key' => 'id',
    'empty' => 'No records found',
    'search' => null,
    'tableId' => 'table',
])

@php
    use Illuminate\Pagination\{LengthAwarePaginator, CursorPaginator};
    if (!function_exists('isPaginated')) {
        function isPaginated($data)
        {
            return !is_array($data) &&
                ($data instanceof AbstractPaginator ||
                    $data instanceof LengthAwarePaginator ||
                    $data instanceof CursorPaginator);
        }
    }

    if (!function_exists('isCursorPaginated')) {
        function isCursorPaginated($data)
        {
            return !is_array($data) && $data instanceof CursorPaginator;
        }
    }
    if (!function_exists('walkAndGetValue')) {
        function walkAndGetValue(string $property, $data)
        {
            $path = preg_split('/\./', $property);
            $current = $data;

            foreach ($path as $p) {
                if (is_array($current)) {
                    $current = $current[$p];
                } else {
                    $current = $current->$p;
                }
            }

            return $current;
        }
    }

    if (!function_exists('getValue')) {
        function getValue(callable|string $property, $data)
        {
            if (is_callable($property)) {
                return $property($data);
            } elseif (is_array($data)) {
                return walkAndGetValue($property, $data);
            } else {
                return walkAndGetValue($property, $data);
            }
        }
    }

    $perPage = isset($paginate) ? (is_numeric($paginate) ? $paginate : $paginate['perPage']) : 15;

    $data = isset($paginate) && !isPaginated($data) ? $data->cursorPaginate($perPage) : $data;
@endphp


@if (!is_array($data))
    @if (isPaginated($data) || isset($actions))
        <div class="mb-2 flex flex-row">
            @isset($actions)
                <div class="flex flex-row gap-2">
                    {{ $actions }}
                </div>
            @endisset
            <div class="grow"></div>
            @if (isPaginated($data))
                @if (isCursorPaginated($data))
                    {{ $data->links('components.table.livewire-simple-paginate') }}
                @else
                    {{ $data->links('components.table.livewire-paginate') }}
                @endif
            @endif
        </div>
    @endif
@endif

<table class="min-w-full bg-white rounded-lg overflow-hidden shadow-lg table table-fixed">
    <thead class="bg-gray-200 text-gray-600" id="{{ $tableId }}">
        <tr>
            @foreach ($columns as $column)
                <th class="py-3 px-4 text-left text-sm font-semibold">{{ $column['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody class="text-gray-700">
        @php
            $queried = $data;

            if (is_object($queried) && method_exists($data, 'lazy')) {
                $queried = $data->lazy();
            }
        @endphp

        @forelse($queried as $d)
            <tr wire:key="{{ $tableId }}.{{ getValue($key, $d) }}">
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
                            {{ getValue($column['render'], $d) }}
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
