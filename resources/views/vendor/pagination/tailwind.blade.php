@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-1" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm text-gray-300 rounded-lg">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">←</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-gray-400">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">→</a>
        @else
            <span class="px-3 py-2 text-sm text-gray-300 rounded-lg">→</span>
        @endif
    </nav>
@endif
