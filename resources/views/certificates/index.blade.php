@extends('layouts.app')

@section('title', 'Gesti&oacute;n de Certificados de Servicio')

@section('content')
<div class="space-y-6"
     x-data="{
        search: '{{ request('search') }}',
        status: '{{ request('status') }}',
        document_type: '{{ request('document_type') }}',
        loading: false,

        async fetchResults() {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    search: this.search,
                    status: this.status,
                    document_type: this.document_type,
                    ajax: '1'
                });
                const res = await fetch(`{{ route('certificates.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                
                const tableContainer = document.getElementById('table-container');
                if (tableContainer) {
                    tableContainer.innerHTML = data.tableHtml;
                }
                
                if (document.getElementById('metric-total')) {
                    document.getElementById('metric-total').innerText = data.totalCertificates;
                }
                if (document.getElementById('metric-neto')) {
                    document.getElementById('metric-neto').innerText = data.formattedNeto;
                }
                if (document.getElementById('metric-sindoc')) {
                    document.getElementById('metric-sindoc').innerText = data.totalSinDocCount;
                }
                if (document.getElementById('metric-facturas')) {
                    document.getElementById('metric-facturas').innerText = data.totalFacturasCount;
                }

                if (window.lucide) { lucide.createIcons(); }
            } catch (err) {
                console.error(err);
            } finally {
                this.loading = false;
            }
        },

        clearFilters() {
            this.search = '';
            this.status = '';
            this.document_type = '';
            this.fetchResults();
        }
     }">

    <!-- 1. Top Header Bar (Animated) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in-up">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center border border-sky-200">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                </div>
                <span>Gesti&oacute;n de Certificados de Servicio</span>
            </h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1.5 font-medium pl-0.5">
                Servicio Técnico Certificado SEC &mdash; <span class="text-slate-800 font-semibold">Domingo Isa&iacute;n Plaza Caama&ntilde;o</span>
            </p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('certificates.import.form') }}" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-200 shadow-xs hover:shadow-sm transition-all">
                    <i data-lucide="database" class="w-4 h-4 text-amber-600"></i>
                    <span>Importar BD Antigua</span>
                </a>
            @endif
            <a href="{{ route('certificates.create') }}" 
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-md shadow-sky-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Emitir Nuevo Certificado</span>
            </a>
        </div>
    </div>

    <!-- 2. Metrics Cards Grid (Animated Stagger) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Certificados -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-sky-300 transition-all duration-300 flex items-center justify-between animate-fade-in-up stagger-1">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Certificados</p>
                <h3 id="metric-total" class="text-2xl md:text-3xl font-black text-slate-900 mt-1 tracking-tight">{{ number_format($totalCertificates) }}</h3>
                <p class="text-xs text-sky-600 mt-1 font-semibold flex items-center gap-1">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-sky-500"></span> {{ $thisMonthCount }} este mes
                </p>
            </div>
            <div class="p-3.5 rounded-2xl bg-sky-50 text-sky-600 border border-sky-100 shadow-xs">
                <i data-lucide="files" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Monto Neto Total -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 flex items-center justify-between animate-fade-in-up stagger-2">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Monto Neto Total</p>
                <h3 id="metric-neto" class="text-2xl md:text-3xl font-black text-emerald-600 mt-1 tracking-tight">${{ number_format($totalNetoAmount, 0, ',', '.') }}</h3>
                <p class="text-xs text-emerald-700 mt-1 font-semibold flex items-center gap-1">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Neto acumulado
                </p>
            </div>
            <div class="p-3.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-xs">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Sin Doc Tributario -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-amber-300 transition-all duration-300 flex items-center justify-between animate-fade-in-up stagger-3">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sin Doc Tributario</p>
                <h3 id="metric-sindoc" class="text-2xl md:text-3xl font-black text-amber-600 mt-1 tracking-tight">{{ number_format($totalSinDocCount) }}</h3>
                <p class="text-xs text-amber-700 mt-1 font-semibold flex items-center gap-1">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span> Neto directo cliente
                </p>
            </div>
            <div class="p-3.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-xs">
                <i data-lucide="file-minus" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Con Factura -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-purple-300 transition-all duration-300 flex items-center justify-between animate-fade-in-up stagger-4">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Con Factura (+19%)</p>
                <h3 id="metric-facturas" class="text-2xl md:text-3xl font-black text-purple-600 mt-1 tracking-tight">{{ number_format($totalFacturasCount) }}</h3>
                <p class="text-xs text-purple-700 mt-1 font-semibold flex items-center gap-1">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-purple-500"></span> Inmobiliarias/Empresas
                </p>
            </div>
            <div class="p-3.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-xs">
                <i data-lucide="receipt" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    <!-- 3. Search & Filter Bar (White / Light Design) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs animate-fade-in-up stagger-5">
        <form @submit.prevent="fetchResults()" class="flex flex-col md:flex-row gap-3">
            
            <!-- Live Search Input -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <template x-if="!loading">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </template>
                    <template x-if="loading">
                        <svg class="animate-spin h-4 w-4 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                </div>

                <input type="text" name="search" x-model="search" @input.debounce.250ms="fetchResults()"
                       placeholder="Filtro en tiempo real: escriba calle, número, comuna, folio o cliente..." 
                       class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition-all font-medium">
                
                <template x-if="search">
                    <button type="button" @click="search = ''; fetchResults()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </template>
            </div>

            <!-- Document Type Dropdown Filter -->
            <div class="w-full md:w-56">
                <select name="document_type" x-model="document_type" @change="fetchResults()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-semibold text-sm focus:outline-none focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition-all">
                    <option value="">Todos los Documentos</option>
                    <option value="certificado">Certificados SEC</option>
                    <option value="cotizacion">Cotizaciones</option>
                </select>
            </div>

            <!-- Status Dropdown Filter -->
            <div class="w-full md:w-48">
                <select name="status" x-model="status" @change="fetchResults()" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 font-semibold text-sm focus:outline-none focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition-all">
                    <option value="">Todos los Estados</option>
                    <option value="emitido">Emitido</option>
                    <option value="completado">Completado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="anulado">Anulado</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>Buscar</span>
            </button>
            
            <button type="button" @click="clearFilters()" x-show="search || status || document_type" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-colors flex items-center justify-center">
                Limpiar
            </button>
        </form>
    </div>

    <!-- 4. Certificates Table Container (White Theme, Animated) -->
    <div id="table-container" class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs transition-opacity duration-200 animate-fade-in-up stagger-6" :class="{ 'opacity-60': loading }">
        @include('certificates.partials.table', ['certificates' => $certificates])
    </div>

</div>

<script>
    // AJAX Pagination click handler
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#table-container nav a');
        if (link) {
            e.preventDefault();
            const url = new URL(link.href);
            const searchInput = document.querySelector('[name=search]');
            const statusSelect = document.querySelector('[name=status]');
            if (searchInput) url.searchParams.set('search', searchInput.value);
            if (statusSelect) url.searchParams.set('status', statusSelect.value);
            url.searchParams.set('ajax', '1');

            const tableContainer = document.getElementById('table-container');
            if (tableContainer) tableContainer.classList.add('opacity-60');

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.json())
                .then(data => {
                    if (tableContainer) {
                        tableContainer.innerHTML = data.tableHtml;
                        tableContainer.classList.remove('opacity-60');
                    }
                    if (window.lucide) { lucide.createIcons(); }
                })
                .catch(err => console.error(err));
        }
    });
</script>
@endsection