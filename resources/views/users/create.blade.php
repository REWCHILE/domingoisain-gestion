@extends('layouts.app')

@section('title', 'Crear Usuario Técnico')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="user-plus" class="w-7 h-7 text-amber-400"></i>
                <span>Crear Nuevo Usuario / Técnico</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Ingrese los datos para otorgar acceso al sistema</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-slate-900 text-slate-300 text-sm font-medium rounded-xl border border-slate-800">
            Volver
        </a>
    </div>

    <form action="{{ route('users.store') }}" method="POST" class="glass-panel p-6 rounded-2xl border border-slate-800 space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nombre Completo <span class="text-rose-400">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej: Carlos Muñoz"
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Correo Electrónico <span class="text-rose-400">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="carlos@instalgaschile.cl"
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Contraseña Inicial <span class="text-rose-400">*</span></label>
            <div class="relative">
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="w-full pl-4 pr-11 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
                <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors focus:outline-none" title="Mostrar/Ocultar contraseña">
                    <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div>
            <label for="role" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Rol de Usuario <span class="text-rose-400">*</span></label>
            <select id="role" name="role" required class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
                <option value="technician" selected>Técnico de Terreno (Emisión de Certificados)</option>
                <option value="admin">Administrador (Control Total - Domingo)</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="rut" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">RUT Gasfiter</label>
                <input type="text" id="rut" name="rut" value="{{ old('rut') }}" placeholder="18.456.789-0"
                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none">
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Teléfono</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+569 9123 4567"
                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none">
            </div>
        </div>

        <div>
            <label for="sec_code" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Acreditación SEC / Clase</label>
            <input type="text" id="sec_code" name="sec_code" value="{{ old('sec_code', 'Clase 3') }}" placeholder="Ej: Gasfiter Certificado Autorizado SEC Clase 3"
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none">
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-slate-900 text-slate-300 text-sm font-semibold rounded-xl border border-slate-800">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-sm rounded-xl shadow-lg transition-all">
                Crear Usuario
            </button>
        </div>
    </form>

</div>
@endsection
