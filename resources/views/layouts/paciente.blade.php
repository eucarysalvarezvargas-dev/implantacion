<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema Médico') }} - @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    @php
        $paciente = auth()->user()->paciente ?? null;
        $temaDinamico = $paciente ? ($paciente->tema_dinamico ?? false) : false;
        $bannerColor = $paciente ? ($paciente->banner_color ?? null) : null;
        
        // Determinar color base para el tema
        $baseColor = '#10b981'; // Emerald 500 predeterminado
        if ($temaDinamico && $bannerColor) {
            if (str_contains($bannerColor, '#')) {
                $baseColor = $bannerColor;
            } elseif (str_contains($bannerColor, 'from-')) {
                // Extraer el primer color del gradiente de Tailwind para aproximar
                if (preg_match('/from-([a-z]+)-(\d+)/', $bannerColor, $matches)) {
                    $colors = [
                        'emerald' => '#10b981', 'green' => '#22c55e', 'lime' => '#84cc16',
                        'teal' => '#14b8a6', 'cyan' => '#06b6d4', 'sky' => '#0ea5e9',
                        'blue' => '#3b82f6', 'indigo' => '#6366f1', 'violet' => '#8b5cf6',
                        'purple' => '#a855f7', 'fuchsia' => '#d946ef', 'pink' => '#ec4899',
                        'rose' => '#f43f5e', 'red' => '#ef4444', 'orange' => '#f97316',
                        'amber' => '#f59e0b', 'yellow' => '#eab308',
                        'slate' => '#64748b', 'gray' => '#6b7280', 'zinc' => '#71717a',
                        'neutral' => '#737373', 'stone' => '#78716c'
                    ];
                    $baseColor = $colors[$matches[1]] ?? $baseColor;
                }
            }
        }

        // Función para calcular si un color es claro (Luminancia)
        $isLight = false;
        if ($temaDinamico) {
            $hex = str_replace('#', '', $baseColor);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114) / 255;
            $isLight = $luminance > 0.6; // Umbral de claridad
        }
        $textColorOnPrimary = $isLight ? '#0f172a' : '#ffffff'; // Negro pizarra o blanco
    @endphp

    @if($temaDinamico)
    <style>
        :root {
            /* Color Base */
            --medical-500: {{ $baseColor }};
            
            /* Variaciones de Tonalidad */
            --medical-900: {{ $baseColor }}f2; /* 95% opacity - Muy oscuro */
            --medical-800: {{ $baseColor }}e6; /* 90% opacity - Oscuro */
            --medical-700: {{ $baseColor }}d9; /* 85% opacity - Medio oscuro */
            --medical-600: {{ $baseColor }}cc; /* 80% opacity - Medio */
            --medical-400: {{ $baseColor }}99; /* 60% opacity - Medio claro */
            --medical-300: {{ $baseColor }}66; /* 40% opacity - Claro */
            --medical-200: {{ $baseColor }}33; /* 20% opacity - Muy claro */
            --medical-100: {{ $baseColor }}1a; /* 10% opacity - Ultra claro */
            --medical-50: {{ $baseColor }}0d;  /* 5% opacity - Casi transparente */
            
            /* Texto sobre fondos de color */
            --text-on-medical: {{ $textColorOnPrimary }};
            --text-on-medical-inverse: {{ $isLight ? '#ffffff' : '#000000' }};
        }
        
        /* ==================== APLICACIÓN DE COLORES DINÁMICOS ==================== */
        
        /* Backgrounds */
        .bg-medical-500, .bg-medical-600, .bg-medical-700, .bg-medical-800, .bg-medical-900 {
            background-color: var(--medical-500) !important;
        }
        .bg-medical-400 { background-color: var(--medical-400) !important; }
        .bg-medical-300 { background-color: var(--medical-300) !important; }
        .bg-medical-200 { background-color: var(--medical-200) !important; }
        .bg-medical-100 { background-color: var(--medical-100) !important; }
        .bg-medical-50 { background-color: var(--medical-50) !important; }
        
        /* Textos */
        .text-medical-900, .text-medical-800, .text-medical-700 { color: var(--medical-900) !important; }
        .text-medical-600, .text-medical-500 { color: var(--medical-500) !important; }
        .text-medical-400 { color: var(--medical-400) !important; }
        
        /* Bordes */
        .border-medical-500, .border-medical-600 { border-color: var(--medical-500) !important; }
        .border-medical-300 { border-color: var(--medical-300) !important; }
        .border-medical-200 { border-color: var(--medical-200) !important; }
        .border-medical-100 { border-color: var(--medical-100) !important; }
        
        /* Rings */
        .ring-medical-500 { --tw-ring-color: var(--medical-500) !important; }
        .ring-medical-200 { --tw-ring-color: var(--medical-200) !important; }
        
        /* Sombras */
        .shadow-medical-200, .shadow-medical-200\/50 {
            --tw-shadow-color: var(--medical-200) !important;
            --tw-shadow: var(--tw-shadow-colored) !important;
        }
        
        /* Gradientes */
        .from-medical-500 { --tw-gradient-from: var(--medical-500) !important; }
        .from-medical-600 { --tw-gradient-from: var(--medical-600) !important; }
        .to-medical-500 { --tw-gradient-to: var(--medical-500) !important; }
        .to-medical-600 { --tw-gradient-to: var(--medical-600) !important; }
        
        /* Hover States */
        .hover\:bg-medical-50:hover { background-color: var(--medical-50) !important; }
        .hover\:bg-medical-100:hover { background-color: var(--medical-100) !important; }
        .hover\:text-medical-600:hover, .hover\:text-medical-700:hover { color: var(--medical-600) !important; }
        .hover\:border-medical-500:hover { border-color: var(--medical-500) !important; }
        .hover\:ring-medical-500:hover { --tw-ring-color: var(--medical-500) !important; }
        
        /* Group Hover */
        .group:hover .group-hover\:bg-medical-50 { background-color: var(--medical-50) !important; }
        .group:hover .group-hover\:text-medical-600 { color: var(--medical-600) !important; }
        .group:hover .group-hover\:border-medical-300 { border-color: var(--medical-300) !important; }
        .group:hover .group-hover\:ring-medical-500 { --tw-ring-color: var(--medical-500) !important; }
        
        /* ==================== ANIMACIONES PREMIUM ==================== */
        
        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px var(--medical-200), 0 0 40px var(--medical-100); }
            50% { box-shadow: 0 0 30px var(--medical-300), 0 0 60px var(--medical-200); }
        }
        
        @keyframes card-hover-lift {
            from { transform: translateY(0) scale(1); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
            to { transform: translateY(-4px) scale(1.01); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
        }
        
        .animate-float-orb { animation: float-orb 15s ease-in-out infinite; }
        .animate-float-orb-slow { animation: float-orb 25s ease-in-out infinite reverse; }
        .animate-float-orb-delayed { animation: float-orb 20s ease-in-out infinite; animation-delay: -5s; }
        .animate-shimmer { animation: shimmer 2s infinite linear; }
        .animate-gradient-shift { animation: gradient-shift 15s ease infinite; background-size: 200% 200%; }
        .animate-pulse-glow { animation: pulse-glow 3s ease-in-out infinite; }
        
        /* ==================== COMPONENTES PERSONALIZADOS ==================== */
        
        /* Card Premium con hover */
        .card-premium {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(to bottom, rgba(255,255,255,0.95), rgba(255,255,255,0.98));
            backdrop-filter: blur(12px);
        }
        html.dark .card-premium {
            background: linear-gradient(to bottom, rgba(31, 41, 55, 0.95), rgba(17, 24, 39, 0.98));
            border-color: rgba(75, 85, 99, 0.4);
        }
        .card-premium:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }
        
        /* Botón Primary Dinámico */
        .btn-primary-dynamic {
            background: linear-gradient(135deg, var(--medical-500), var(--medical-600));
            color: var(--text-on-medical);
            transition: all 0.3s ease;
        }
        .btn-primary-dynamic:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px var(--medical-200), 0 6px 6px var(--medical-100);
        }
        
        /* Badge Dinámico */
        .badge-medical-dynamic {
            background-color: var(--medical-100);
            color: var(--medical-800);
            border: 1px solid var(--medical-200);
        }
        
        /* Toast Notifications */
        .toast-card {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toast-card.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        
        /* Glassmorphism Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    @endif

    @stack('styles')
</head>
<body class="min-h-screen bg-smoke-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="relative mx-auto min-h-screen w-full bg-[#f8fafc] dark:bg-gray-900 selection:bg-medical-500/30 transition-colors duration-200">
        <!-- Premium Mesh Background -->
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <!-- Orbe 1: Principal -->
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] rounded-full animate-float-orb blur-[120px]"
                 style="background-color: var(--medical-500); opacity: 0.15;"></div>
            <!-- Orbe 2: Secundario -->
            <div class="absolute top-[20%] -right-[5%] w-[40%] h-[60%] rounded-full animate-float-orb-slow blur-[100px]"
                 style="background-color: var(--medical-600); opacity: 0.1;"></div>
            <!-- Orbe 3: Inferior Acento -->
            <div class="absolute -bottom-[10%] left-[20%] w-[35%] h-[45%] rounded-full animate-float-orb-delayed blur-[130px]"
                 style="background-color: var(--medical-500); opacity: 0.08;"></div>
            
            @if(!$temaDinamico)
                <div class="absolute top-[10%] left-[30%] w-[20%] h-[30%] bg-premium-200/10 blur-[80px] rounded-full"></div>
            @endif
        </div>

        <header class="sticky top-4 z-40 mx-auto max-w-7xl px-4">
            <div class="rounded-3xl border border-white/40 dark:border-gray-700/40 bg-white/70 dark:bg-gray-800/70 backdrop-blur-2xl shadow-[0_8px_32px_rgba(0,0,0,0.04)] ring-1 ring-black/[0.03] dark:ring-white/[0.03] transition-colors duration-200">
                <div class="container flex h-20 items-center justify-between px-6">
                    <a href="{{ route('paciente.dashboard') }}" class="flex items-center gap-3 active:scale-95 transition-transform group">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-medical-500 to-medical-600 text-white shadow-lg shadow-medical-200/50 group-hover:shadow-medical-500/30 transition-shadow">
                            <i class="bi bi-shield-plus text-xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800 dark:text-white tracking-tight leading-none">Portal Salud</span>
                        </div>
                    </a>

                    <nav class="hidden items-center gap-1 lg:flex bg-slate-50 dark:bg-gray-700/50 p-1.5 rounded-2xl border border-slate-100 dark:border-gray-600/50">
                        @php
                            $navItems = [
                                ['route' => 'paciente.dashboard', 'icon' => 'bi-grid-fill', 'label' => 'Inicio'],
                                ['route' => 'paciente.citas.index', 'icon' => 'bi-calendar-event', 'label' => 'Citas'],
                                ['route' => 'paciente.citas.create', 'icon' => 'bi-plus-circle-dotted', 'label' => 'Agendar'],
                                ['route' => 'paciente.ordenes.index', 'icon' => 'bi-prescription2', 'label' => 'Recetas'],
                                ['route' => 'paciente.historial', 'icon' => 'bi-journal-medical', 'label' => 'Historial'],
                                ['route' => 'paciente.pagos', 'icon' => 'bi-wallet2', 'label' => 'Pagos'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ route($item['route']) }}" 
                               class="group flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold transition-all duration-300 {{ request()->routeIs($item['route']) ? 'bg-medical-500 text-white shadow-md shadow-medical-200' : 'text-slate-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-600 hover:text-medical-600 dark:hover:text-medical-400 hover:shadow-sm' }}">
                                <i class="bi {{ $item['icon'] }} text-sm transition-transform group-hover:scale-110"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>

                    <div class="flex items-center gap-3">
                        <div class="hidden h-8 w-[1px] bg-slate-200 dark:bg-gray-700 lg:block"></div>
                        
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 dark:bg-gray-700 text-slate-500 dark:text-gray-400 transition-all hover:bg-medical-50 dark:hover:bg-gray-600 hover:text-medical-600 dark:hover:text-medical-400">
                            <i class="bi bi-moon-fill text-sm dark:hidden"></i>
                            <i class="bi bi-sun-fill text-sm hidden dark:inline-block"></i>
                        </button>
                        
                        <!-- Notification Bell (Simplified) -->
                        <div class="relative group">
                            <a href="{{ route('paciente.notificaciones.index') }}" 
                               class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-slate-50 dark:bg-gray-700 text-slate-500 dark:text-gray-400 transition-all hover:bg-medical-50 dark:hover:bg-gray-600 hover:text-medical-600 dark:hover:text-medical-400">
                                <i class="bi bi-bell-fill text-sm"></i>
                                @php $unreadCount = $paciente ? $paciente->unreadNotifications()->count() : 0; @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute top-1.5 right-2 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-gray-800"></span>
                                @endif
                            </a>
                            
                            <!-- Dropdown kept but code omitted for brevity/safety since it's large -->
                             <div class="absolute right-0 top-full mt-2 w-80 scale-95 opacity-0 pointer-events-none group-hover:scale-100 group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-slate-100 dark:border-gray-700 overflow-hidden">
                                    <div class="flex items-center justify-between px-4 py-3 bg-medical-50 dark:bg-gray-700/50 border-b border-medical-100 dark:border-gray-600">
                                        <h3 class="text-sm font-bold text-medical-800 dark:text-medical-400">Notificaciones</h3>
                                        @if($unreadCount > 0)
                                            <form method="POST" action="{{ route('paciente.notificaciones.leer-todas') }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs font-bold text-medical-600 hover:text-medical-700 dark:hover:text-medical-300">
                                                    Leídas
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        <!-- Notifications List (Simplified) -->
                                         @forelse(($paciente ? $paciente->unreadNotifications()->take(5)->get() : collect()) as $notification)
                                            <a href="{{ $notification->data['link'] ?? '#' }}" 
                                               class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700 transition-colors border-b border-slate-100 dark:border-gray-700 last:border-0"
                                               onclick="event.preventDefault(); marcarComoLeida('{{ $notification->id }}', '{{ $notification->data['link'] ?? '#' }}')">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 h-2 w-2 mt-2 rounded-full bg-{{ $notification->data['tipo'] === 'success' ? 'medical' : ($notification->data['tipo'] === 'danger' ? 'rose' : 'blue') }}-500"></div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm text-slate-700 dark:text-gray-300 line-clamp-2">{{ $notification->data['mensaje'] ?? 'Nueva notificación' }}</p>
                                                        <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans(null, true, true) }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-6 text-center text-slate-400 dark:text-gray-500">
                                                <span class="text-xs">Sin nuevas notificaciones</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu (Collapsed) -->
                        <div class="relative group pl-1">
                            <button class="flex items-center gap-2 rounded-full bg-white dark:bg-gray-700 border border-slate-200 dark:border-gray-600 p-0.5 pr-3 hover:border-medical-300 dark:hover:border-medical-500 transition-all shadow-sm">
                                @if(auth()->user()->paciente && auth()->user()->paciente->foto_perfil)
                                    <img src="{{ asset('storage/' . auth()->user()->paciente->foto_perfil) }}?v={{ time() }}" 
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->paciente->primer_nombre . ' ' . auth()->user()->paciente->primer_apellido) }}&background=10b981&color=fff'"
                                         class="h-8 w-8 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->paciente->primer_nombre . ' ' . auth()->user()->paciente->primer_apellido) }}&background=10b981&color=fff"
                                         class="h-8 w-8 rounded-full object-cover ring-2 ring-white dark:ring-gray-800">
                                @endif
                                <i class="bi bi-chevron-down text-[10px] text-slate-400 dark:text-gray-400 group-hover:text-medical-500"></i>
                            </button>

                            <!-- Dropdown -->
                            <div class="absolute right-0 top-full mt-2 w-56 scale-95 opacity-0 pointer-events-none group-hover:scale-100 group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-slate-100 dark:border-gray-700 p-1">
                                    <div class="px-4 py-3 border-b border-slate-50 dark:border-gray-700 mb-1">
                                        <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->correo }}</p>
                                        <p class="text-xs text-slate-500 dark:text-gray-400">Paciente</p>
                                    </div>
                                    <a href="{{ route('paciente.perfil.edit') }}" class="flex w-full items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-gray-700 hover:text-medical-600 dark:hover:text-medical-400 transition-colors">
                                        <i class="bi bi-person-gear text-sm"></i>
                                        Mi Perfil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">@csrf
                                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                            <i class="bi bi-power text-sm"></i>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <button id="mobileMenuToggle" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition-all active:scale-90 hover:bg-medical-50 hover:text-medical-600 lg:hidden ring-1 ring-slate-200">
                            <i class="bi bi-grid-fill text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Enhanced Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden">
                <div class="mt-3 px-2">
                    <nav class="flex flex-col gap-1.5 rounded-[2rem] bg-white/90 backdrop-blur-xl p-4 shadow-2xl ring-1 ring-black/[0.05]">
                        @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" 
                           class="flex items-center gap-4 px-5 py-4 rounded-2xl text-base font-bold transition-all {{ request()->routeIs($item['route']) ? 'bg-medical-500 text-white shadow-xl shadow-medical-200' : 'text-slate-600 hover:bg-slate-50' }}"
                           style="{{ (request()->routeIs($item['route']) && $temaDinamico) ? 'color: var(--text-on-medical) !important' : '' }}">
                            <i class="bi {{ $item['icon'] }} text-xl"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                        @endforeach
                        
                        <div class="h-[1px] bg-slate-100 my-2"></div>
                        
                        <div class="flex items-center gap-3 px-4 py-2 mb-2">
                            <div class="h-10 w-10 rounded-xl bg-medical-50 flex items-center justify-center text-medical-600 font-bold">
                                {{ strtoupper(substr(auth()->user()->correo, 0, 1)) }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->correo }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Estado: Activo</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-2xl bg-rose-50 py-4 text-sm font-bold text-rose-600 transition-colors hover:bg-rose-100">
                                <i class="bi bi-box-arrow-right text-lg"></i>
                                Finalizar Sesión
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </header>

        <main class="container mx-auto max-w-7xl px-4 py-8 relative z-10">
            @if(session('success'))
                <div id="alert-success" class="mb-6 flex items-center gap-4 rounded-2xl border border-medical-200 bg-medical-50/80 backdrop-blur-sm px-6 py-4 text-sm font-bold text-medical-800 shadow-sm transition-all animate-in fade-in slide-in-from-top-4 duration-300"
                     style="{{ $temaDinamico ? 'color: var(--medical-800) !important; border-color: var(--medical-200) !important; background-color: var(--medical-50) !important;' : '' }}">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-medical-500 text-white shadow-md shadow-medical-200"
                         style="{{ $temaDinamico ? 'background-color: var(--medical-500) !important; color: var(--text-on-medical) !important;' : '' }}">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button data-dismiss="alert-success" class="h-8 w-8 rounded-lg hover:bg-medical-100/50 text-medical-400 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert-error" class="mb-6 flex items-center gap-4 rounded-2xl border border-rose-100 bg-rose-50/80 backdrop-blur-sm px-6 py-4 text-sm font-bold text-rose-800 shadow-sm transition-all animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-500 text-white shadow-md shadow-rose-200">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="flex-1">{{ session('error') }}</div>
                    <button data-dismiss="alert-error" class="h-8 w-8 rounded-lg hover:bg-rose-100/50 text-rose-400 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-24 right-6 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none"></div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('mobileMenuToggle');
            const menu = document.getElementById('mobileMenu');
            if (toggle && menu) {
                toggle.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('#alert-success, #alert-error');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });

            // Dismiss buttons
            document.querySelectorAll('[data-dismiss]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-dismiss');
                    const target = document.getElementById(targetId);
                    if(target) {
                        target.style.transition = 'opacity 0.3s';
                        target.style.opacity = '0';
                        setTimeout(() => target.remove(), 300);
                    }
                });
            });
        });
        
        // Function to mark notification as read
        window.marcarComoLeida = function(notificationId, redirectUrl) {
            fetch(`/paciente/notificaciones/${notificationId}/marcar-leida`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && redirectUrl && redirectUrl !== '#') {
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (redirectUrl && redirectUrl !== '#') {
                    window.location.href = redirectUrl;
                }
            });
        };

        // Laravel Echo Listener
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo && @json($paciente !== null)) {
                const pacienteId = {{ $paciente->id ?? 'null' }};
                
                window.Echo.private(`App.Models.Paciente.${pacienteId}`)
                    .notification((notification) => {
                        console.log('Notificación recibida:', notification);
                        
                        const data = notification.data || notification;
                        const title = data.titulo || 'Nueva Notificación';
                        const message = data.mensaje || '';
                        const type = data.tipo || 'info';
                        
                        // Crear toast
                        createToast(title, message, type, notification.id);
                        
                        // Actualizar contador en la campana
                        const badge = document.querySelector('.relative .bg-rose-500');
                        if (badge) {
                            let count = parseInt(badge.innerText.replace('+', '')) || 0;
                            count++;
                            badge.innerText = count > 9 ? '9+' : count;
                        } else {
                            // Si no hay badge, crearlo (esto es más complejo, por ahora al menos intentamos buscarlo)
                            const bellLink = document.querySelector('a[href="{{ route('paciente.notificaciones.index') }}"]');
                            if (bellLink) {
                                const newBadge = document.createElement('span');
                                newBadge.className = "absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white ring-2 ring-white";
                                newBadge.innerText = "1";
                                bellLink.appendChild(newBadge);
                            }
                        }
                    });
            }
        });

        // Toast Notification System
        function createToast(title, message, type = 'info', id = null) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const config = {
                success: { bg: 'bg-emerald-500/90', icon: 'check-circle-fill' },
                danger: { bg: 'bg-rose-500/90', icon: 'exclamation-circle-fill' },
                warning: { bg: 'bg-amber-500/90', icon: 'exclamation-triangle-fill' },
                info: { bg: 'bg-blue-600/90', icon: 'info-circle-fill' }
            }[type] || { bg: 'bg-slate-700/90', icon: 'bell-fill' };

            const toast = document.createElement('div');
            toast.className = `toast-card pointer-events-auto w-full backdrop-blur-xl rounded-2xl shadow-2xl p-4 flex gap-4 items-start group border-t border-white/20 shadow-lg ${config.bg} text-white`;
            
            toast.innerHTML = `
                <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center text-white shadow-inner">
                    <i class="bi bi-${config.icon} text-xl"></i>
                </div>
                <div class="flex-1 min-w-0 text-white">
                    <h4 class="text-sm font-bold text-shadow-sm">${title}</h4>
                    <p class="text-xs opacity-90 mt-1 line-clamp-2">${message}</p>
                </div>
                <button class="text-white/60 hover:text-white transition-colors p-1" onclick="this.parentElement.remove()">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            `;

            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 100);

            // Auto-remove after 8 seconds
            setTimeout(() => {
                if (toast && toast.parentNode) {
                    toast.classList.remove('show');
                    setTimeout(() => { if (toast && toast.parentNode) toast.remove(); }, 500);
                }
            }, 8000);
        }

        // Show unread notifications as toasts ONLY if the login flag is present
        @if(session('mostrar_bienvenida_toasts') && $paciente)
            @php
                $toasts = $paciente->unreadNotifications->take(3)->map(function($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->data['titulo'] ?? 'Notificación',
                        'message' => $n->data['mensaje'] ?? '',
                        'tipo' => $n->data['tipo'] ?? 'info'
                    ];
                });
                // Consumir el flag para que no aparezca en la siguiente página
                session()->forget('mostrar_bienvenida_toasts');
            @endphp

            const unreadToasts = @json($toasts);
            unreadToasts.forEach((toast, index) => {
                setTimeout(() => {
                    createToast(toast.title, toast.message, toast.tipo, toast.id);
                }, 500 * (index + 1));
            });
        @endif
    </script>
    @endpush

    @stack('scripts')
    
    <!-- Dark Mode Script -->
    <script>
        // Dark Mode Toggle Logic
        const darkModeToggle = document.getElementById('darkModeToggle');
        const htmlElement = document.documentElement;
        
        // Check for saved preference or system preference
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            htmlElement.classList.add('dark');
        }
        
        // Toggle dark mode
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                htmlElement.classList.toggle('dark');
                const isDark = htmlElement.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        }
    </script>
</body>
</html>
