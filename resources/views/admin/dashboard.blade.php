@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')

@push('styles')
<style>
    /* Modern Dashboard Styles */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }
    
    .stat-card.blue { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .stat-card.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-card.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .stat-card.indigo { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    
    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.07);
        margin-bottom: 1.5rem;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-left: 3px solid #3b82f6;
        background: #f8fafc;
        margin-bottom: 0.75rem;
        border-radius: 0 8px 8px 0;
        transition: background 0.3s ease;
    }
    
    .activity-item:hover {
        background: #e2e8f0;
    }
    
    .task-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .task-badge.warning { background: #fef3c7; color: #92400e; }
    .task-badge.danger { background: #fee2e2; color: #991b1b; }
    .task-badge.info { background: #dbeafe; color: #1e40af; }
    
    .metric-change {
        display: inline-flex;
        align-items: center;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }
    
    .metric-change.positive { background: #d1fae5; color: #065f46; }
    .metric-change.negative { background: #fee2e2; color: #991b1b; }
    
    .quick-action {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        color: #374151;
        transition: all 0.3s ease;
    }
    
    .quick-action:hover {
        background: #f9fafb;
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    
    .quick-action i {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: #3b82f6;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Dashboard Administrativo</h1>
                <p class="text-white/80">Bienvenido de nuevo, {{ auth()->user()->administrador->primer_nombre ?? 'Administrador' }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="text-sm text-white/80">Última actualización</div>
                <div class="text-lg font-semibold">{{ now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="dashboard-grid mb-8">
        <!-- Médicos Activos -->
        <div class="stat-card blue">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi-people-fill text-3xl opacity-80"></i>
                    <span class="metric-change positive">
                        <i class="bi-arrow-up"></i> {{ $stats['medicos_nuevos_mes'] }} nuevos
                    </span>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['medicos']) }}</div>
                <div class="text-white/80 text-sm">Médicos Activos</div>
            </div>
        </div>

        <!-- Pacientes Totales -->
        <div class="stat-card green">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi-person-badge-fill text-3xl opacity-80"></i>
                    <span class="metric-change positive">
                        <i class="bi-arrow-up"></i> {{ $stats['pacientes_nuevos_semana'] }} esta semana
                    </span>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['pacientes']) }}</div>
                <div class="text-white/80 text-sm">Pacientes Totales</div>
            </div>
        </div>

        <!-- Citas Hoy -->
        <div class="stat-card purple">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi-calendar-check-fill text-3xl opacity-80"></i>
                    <span class="metric-change positive">
                        <i class="bi-check-circle"></i> {{ $stats['citas_completadas_hoy'] }} completadas
                    </span>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['citas_hoy']) }}</div>
                <div class="text-white/80 text-sm">Citas Hoy</div>
            </div>
        </div>

        <!-- Ingresos del Mes -->
        <div class="stat-card orange">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi-currency-dollar text-3xl opacity-80"></i>
                    <span class="metric-change {{ $stats['crecimiento_ingresos'] >= 0 ? 'positive' : 'negative' }}">
                        <i class="bi-arrow-{{ $stats['crecimiento_ingresos'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['crecimiento_ingresos']) }}%
                    </span>
                </div>
                <div class="text-3xl font-bold mb-1">${{ number_format($stats['ingresos_mes'], 2) }}</div>
                <div class="text-white/80 text-sm">Ingresos del Mes</div>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="stat-card indigo">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi-person-check-fill text-3xl opacity-80"></i>
                </div>
                <div class="text-3xl font-bold mb-1">{{ number_format($stats['usuarios_activos']) }}</div>
                <div class="text-white/80 text-sm">Usuarios Activos</div>
            </div>
        </div>

        <!-- Tareas Pendientes -->
        <div class="stat-card red">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi-exclamation-triangle-fill text-3xl opacity-80"></i>
                </div>
                <div class="text-3xl font-bold mb-1">{{ array_sum($tareas) }}</div>
                <div class="text-white/80 text-sm">Tareas Pendientes</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Weekly Appointments Chart -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Citas de la Semana</h3>
            <canvas id="weeklyChart" width="400" height="200"></canvas>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Ingresos Mensuales</h3>
            <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>

        <!-- Appointment Status Chart -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Estado de Citas</h3>
            <canvas id="statusChart" width="400" height="200"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.citas.create') }}" class="quick-action">
                    <i class="bi-calendar-plus"></i>
                    <div>
                        <div class="font-medium">Nueva Cita</div>
                        <div class="text-sm text-gray-500">Agendar consulta</div>
                    </div>
                </a>
                <a href="{{ route('admin.pacientes.create') }}" class="quick-action">
                    <i class="bi-person-plus"></i>
                    <div>
                        <div class="font-medium">Nuevo Paciente</div>
                        <div class="text-sm text-gray-500">Registrar paciente</div>
                    </div>
                </a>
                <a href="{{ route('admin.medicos.create') }}" class="quick-action">
                    <i class="bi-person-badge"></i>
                    <div>
                        <div class="font-medium">Nuevo Médico</div>
                        <div class="text-sm text-gray-500">Agregar médico</div>
                    </div>
                </a>
                <a href="{{ route('admin.facturacion.create') }}" class="quick-action">
                    <i class="bi-receipt"></i>
                    <div>
                        <div class="font-medium">Nueva Factura</div>
                        <div class="text-sm text-gray-500">Generar factura</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Activity and Tasks Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Actividad Reciente</h3>
                <div class="space-y-2">
                    @forelse($actividadReciente as $activity)
                        <div class="activity-item">
                            <i class="{{ $activity->icono }} {{ $activity->icono_clase }} mr-3"></i>
                            <div class="flex-1">
                                <div class="font-medium text-gray-800">{{ $activity->descripcion }}</div>
                                <div class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="bi-clock-history text-4xl mb-2"></i>
                            <p>No hay actividad reciente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div>
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Tareas Pendientes</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Citas sin confirmar</span>
                        <span class="task-badge warning">{{ $tareas['citas_sin_confirmar'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Pagos pendientes</span>
                        <span class="task-badge danger">{{ $tareas['pagos_pendientes'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Resultados pendientes</span>
                        <span class="task-badge info">{{ $tareas['resultados_pendientes'] }}</span>
                    </div>
                    
                    @if(array_sum($tareas) > 0)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="bi-exclamation-circle text-yellow-600 mr-2"></i>
                                <span class="text-sm text-yellow-800">
                                    Tienes {{ array_sum($tareas) }} tareas pendientes
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="bi-check-circle text-green-600 mr-2"></i>
                                <span class="text-sm text-green-800">
                                    ¡Todo al día!
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Weekly Appointments Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['weekly']['labels']),
            datasets: [{
                label: 'Citas',
                data: @json($chartData['weekly']['data']),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($chartData['revenue']['labels']),
            datasets: [{
                label: 'Ingresos ($)',
                data: @json($chartData['revenue']['data']),
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgb(16, 185, 129)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Appointment Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($chartData['status']['labels']),
            datasets: [{
                data: @json($chartData['status']['data']),
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(16, 185, 129)',
                    'rgb(59, 130, 246)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Auto-refresh every 5 minutes
    setInterval(() => {
        location.reload();
    }, 300000);
</script>
@endpush
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
    @endif
    
    {{-- Decorative Orbs --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-2xl -ml-20 -mb-20"></div>

    <div class="relative z-10 p-8">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-8">
            <div class="text-white">
                <h2 class="text-3xl font-display font-bold mb-2">
                    ¡Bienvenido, {{ $admin->primer_nombre }}! 👋
                </h2>
                <p class="text-white/80 font-medium flex items-center gap-2">
                    <i class="bi bi-calendar4"></i>
                    @php \Carbon\Carbon::setLocale('es'); @endphp
                    {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 w-full xl:w-auto">
                @foreach([
                    ['icon' => 'person-badge', 'value' => $stats['medicos'], 'label' => 'Médicos', 'bg' => 'bg-emerald-500/20', 'route' => route('medicos.index')],
                    ['icon' => 'people', 'value' => $stats['pacientes'], 'label' => 'Pacientes', 'bg' => 'bg-blue-500/20', 'route' => route('pacientes.index')],
                    ['icon' => 'calendar-check', 'value' => $stats['citas_hoy'], 'label' => 'Citas Hoy', 'bg' => 'bg-amber-500/20', 'route' => route('citas.index')],
                    ['icon' => 'currency-dollar', 'value' => number_format($stats['ingresos_mes']/1000, 1) . 'k', 'label' => 'Ingresos', 'bg' => 'bg-purple-500/20', 'route' => route('pagos.index')]
                ] as $stat)
                <a href="{{ $stat['route'] }}" class="block bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 hover:bg-white/20 transition-all cursor-pointer group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $stat['bg'] }} flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <i class="bi bi-{{ $stat['icon'] }} text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-white leading-none">{{ $stat['value'] }}</p>
                            <p class="text-[11px] font-bold text-white/70 uppercase tracking-wide mt-1">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6">
    {{-- Left Column (Charts) --}}
    <div class="col-span-12 lg:col-span-8 space-y-6">
        
        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Weekly Chart --}}
            <a href="{{ route('citas.index') }}" class="glass-card p-6 h-full flex flex-col block hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg group-hover:text-blue-600 transition-colors">Actividad Semanal</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total citas últimos 7 días</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold dark:bg-blue-900/30 dark:text-blue-300">
                        {{ array_sum($chartData['weekly']['data']) }} citas
                    </span>
                </div>
                <div class="chart-wrapper flex-1">
                    <div id="weeklyChart" class="h-full w-full pointer-events-none"></div>
                </div>
            </a>

            {{-- Status Distribution --}}
            <a href="{{ route('citas.index') }}" class="glass-card p-6 h-full flex flex-col block hover:shadow-lg transition-shadow cursor-pointer">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">Distribución</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Estado de citas</p>
                    </div>
                    <div class="flex items-center gap-2">
                         <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                         <span class="text-xs text-gray-500 font-medium">Completadas</span>
                    </div>
                </div>
                <div class="chart-wrapper flex-1 d-flex items-center justify-center">
                    <div id="statusChart" class="h-full w-full pointer-events-none"></div>
                </div>
            </a>
        </div>

        {{-- Revenue Chart --}}
        <a href="{{ route('pagos.index') }}" class="glass-card p-6 block hover:shadow-lg transition-shadow cursor-pointer">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">Ingresos Mensuales</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Rendimiento financiero {{ now()->year }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['ingresos_mes']) }}</p>
                        <p class="text-xs font-bold {{ $stats['crecimiento_ingresos'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $stats['crecimiento_ingresos'] > 0 ? '+' : '' }}{{ $stats['crecimiento_ingresos'] }}% vs mes anterior
                        </p>
                    </div>
                </div>
            </div>
            <div class="chart-wrapper" style="height: 350px;">
                <div id="revenueChart" class="h-full w-full pointer-events-none"></div>
            </div>
        </a>
    </div>

    {{-- Right Column (Sidebar) --}}
    <div class="col-span-12 lg:col-span-4 space-y-6">
        
        {{-- Tasks Card --}}
        <a href="{{ route('citas.index') }}" class="glass-card p-6 block hover:shadow-lg transition-shadow cursor-pointer">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg">Tareas Pendientes</h3>
                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-lg text-xs font-bold dark:bg-amber-900/30 dark:text-amber-300">
                    Prioridad
                </span>
            </div>
            <div class="space-y-4">
                @foreach([
                    ['title' => 'Citas sin confirmar', 'count' => $tareas['citas_sin_confirmar'], 'color' => 'amber', 'icon' => 'exclamation-circle'],
                    ['title' => 'Pagos en revisión', 'count' => $tareas['pagos_pendientes'], 'color' => 'rose', 'icon' => 'wallet2'],
                    ['title' => 'Resultados Lab', 'count' => $tareas['resultados_pendientes'], 'color' => 'blue', 'icon' => 'file-earmark-medical']
                ] as $task)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-white dark:hover:bg-gray-800 border border-transparent hover:border-gray-200 dark:hover:border-gray-700 transition-all stat-hover group">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-{{ $task['color'] }}-100 dark:bg-{{ $task['color'] }}-900/30 flex items-center justify-center text-{{ $task['color'] }}-600 dark:text-{{ $task['color'] }}-400">
                            <i class="bi bi-{{ $task['icon'] }}"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $task['title'] }}</span>
                    </div>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $task['count'] }}</span>
                </div>
                @endforeach
            </div>
        </a>

        {{-- Activity Feed --}}
        <a href="{{ route('admin.notificaciones.index') }}" class="glass-card p-6 block hover:shadow-lg transition-shadow cursor-pointer">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-6">Actividad</h3>
            <div class="relative pl-4 border-l-2 border-gray-100 dark:border-gray-800 space-y-6">
                @forelse($actividadReciente as $actividad)
                <div class="relative group">
                    <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-blue-500 border-4 border-white dark:border-gray-900 group-hover:scale-125 transition-transform"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $actividad->descripcion }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($actividad->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-400">
                    <p class="text-sm">Sin actividad reciente</p>
                </div>
                @endforelse
            </div>
        </a>

        {{-- Quick Actions --}}
        <div class="glass-card p-6">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-4">Acciones</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('medicos.create') }}" class="p-4 rounded-xl bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/30 transition-colors text-center group">
                    <i class="bi bi-person-badge text-2xl text-violet-600 dark:text-violet-400 mb-2 block group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-bold text-violet-700 dark:text-violet-300">Nuevo Médico</span>
                </a>
                <a href="{{ route('pacientes.create') }}" class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors text-center group">
                    <i class="bi bi-person-add text-2xl text-blue-600 dark:text-blue-400 mb-2 block group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300">Nuevo Paciente</span>
                </a>
                <a href="{{ route('citas.create') }}" class="col-span-2 p-3 rounded-xl bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors text-center flex items-center justify-center gap-2 group text-white dark:text-gray-900">
                    <i class="bi bi-plus-circle text-lg"></i>
                    <span class="text-sm font-bold">Nueva Cita</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.1/dist/apexcharts.min.js"></script>
<script>
    const chartData = @json($chartData);
    const isDark = document.documentElement.classList.contains('dark');
    
    const theme = {
        font: 'Inter, system-ui, sans-serif',
        text: isDark ? '#9ca3af' : '#6b7280',
        grid: isDark ? '#374151' : '#f3f4f6'
    };

    // Improved Weekly Chart - Fit to Container
    new ApexCharts(document.querySelector("#weeklyChart"), {
        series: [{ name: 'Citas', data: chartData.weekly.data }],
        chart: { 
            type: 'area', 
            height: '100%', 
            width: '100%',
            toolbar: { show: false }, 
            parentHeightOffset: 0,
            zoom: { enabled: false }
        },
        stroke: { curve: 'smooth', width: 3, colors: ['#3b82f6'] },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 90, 100] } },
        dataLabels: { enabled: false },
        xaxis: { 
            categories: chartData.weekly.labels, 
            labels: { show: false }, // Hide X labels for cleaner mini-chart look
            axisBorder: { show: false }, 
            axisTicks: { show: false },
            tooltip: { enabled: false }
        },
        yaxis: { show: false }, // Hide Y Axis
        grid: { show: false, padding: { top: 0, right: 0, bottom: 0, left: 0 } }, // Remove grid & padding
        tooltip: { theme: isDark ? 'dark' : 'light' }
    }).render();

    // Improved Status Donut - Better Fit
    new ApexCharts(document.querySelector("#statusChart"), {
        series: chartData.status.data,
        chart: { 
            type: 'donut', 
            height: 220, // Specific height for donut to fit better
            fontFamily: theme.font
        },
        labels: chartData.status.labels,
        colors: ['#10b981', '#3b82f6', '#ef4444'],
        legend: { position: 'bottom', fontSize: '12px', labels: { colors: theme.text } },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        value: { fontSize: '24px', fontWeight: 700, color: isDark ? '#fff' : '#111827', offsetY: 5 },
                        total: { 
                            show: true, 
                            label: 'Total', 
                            fontSize: '12px',
                            color: theme.text, 
                            formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0) 
                        }
                    }
                }
            }
        },
        stroke: { show: false }
    }).render();

    // Improved Revenue Chart
    new ApexCharts(document.querySelector("#revenueChart"), {
        series: [{ name: 'Ingresos', data: chartData.revenue.data }],
        chart: { 
            type: 'bar', 
            height: '100%', 
            width: '100%',
            toolbar: { show: false },
            parentHeightOffset: 0
        },
        plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
        colors: ['#8b5cf6'],
        xaxis: { 
            categories: chartData.revenue.labels, 
            labels: { 
                style: { colors: theme.text, fontSize: '11px', fontFamily: theme.font },
                rotate: 0 
            },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: { 
            labels: { 
                formatter: (val) => '$' + (val/1000).toFixed(0) + 'k',
                style: { colors: theme.text, fontSize: '11px' }
            } 
        },
        grid: { 
            borderColor: theme.grid, 
            strokeDashArray: 4,
            padding: { top: 0, right: 0, bottom: 0, left: 10 }
        },
        dataLabels: { enabled: false },
        tooltip: { theme: isDark ? 'dark' : 'light' }
    }).render();
</script>
@endpush
