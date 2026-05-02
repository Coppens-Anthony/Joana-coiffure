@if ($paginator->hasPages())
    <nav role="navigation" class="ml-auto" aria-label="{{ __('Pagination Navigation') }}">
        <h4 class="sr-only">{{ __('Pagination Navigation') }}</h4>
        <div class="flex gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium opacity-50 cursor-not-allowed rounded-xl">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a wire:navigate href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium  rounded-xl hover:bg-primary-opacity transition-colors">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a wire:navigate href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl hover:bg-primary-opacity transition-colors">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium opacity-50 cursor-not-allowed rounded-xl">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex sm:gap-2 sm:items-center sm:justify-center">
            <div class="flex gap-2">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium opacity-50 cursor-not-allowed rounded-lg">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <a wire:navigate href="{{ $paginator->previousPageUrl() }}" rel="prev"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg hover:bg-primary-opacity transition-colors">
                        {!! __('pagination.previous') !!}
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center px-4 py-2 text-sm font-medium">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-primary text-black rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a wire:navigate href="{{ $url }}"
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-primary-opacity transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a wire:navigate href="{{ $paginator->nextPageUrl() }}" rel="next"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg hover:bg-primary-opacity transition-colors">
                        {!! __('pagination.next') !!}
                    </a>
                @else
                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium opacity-50 cursor-not-allowed rounded-lg">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
