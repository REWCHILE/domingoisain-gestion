@extends('layouts.app')

@section('title', 'Gestión de Técnicos y Usuarios')

@section('content')
<div class="space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="users" class="w-7 h-7 text-amber-400"></i>
                <span>Gestión de Técnicos y Personal</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Administración de usuarios con acceso al sistema de certificados</p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 text-white font-semibold text-sm shadow-lg shadow-amber-500/25 transition-all">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            <span>Nuevo Técnico / Usuario</span>
        </a>
    </div>

    <!-- Users Table Card -->
    <div class="glass-panel rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 text-xs font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                        <th class="py-4 px-5">Usuario / Nombre</th>
                        <th class="py-4 px-5">Rol / Nivel Acceso</th>
                        <th class="py-4 px-5">RUT & Contacto</th>
                        <th class="py-4 px-5">Acreditación SEC</th>
                        <th class="py-4 px-5">Estado</th>
                        <th class="py-4 px-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @foreach($users as $usr)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            
                            <!-- Name & Email -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-sky-400">
                                        {{ strtoupper(substr($usr->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-white block">{{ $usr->name }}</span>
                                        <span class="text-xs text-slate-400 block">{{ $usr->email }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="py-4 px-5">
                                @if($usr->isAdmin())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        <i data-lucide="shield" class="w-3.5 h-3.5"></i> Administrador (Domingo)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        <i data-lucide="wrench" class="w-3.5 h-3.5"></i> Técnico Terreno
                                    </span>
                                @endif
                            </td>

                            <!-- Contact -->
                            <td class="py-4 px-5">
                                <span class="text-xs font-medium text-slate-300 block">RUT: {{ $usr->rut ?: 'No registrado' }}</span>
                                <span class="text-xs text-slate-400 block mt-0.5">{{ $usr->phone ?: 'Sin teléfono' }}</span>
                            </td>

                            <!-- SEC Code -->
                            <td class="py-4 px-5">
                                <span class="text-xs font-medium text-sky-400">{{ $usr->sec_code ?: 'General SEC' }}</span>
                            </td>

                            <!-- Active status -->
                            <td class="py-4 px-5">
                                @if($usr->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Activo</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">Inactivo</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <form action="{{ route('users.welcome-email', $usr->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Enviar Correo de Bienvenida / Clave" class="px-2.5 py-1.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 border border-sky-500/20 rounded-lg transition-colors flex items-center gap-1.5 text-xs font-semibold">
                                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                            <span>Enviar Clave</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('users.edit', $usr->id) }}" class="p-2 bg-slate-800 hover:bg-slate-700 text-amber-400 rounded-lg transition-colors" title="Editar">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>

                                    @if($usr->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $usr->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 rounded-lg transition-colors" title="Eliminar">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
