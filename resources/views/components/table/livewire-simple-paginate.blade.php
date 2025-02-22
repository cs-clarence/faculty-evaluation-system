@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between gap-0.5">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <x-button variant="outlined" color="neutral" disabled size="sm">
                        {!! __('pagination.previous') !!}
                    </x-button>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        <x-button type="button" dusk="previousPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()?->encode() }}" wire:click="setPage('{{$paginator->previousCursor()?->encode()}}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" variant="outlined" color="neutral" size="sm">
                                {!! __('pagination.previous') !!}
                        </x-button>
                    @else
                        <x-button
                            type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" variant="outlined" color="neutral" size="sm">
                                {!! __('pagination.previous') !!}
                        </x-button>
                    @endif
                @endif
            </span>


            <span>
                {{-- Next Page Link --}}
                @if ($paginator->onLastPage())
                    <x-button variant="outlined" color="neutral" disabled size="sm">
                        {!! __('pagination.next') !!}
                    </x-button>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        <x-button type="button" dusk="nextPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()?->encode() }}" wire:click="setPage('{{$paginator->nextCursor()?->encode()}}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" variant="outlined" color="neutral" size="sm">
                                {!! __('pagination.next') !!}
                        </x-button>
                    @else
                        <x-button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" variant="outlined" color="neutral" size="sm">
                                {!! __('pagination.next') !!}
                        </x-button>
                    @endif
                @endif
            </span>
        </nav>
    @endif
</div>
