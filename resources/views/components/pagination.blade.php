@props(['paginator'])

@if ($paginator->hasPages())
    <div class="flex space-x-2 mt-2 mb-3">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-sm text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Previous
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="px-3 py-1 text-sm text-white bg-blue-600 border border-blue-600 rounded-lg">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">
                Next
            </a>
        @else
            <span class="px-3 py-1 text-sm text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                Next
            </span>
        @endif
    </div>
@endif
