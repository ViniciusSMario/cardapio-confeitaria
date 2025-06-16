@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill">
            <ul class="pagination justify-content-center">
                {{-- Link para a página anterior --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled mx-1">
                        <span class="page-link rounded-pill bg-secondary text-white border-0 px-3">&laquo;</span>
                    </li>
                @else
                    <li class="page-item mx-1">
                        <a class="page-link rounded-pill bg-maroon text-white border-0 px-3"
                            href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                @endif

                {{-- Links das páginas --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled mx-1"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active mx-1">
                                    <span class="page-link rounded-pill bg-maroon text-white border-0 px-3">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item mx-1">
                                    <a class="page-link rounded-pill bg-light text-dark border-0 px-3"
                                        href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Link para a próxima página --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item mx-1">
                        <a class="page-link rounded-pill bg-maroon text-white border-0 px-3"
                            href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled mx-1">
                        <span class="page-link rounded-pill bg-secondary text-white border-0 px-3">&raquo;</span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
