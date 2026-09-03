@extends('layouts.app')

@section('title', 'Importar Base de Datos Antigua (phpMyAdmin)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Page Title Header -->
    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="database" class="w-7 h-7 text-amber-400"></i>
                <span>Importación de Datos Antiguos</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Cargue el archivo .SQL de phpMyAdmin (tabla cotizacion) para conservar toda la historia</p>
        </div>
        <a href="{{ route('certificates.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-medium rounded-xl border border-slate-800 transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Volver</span>
        </a>
    </div>

    @if(session('error'))
        <div class="p-4 bg-rose-500/20 border border-rose-500/30 rounded-xl text-rose-300 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Import Instructions Card -->
    <div class="glass-panel p-6 rounded-2xl border border-slate-800 space-y-4">
        <h3 class="text-base font-bold text-white flex items-center gap-2">
            <i data-lucide="help-circle" class="w-5 h-5 text-sky-400"></i>
            <span>¿Cómo exportar desde phpMyAdmin?</span>
        </h3>
        <ol class="list-decimal list-inside text-xs text-slate-300 space-y-2 leading-relaxed font-sans">
            <li>Entre a su <strong>phpMyAdmin</strong> de cPanel / Hosting antiguo.</li>
            <li>Seleccione la base de datos <code class="bg-slate-900 px-1.5 py-0.5 rounded text-sky-300">gasfite8_gestion_instalgaschile</code>.</li>
            <li>Haga clic en la tabla <code class="bg-slate-900 px-1.5 py-0.5 rounded text-amber-300">cotizacion</code>.</li>
            <li>Vaya a la pestaña <strong>Exportar</strong> ➔ Formato: <strong>SQL</strong> ➔ Clic en <strong>Exportar</strong>.</li>
            <li>Suba el archivo <code class="bg-slate-900 px-1.5 py-0.5 rounded text-emerald-300">.sql</code> obtenido mediante el formulario inferior.</li>
        </ol>
    </div>

    <!-- Upload Form -->
    <form action="{{ route('certificates.import.process') }}" method="POST" enctype="multipart/form-data" class="glass-panel p-6 rounded-2xl border border-slate-800 space-y-6">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">
                Seleccionar archivo .SQL de phpMyAdmin
            </label>
            <div class="border-2 border-dashed border-slate-700 hover:border-sky-500 rounded-2xl p-8 text-center transition-all bg-slate-900/60 cursor-pointer relative"
                 x-data="{ filename: '' }">
                <input type="file" name="sql_file" accept=".sql,.txt" required 
                       @change="filename = $event.target.files[0]?.name || ''"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center mx-auto border border-sky-500/20">
                        <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-white block" x-text="filename ? filename : 'Arrastre o seleccione el archivo .SQL aquí'"></span>
                        <span class="text-xs text-slate-400 block mt-1">Soporta respaldos SQL de phpMyAdmin o MariaDB</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-800 flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold text-sm rounded-xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2">
                <i data-lucide="file-up" class="w-5 h-5"></i>
                <span>Iniciar Importación de Datos</span>
            </button>
        </div>
    </form>

    <!-- Alternative Terminal Command -->
    <div class="glass-panel p-4 rounded-xl border border-slate-800 flex items-center justify-between text-xs">
        <span class="text-slate-400">Alternativa por consola:</span>
        <code class="bg-slate-900 px-3 py-1.5 rounded-lg text-emerald-400 font-mono">php artisan import:legacy-sql cotizacion.sql</code>
    </div>

</div>
@endsection
