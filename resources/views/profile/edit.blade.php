@extends('layouts.app')

@section('title', 'Mi Perfil de Usuario')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2.5">
                <i data-lucide="user-cog" class="w-7 h-7 text-purple-400"></i>
                <span>Mi Perfil de Usuario</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">Actualiza tus datos personales, información de contacto y contraseña de acceso</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/20 border border-emerald-500/30 rounded-2xl text-emerald-300 text-sm font-semibold flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-500/20 border border-rose-500/30 rounded-2xl text-rose-300 text-sm">
            <p class="font-bold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside text-xs space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Información Personal y de Contacto -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 space-y-5">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                <i data-lucide="user" class="w-5 h-5 text-sky-400"></i>
                <span>Información Personal</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Nombre Completo -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nombre Completo <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <!-- Correo Electrónico -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Correo Electrónico <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Teléfono de Contacto
                    </label>
                    <div class="relative">
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+56 9 8888 7777"
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <!-- RUT -->
                <div>
                    <label for="rut" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        RUT Personal / Empresa
                    </label>
                    <div class="relative">
                        <input type="text" id="rut" name="rut" value="{{ old('rut', $user->rut) }}" placeholder="12.738.961-6"
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="id-card" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <!-- Código Acreditación SEC -->
                <div class="md:col-span-2">
                    <label for="sec_code" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Acreditación / Clase SEC
                    </label>
                    <div class="relative">
                        <input type="text" id="sec_code" name="sec_code" value="{{ old('sec_code', $user->sec_code) }}" placeholder="Ej: Gasfiter Certificado Autorizado SEC Clase 3"
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-sky-400 font-semibold text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="award" class="w-4 h-4 text-sky-400 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

            </div>
        </div>

        <!-- 2. Cambio de Contraseña -->
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 space-y-5">
            <div>
                <h2 class="text-lg font-semibold text-white flex items-center gap-2 border-b border-slate-800 pb-2">
                    <i data-lucide="key-round" class="w-5 h-5 text-amber-400"></i>
                    <span>Cambiar Contraseña</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">Deja estos campos en blanco si no deseas cambiar tu contraseña actual.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Nueva Contraseña -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" minlength="6" placeholder="••••••••"
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <!-- Confirmar Nueva Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Confirmar Nueva Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="6" placeholder="••••••••"
                               class="w-full px-4 py-3 bg-slate-900 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 pl-10">
                        <i data-lucide="shield-check" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Guardar Cambios de Perfil</span>
            </button>
        </div>

    </form>

</div>
@endsection
