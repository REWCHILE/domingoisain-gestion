<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Certificados y Cotizaciones') - Domingo Isa&iacute;n</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=2">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#008be3',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#080d1a',
                        },
                        celeste: {
                            400: '#38d9f5',
                            500: '#06b6d4',
                            600: '#0891b2',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Smooth Entrance Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .animate-fade-in {
            animation: fadeIn 0.35s ease-out both;
        }

        .stagger-1 { animation-delay: 40ms; }
        .stagger-2 { animation-delay: 80ms; }
        .stagger-3 { animation-delay: 120ms; }
        .stagger-4 { animation-delay: 160ms; }
        .stagger-5 { animation-delay: 200ms; }
        .stagger-6 { animation-delay: 240ms; }

                /* White / Light Theme Overrides */
        .glass-panel, .glass-card {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04) !important;
        }

        /* Input and form controls */
        .glass-panel input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="button"]),
        .glass-panel select,
        .glass-panel textarea {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        .glass-panel input:focus, .glass-panel select:focus, .glass-panel textarea:focus {
            background-color: #ffffff !important;
            border-color: #0284c7 !important;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12) !important;
        }

        .glass-panel label {
            color: #334155 !important;
        }

        .glass-panel .text-white {
            color: #0f172a !important;
        }

        .glass-panel .text-slate-400 {
            color: #64748b !important;
        }

        .glass-panel .text-slate-300 {
            color: #475569 !important;
        }

        .glass-panel .bg-slate-900, .glass-panel .bg-slate-900\/90, .glass-panel .bg-slate-900\/80 {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #0f172a !important;
        }

        .glass-panel .border-slate-800 {
            border-color: #e2e8f0 !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans antialiased bg-[#f8fafc] text-slate-800 selection:bg-brand-500 selection:text-white">

    <div x-data="{ sidebarHovered: false, mobileOpen: false, get isExpanded() { return this.mobileOpen || this.sidebarHovered; } }" class="min-h-screen flex flex-col md:flex-row relative">
        
                <!-- Mobile Backdrop / Overlay -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false" 
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 md:hidden"
             style="display: none;"></div>

        <!-- Sidebar Navigation (Collapsible with Hover) -->
        <aside @mouseenter="sidebarHovered = true" 
               @mouseleave="sidebarHovered = false"
               :class="{
                   'translate-x-0': mobileOpen,
                   '-translate-x-full md:translate-x-0': !mobileOpen,
                   'w-72 shadow-2xl': isExpanded,
                   'w-20 shadow-sm': !isExpanded
               }"
               class="fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200/90 flex flex-col transition-all duration-300 ease-in-out md:static shrink-0 max-w-[85vw]">
            <!-- Brand Header -->
            <div class="h-20 flex items-center px-4 border-b border-slate-100 bg-gradient-to-r from-slate-50/50 to-white overflow-hidden relative">
                <a href="{{ route('certificates.index') }}" class="flex items-center gap-3 w-full group">
                    <!-- Isotype Logo Icon (Always Visible) -->
                    <div class="w-12 h-12 rounded-xl bg-white border border-slate-200/80 shadow-sm flex items-center justify-center p-1.5 shrink-0 group-hover:scale-105 group-hover:border-sky-300 transition-all">
                        <img src="{{ asset('images/branding/isologotipo.png') }}" alt="Domingo Isa&iacute;n" class="w-9 h-9 object-contain">
                    </div>
                    
                    <!-- Text Brand (Reveals on Hover Expand) -->
                    <div x-show="isExpanded" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-x-2"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="overflow-hidden whitespace-nowrap">
                        <span class="font-extrabold text-slate-900 text-base tracking-tight block leading-tight">Domingo Isa&iacute;n</span>
                        <span class="text-xs text-sky-600 font-semibold tracking-wide">T&eacute;cnico en Ingenier&iacute;a</span>
                    </div>
                </a>

                <!-- Mobile Close Button -->
                <button @click="mobileOpen = false" class="md:hidden p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer shrink-0 ml-auto" title="Cerrar menú">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- User Info Badge (Avatar with Isologotype) -->
            <div class="p-3">
                <a href="{{ route('profile.edit') }}" title="Editar mi perfil" 
                   class="p-2.5 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-sky-400 hover:bg-sky-50/40 transition-all flex items-center gap-3 group cursor-pointer shadow-sm overflow-hidden">
                    <!-- User Avatar using the Isologotype -->
                    <div class="w-10 h-10 rounded-xl bg-white border border-sky-200/80 flex items-center justify-center shadow-xs shrink-0 group-hover:scale-105 transition-transform p-1">
                        <img src="{{ asset('images/branding/isologotipo.png') }}" alt="User" class="w-8 h-8 object-contain">
                    </div>
                    
                    <!-- Details (Visible on Hover) -->
                    <div x-show="isExpanded" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="overflow-hidden flex-1 whitespace-nowrap">
                        <p class="text-xs font-bold text-slate-900 truncate group-hover:text-brand-600 transition-colors">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-[11px] text-sky-600 flex items-center gap-1 font-semibold mt-0.5">
                            @if(Auth::user()->isAdmin())
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-amber-500"></span> Administrador SEC
                            @else
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span> T&eacute;cnico Certificado
                            @endif
                        </p>
                    </div>

                    <i x-show="isExpanded" data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-brand-500 transition-colors shrink-0"></i>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-2 space-y-1.5 overflow-y-auto overflow-x-hidden">
                <div x-show="isExpanded" class="px-3 pb-1 pt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Certificados y Cotizaciones
                </div>

                <!-- Link 1: Listado -->
                <a href="{{ route('certificates.index') }}" 
                   title="Listado de Certificados"
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('certificates.index') ? 'bg-sky-50 text-sky-700 border border-sky-200/80 shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="file-text" class="w-5 h-5 {{ request()->routeIs('certificates.index') ? 'text-sky-600' : 'text-slate-500' }} shrink-0"></i>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition ease-out duration-150"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="whitespace-nowrap">Listado de Certificados</span>
                </a>

                <!-- Link 2: Emitir -->
                <a href="{{ route('certificates.create') }}" 
                   title="Emitir Certificado"
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('certificates.create') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition ease-out duration-150"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="whitespace-nowrap">Emitir Certificado</span>
                </a>

                <div x-show="isExpanded" class="px-3 pb-1 pt-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    Cuenta y Configuraci&oacute;n
                </div>

                <!-- Link 3: Mi Perfil -->
                <a href="{{ route('profile.edit') }}" 
                   title="Mi Perfil"
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('profile.*') ? 'bg-purple-50 text-purple-700 border border-purple-200 shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i data-lucide="user-cog" class="w-5 h-5 text-purple-600 shrink-0"></i>
                    <span x-show="isExpanded" 
                          x-transition:enter="transition ease-out duration-150"
                          x-transition:enter-start="opacity-0"
                          x-transition:enter-end="opacity-100"
                          class="whitespace-nowrap">Mi Perfil</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <!-- Link 4: T&eacute;cnicos -->
                    <a href="{{ route('users.index') }}" 
                       title="Gesti&oacute;n de T&eacute;cnicos"
                       class="flex items-center gap-3.5 px-3.5 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('users.*') ? 'bg-amber-50 text-amber-800 border border-amber-200 shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <i data-lucide="users" class="w-5 h-5 text-amber-600 shrink-0"></i>
                        <span x-show="isExpanded" 
                              x-transition:enter="transition ease-out duration-150"
                              x-transition:enter-start="opacity-0"
                              x-transition:enter-end="opacity-100"
                              class="whitespace-nowrap">Gesti&oacute;n de T&eacute;cnicos</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom Company Info (When Hovered) -->
            <div x-show="isExpanded" 
                 x-transition:enter="transition ease-out duration-200"
                 class="p-3 mx-3 mb-2 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-500 space-y-0.5">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-sky-600"></i>
                    <span>Gasfiter Autorizado SEC</span>
                </div>
                <p>RUT: 12.738.961-6</p>
                <p>Estado 215 of. 703, Santiago</p>
            </div>

            <!-- Logout Footer -->
            <div class="p-3 border-t border-slate-100 bg-slate-50/50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            title="Cerrar Sesi&oacute;n"
                            class="w-full flex items-center justify-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4 shrink-0"></i>
                        <span x-show="isExpanded" class="whitespace-nowrap">Cerrar Sesi&oacute;n</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
            
            <!-- Mobile Header Bar -->
            <header class="md:hidden h-16 bg-white flex items-center justify-between px-4 border-b border-slate-200 sticky top-0 z-30 shadow-xs">
                <button @click="mobileOpen = true" class="p-2 text-slate-600 hover:text-slate-900 rounded-lg bg-slate-100">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/branding/isologotipo.png') }}" alt="Domingo Isa&iacute;n" class="h-8 w-8 object-contain">
                    <span class="font-bold text-slate-900 text-sm">Domingo Isa&iacute;n</span>
                    <span class="text-[10px] bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full font-bold">SEC</span>
                </div>
            </header>

            <!-- Main Body with Entrance Animation -->
            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                
                <!-- Flash Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-xs animate-fade-in">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error') || (isset($errors) && $errors->any()))
                    <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 space-y-1 shadow-xs animate-fade-in">
                        <div class="flex items-center gap-3">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                            <span class="text-sm font-bold">Por favor revise los siguientes detalles:</span>
                        </div>
                        @if(isset($errors) && $errors->any())
                            <ul class="list-disc list-inside text-xs pl-8 space-y-1 text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs pl-8 text-rose-700">{{ session('error') }}</p>
                        @endif
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>