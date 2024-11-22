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
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center">
            <div class="flex flex-1 justify-between sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-400 rounded-md border border-gray-700 cursor-default bg-gray-800/40">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md border border-gray-700 bg-gray-800/40 hover:bg-gray-700/40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" class="inline-flex relative items-center px-4 py-2 ml-3 text-sm font-medium text-gray-300 rounded-md border border-gray-700 bg-gray-800/40 hover:bg-gray-700/40 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="inline-flex relative items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 rounded-md border border-gray-700 cursor-default bg-gray-800/40">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-400">
                        {!! __('Showing') !!}
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>

                <div>
                    <span class="inline-flex relative z-0 rounded-md shadow-sm">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span class="inline-flex relative items-center px-2 py-2 text-sm font-medium text-gray-400 rounded-l-md border border-gray-700 cursor-default bg-gray-800/40" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" class="inline-flex relative items-center px-2 py-2 text-sm font-medium text-gray-300 rounded-l-md border border-gray-700 bg-gray-800/40 hover:bg-gray-700/40 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="{{ __('pagination.previous') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="inline-flex relative items-center px-4 py-2 -ml-px text-sm font-medium text-gray-400 border border-gray-700 cursor-default bg-gray-800/40">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="inline-flex relative items-center px-4 py-2 -ml-px text-sm font-medium text-blue-400 border border-gray-700 cursor-default bg-gray-700/40">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="inline-flex relative items-center px-4 py-2 -ml-px text-sm font-medium text-gray-300 border border-gray-700 bg-gray-800/40 hover:bg-gray-700/40 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" class="inline-flex relative items-center px-2 py-2 -ml-px text-sm font-medium text-gray-300 rounded-r-md border border-gray-700 bg-gray-800/40 hover:bg-gray-700/40 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="{{ __('pagination.next') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span class="inline-flex relative items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 rounded-r-md border border-gray-700 cursor-default bg-gray-800/40">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
