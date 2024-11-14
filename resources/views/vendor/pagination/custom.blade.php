@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        <div class="d-flex justify-content-center">
            @if ($paginator->onFirstPage())
                <button class="btn btn-falcon-default btn-sm me-2" type="button" disabled="disabled"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Prev">
                    <span class="fas fa-chevron-left"></span>
                </button>
            @else
                <a class="btn btn-falcon-default btn-sm me-2" href="{{ $paginator->previousPageUrl() }}"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Prev">
                    <span class="fas fa-chevron-left"></span>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn btn-sm btn-falcon-default text-primary me-2"
                                aria-current="page">{{ $page }}</span>
                        @else
                            <a class="btn btn-sm btn-falcon-default me-2"
                                href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="btn btn-falcon-default btn-sm me-2" href="{{ $paginator->nextPageUrl() }}"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Next">
                    <span class="fas fa-chevron-right"></span>
                </a>
            @else
                <button class="btn btn-falcon-default btn-sm me-2" type="button" disabled="disabled"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Next">
                    <span class="fas fa-chevron-right"></span>
                </button>
            @endif
        </div>
    </div>
@endif
