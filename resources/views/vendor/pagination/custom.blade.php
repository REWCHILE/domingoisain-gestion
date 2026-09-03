@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        
        <!-- Mobile & Desktop Pagination Controls -->
        <div class="flex-1 flex items-center justify-between">
            
            <!-- Previous Page Link -->
            <div>
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs font-semibold text-slate-600 cursor-not-allowed opacity-60">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        <span>Anterior</span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-xs font-bold text-sky-400 hover:text-white transition-colors shadow-sm">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        <span>Anterior</span>
                    </a>
                @endif
            </div>

            <!-- Page Counter Info -->
            <div class="hidden sm:block text-xs text-slate-400 font-medium">
                Página <span class="font-bold text-white">{{ $paginator->currentPage() }}</span> de <span class="font-bold text-white">{{ $paginator->lastPage() }}</span>
            </div>

            <!-- Next Page Link -->
            <div>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-xs font-bold text-sky-400 hover:text-white transition-colors shadow-sm">
                        <span>Siguiente</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 border border-slate-800 rounded-xl text-xs font-semibold text-slate-600 cursor-not-allowed opacity-60">
                        <span>Siguiente</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </span>
                @endif
            </div>

        </div>

    </nav>
@endif
