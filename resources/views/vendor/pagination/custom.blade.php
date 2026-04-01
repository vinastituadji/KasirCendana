@if ($paginator->hasPages())
<nav style="display:flex; align-items:center; gap:4px; flex-wrap:wrap;">

    {{-- Tombol Previous --}}
    @if ($paginator->onFirstPage())
        <span style="
            display:inline-flex; align-items:center; justify-content:center;
            width:30px; height:30px; border-radius:6px;
            background:var(--sand-100); color:var(--sand-300);
            cursor:not-allowed; font-size:13px; line-height:1;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="
            display:inline-flex; align-items:center; justify-content:center;
            width:30px; height:30px; border-radius:6px;
            background:var(--sand-100); color:var(--gray-600);
            text-decoration:none; font-size:13px; line-height:1;
            transition:all .15s ease;
        " onmouseover="this.style.background='var(--teal-500)';this.style.color='white';"
           onmouseout="this.style.background='var(--sand-100)';this.style.color='var(--gray-600)';">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </a>
    @endif

    {{-- Nomor Halaman --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="
                display:inline-flex; align-items:center; justify-content:center;
                width:30px; height:30px; border-radius:6px;
                background:transparent; color:var(--gray-400);
                font-size:13px; font-weight:600;
            ">...</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="
                        display:inline-flex; align-items:center; justify-content:center;
                        width:30px; height:30px; border-radius:6px;
                        background:var(--teal-500); color:white;
                        font-size:13px; font-weight:700; line-height:1;
                    ">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="
                        display:inline-flex; align-items:center; justify-content:center;
                        width:30px; height:30px; border-radius:6px;
                        background:var(--sand-100); color:var(--gray-600);
                        text-decoration:none; font-size:13px; font-weight:500; line-height:1;
                        transition:all .15s ease;
                    " onmouseover="this.style.background='var(--teal-500)';this.style.color='white';"
                       onmouseout="this.style.background='var(--sand-100)';this.style.color='var(--gray-600)';">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Tombol Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="
            display:inline-flex; align-items:center; justify-content:center;
            width:30px; height:30px; border-radius:6px;
            background:var(--sand-100); color:var(--gray-600);
            text-decoration:none; font-size:13px; line-height:1;
            transition:all .15s ease;
        " onmouseover="this.style.background='var(--teal-500)';this.style.color='white';"
           onmouseout="this.style.background='var(--sand-100)';this.style.color='var(--gray-600)';">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </a>
    @else
        <span style="
            display:inline-flex; align-items:center; justify-content:center;
            width:30px; height:30px; border-radius:6px;
            background:var(--sand-100); color:var(--sand-300);
            cursor:not-allowed; font-size:13px; line-height:1;
        ">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </span>
    @endif

    {{-- Info halaman --}}
    <span style="
        margin-left:8px; font-size:12px; color:var(--gray-400); white-space:nowrap;
    ">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }}</span>

</nav>
@endif
