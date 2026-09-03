@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between border-b border-slate-800 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="edit" class="w-7 h-7 text-amber-400"></i>
                <span>Editar Usuario / Técnico</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Modificar datos y permisos de {{ $user->name }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-slate-900 text-slate-300 text-sm font-medium rounded-xl border border-slate-800">
            Volver
        </a>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="glass-panel p-6 rounded-2xl border border-slate-800 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nombre Completo <span class="text-rose-400">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Correo Electrónico <span class="text-rose-400">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Cambiar Contraseña (Opcional)</label>
            <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener la actual"
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
        </div>

        <div>
            <label for="role" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Rol de Usuario <span class="text-rose-400">*</span></label>
            <select id="role" name="role" required class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
                <option value="technician" {{ $user->role === 'technician' ? 'selected' : '' }}>Técnico de Terreno</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador (Control Total - Domingo)</option>
            </select>
        </div>

        <div>
            <label for="is_active" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Estado de la Cuenta <span class="text-rose-400">*</span></label>
            <select id="is_active" name="is_active" required class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500">
                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Activo (Permite acceso)</option>
                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactivo (Bloquear acceso)</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="rut" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">RUT Gasfiter</label>
                <input type="text" id="rut" name="rut" value="{{ old('rut', $user->rut) }}"
                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none">
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Teléfono</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none">
            </div>
        </div>

        <div>
            <label for="sec_code" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Acreditación SEC / Clase</label>
            <input type="text" id="sec_code" name="sec_code" value="{{ old('sec_code', $user->sec_code) }}"
                   class="w-full px-4 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none">
        </div>

        <div class="pt-4 flex justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-slate-900 text-slate-300 text-sm font-semibold rounded-xl border border-slate-800">Cancelar</a>
            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-sm rounded-xl shadow-lg transition-all">
                Guardar Cambios
            </button>
        </div>
    </form>

</div>
@endsection
