<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establecer Contraseña — Instalgaschile SPA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">

    <!-- Background Accent Glows -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md space-y-8 relative z-10">

        <!-- Logo & Branding Header -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 shadow-xl shadow-sky-500/20 border border-sky-400/30">
                <i data-lucide="key-round" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-white">Establecer Contraseña</h1>
                <p class="text-xs text-slate-400 mt-1">Crea tu clave de acceso para Instalgaschile SPA</p>
            </div>
        </div>

        @if(session('error'))
            <div class="p-4 bg-rose-500/20 border border-rose-500/30 rounded-xl text-rose-300 text-xs text-center font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Form -->
        <div class="glass-panel p-8 rounded-3xl border border-slate-800 shadow-2xl space-y-6">
            
            <div class="p-3 bg-slate-900/80 rounded-xl border border-slate-800 flex items-center gap-3">
                <i data-lucide="mail" class="w-5 h-5 text-sky-400 shrink-0"></i>
                <div class="overflow-hidden">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Cuenta de Usuario</span>
                    <span class="text-xs font-semibold text-slate-200 truncate block">{{ $email }}</span>
                </div>
            </div>

            <form action="{{ route('password.set.update') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Nueva Contraseña <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required minlength="6"
                               placeholder="••••••••"
                               class="w-full px-4 py-3 bg-slate-900/90 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all pl-10">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Confirmar Contraseña <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                               placeholder="••••••••"
                               class="w-full px-4 py-3 bg-slate-900/90 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all pl-10">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                    <span>Guardar e Iniciar Sesión</span>
                </button>
            </form>

        </div>

        <div class="text-center text-xs text-slate-500">
            Instalgaschile SPA © {{ date('Y') }} — Todos los derechos reservados.
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
