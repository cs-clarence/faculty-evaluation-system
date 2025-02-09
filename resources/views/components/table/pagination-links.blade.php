@if ($paginator->hasPages())
    @php($buttonClasses = 'border-1 border-blue-600 text-blue-600 px-4 py-2 first:rounded-l-md last:rounded-r-md not-disabled:hover:bg-blue-600 cursor-pointer disabled:cursor-not-allowed not-disabled:hover:text-white flex disabled:opacity-50')
    <nav>
        <ul class="flex col">
            {{-- Previous Page Link --}}
            <button x-on:click="Livewire.navigate('{{ $paginator->previousPageUrl() }}')" rel="prev"
                class="{{ $buttonClasses }}" @disabled($paginator->onFirstPage()) aria-label="@lang('pagination.previous')">
                &lsaquo;
            </button>

            {{-- Pagination Elements --}}
            @isset($elements)
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="{{ $buttonClasses }}">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $buttonClasses }}">{{ $page }}</span>
                            @else
                                <button x-on:click="Livewire.navigate('{{ $url }}')" class="{{ $buttonClasses }}">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endisset

            {{-- Next Page Link --}}
            <button x-on:click="Livewire.navigate('{{ $paginator->nextPageUrl() }}')" rel="prev"
                class="{{ $buttonClasses }}" @disabled(!$paginator->hasMorePages()) aria-label="@lang('pagination.next')">
                &rsaquo;
            </button>
        </ul>
    </nav>
@endif
