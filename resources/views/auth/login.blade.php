<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesi&oacute;n - Domingo Isa&iacute;n | T&eacute;cnico en Ingenier&iacute;a</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        .glass-box {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full font-sans antialiased bg-slate-950 flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Background Ambient Glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Header Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl mb-4">
                <img src="{{ asset('images/branding/logo-domingoisain.png') }}" alt="Domingo Isa&iacute;n" class="h-14 w-auto object-contain">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Domingo Isa&iacute;n Plaza Caama&ntilde;o</h1>
            <p class="text-xs text-sky-400 font-medium mt-1 uppercase tracking-wider">Ingenier&iacute;a Civil y Servicios de la Construcci&oacute;n</p>
            <p class="text-[11px] text-slate-400">Gasfiter Certificado Autorizado SEC</p>
        </div>

        <!-- Login Card -->
        <div class="glass-box rounded-3xl p-8 shadow-2xl">
            
            <h2 class="text-lg font-semibold text-slate-200 mb-6 flex items-center gap-2">
                <i data-lucide="lock" class="w-5 h-5 text-sky-400"></i>
                <span>Acceso al Sistema</span>
            </h2>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm">
                    <p class="font-medium mb-1">Error de acceso:</p>
                    <ul class="list-disc list-inside text-xs text-rose-200">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Correo Electr&oacute;nico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                               class="w-full pl-11 pr-4 py-3 bg-slate-900/80 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all"
                               placeholder="domi@domingoisain.cl">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Contrase&ntilde;a</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="w-full pl-11 pr-11 py-3 bg-slate-900/80 border border-slate-800 rounded-xl text-white placeholder-slate-500 text-sm focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors focus:outline-none" title="Mostrar/Ocultar contraseña">
                            <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-800 text-sky-500 focus:ring-sky-500">
                        <span>Recordar sesi&oacute;n</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-semibold rounded-xl text-sm shadow-lg shadow-sky-500/25 transition-all flex items-center justify-center gap-2 group">
                    <span>Ingresar</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-xs text-slate-400">
            &copy; {{ date('Y') }} Domingo Isa&iacute;n Plaza Caama&ntilde;o &mdash; Estado 215 oficina 703, Santiago
        </div>

    </div>

    <script>
        lucide.createIcons();

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>
