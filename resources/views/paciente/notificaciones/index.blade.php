@extends('layouts.paciente')

@section('title', 'Notificaciones')

@section('content')
<!-- Header con Glassmorphism -->
<div class="relative bg-white dark:bg-gray-800 rounded-3xl p-6 lg:p-10 shadow-xl overflow-hidden mb-8 border border-slate-100 dark:border-gray-700">
    <!-- Decorative Elements -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                    <i class="bi bi-bell-fill text-xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Centro de Avisos</h2>
            </div>
            <p class="text-slate-500 dark:text-gray-400 text-lg">Tus recordatorios, alertas y mensajes importantes.</p>
        </div>
        
        @if($stats['no_leidas'] > 0)
        <div class="flex items-center gap-4">
             <form action="{{ route('paciente.notificaciones.leer-todas') }}" method="POST">
                @csrf
                <button type="submit" class="group relative px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/20 transition-all active:scale-95 flex items-center gap-2 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    <i class="bi bi-check-all relative z-10"></i>
                    <span class="relative z-10">Marcar todo leído</span>
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- Stats Cards (Unified Gradient Style) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- No Leídas -->
    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-rose-500 to-orange-500 text-white shadow-lg shadow-rose-500/20 group hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="bi bi-envelope-open text-6xl text-white"></i>
        </div>
        <div class="relative z-10">
            <p class="text-rose-100 font-medium mb-1">Sin Leer</p>
            <h3 class="text-4xl font-black">{{ $stats['no_leidas'] }}</h3>
            <p class="text-xs text-white/80 mt-1 font-medium">Atención requerida</p>
        </div>
    </div>

    <!-- Total -->
    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-blue-600 text-white shadow-lg shadow-indigo-500/20 group hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="bi bi-inbox text-6xl text-white"></i>
        </div>
        <div class="relative z-10">
            <p class="text-indigo-100 font-medium mb-1">Total Recibidas</p>
            <h3 class="text-4xl font-black">{{ $stats['total'] }}</h3>
            <p class="text-xs text-white/80 mt-1 font-medium">Historial completo</p>
        </div>
    </div>

    <!-- Leídas -->
    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-500/20 group hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="bi bi-check2-circle text-6xl text-white"></i>
        </div>
        <div class="relative z-10">
            <p class="text-emerald-100 font-medium mb-1">Archivadas</p>
            <h3 class="text-4xl font-black">{{ $stats['leidas'] }}</h3>
            <p class="text-xs text-white/80 mt-1 font-medium">Procesadas</p>
        </div>
    </div>
</div>

<!-- Filters & List -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Filters -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-slate-100 dark:border-gray-700 shadow-sm sticky top-6">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="bi bi-sliders"></i> Filtros
            </h3>
            
            <form method="GET" action="{{ route('paciente.notificaciones.index') }}" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">Buscar</label>
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-3 text-slate-400"></i>
                        <input type="text" name="buscar" value="{{ request('buscar') }}" 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-gray-700 border-none rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all font-medium"
                               placeholder="Palabra clave...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">Estado</label>
                    <select name="estado" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-gray-700 border-none rounded-xl text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-medium cursor-pointer">
                        <option value="todas" {{ request('estado') == 'todas' ? 'selected' : '' }}>Todas</option>
                        <option value="no_leidas" {{ request('estado') == 'no_leidas' ? 'selected' : '' }}>No Leídas</option>
                        <option value="leidas" {{ request('estado') == 'leidas' ? 'selected' : '' }}>Leídas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-2">Tipo</label>
                    <select name="tipo" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-gray-700 border-none rounded-xl text-slate-800 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all font-medium cursor-pointer">
                        <option value="todas">Todos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
                        Filtrar
                    </button>
                    <a href="{{ route('paciente.notificaciones.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-gray-600 rounded-xl transition-all flex items-center justify-center">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification List -->
    <div class="lg:col-span-3 space-y-4">
        <!-- Bulk Actions (Hidden by default) -->
        <div id="bulk-actions-bar" class="hidden bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-2xl p-4 flex items-center justify-between animate-fade-in mb-4">
            <span class="text-indigo-900 dark:text-indigo-300 font-bold flex items-center gap-2">
                <i class="bi bi-check-square-fill"></i>
                <span id="selected-count">0</span> seleccionadas
            </span>
            <button onclick="eliminarSeleccionadas()" class="px-4 py-2 bg-white dark:bg-gray-800 text-rose-600 font-bold rounded-lg shadow-sm border border-slate-100 dark:border-gray-700 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all flex items-center gap-2">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </div>

        @forelse($notificaciones as $index => $notif)
            @php
                $data = $notif->data;
                $isUnread = is_null($notif->read_at);
                $tipo = $data['tipo'] ?? 'info';
                
                // Color Mapping for Unified Design
                $colors = [
                    'success' => ['color' => 'emerald', 'icon' => 'bi-check-circle-fill'],
                    'warning' => ['color' => 'amber', 'icon' => 'bi-exclamation-triangle-fill'],
                    'danger' =>  ['color' => 'rose', 'icon' => 'bi-x-circle-fill'],
                    'info' =>    ['color' => 'blue', 'icon' => 'bi-info-circle-fill'],
                ];
                $style = $colors[$tipo] ?? $colors['info'];
                $themeColor = $style['color'];
            @endphp

            <!-- Card Unificado - Estilo Ordenes -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-0 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden animate-slide-in-up"
                 style="animation-delay: {{ $index * 50 }}ms; animation-fill-mode: both;">
                
                <!-- Status Stripe -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $themeColor }}-500"></div>

                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Icon -->
                        <div class="shrink-0 flex items-start gap-4">
                             <!-- Checkbox Integrated near Icon -->
                            <div class="pt-4">
                                <input type="checkbox" class="w-5 h-5 rounded-md border-gray-300 text-indigo-600 focus:ring-indigo-500 notif-checkbox cursor-pointer" value="{{ $notif->id }}">
                            </div>

                            <div class="w-14 h-14 rounded-2xl bg-{{ $themeColor }}-50 dark:bg-{{ $themeColor }}-900/20 flex items-center justify-center text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400 group-hover:scale-110 transition-transform duration-300">
                                <i class="bi {{ $style['icon'] }} text-2xl"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Header -->
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-white group-hover:text-{{ $themeColor }}-600 transition-colors">
                                        {{ $data['titulo'] ?? 'Notificación' }}
                                    </h3>
                                    @if($isUnread)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 animate-pulse">
                                            Nueva
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-slate-400 flex items-center gap-1">
                                    <i class="bi bi-clock"></i> {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Body -->
                            <div class="mb-4">
                                <p class="text-sm text-slate-500 dark:text-gray-400">
                                    {{ $data['mensaje'] ?? '' }}
                                </p>
                                @if(isset($data['link']))
                                    <a href="{{ $data['link'] }}" class="inline-flex items-center gap-1 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors mt-2">
                                        Ver detalles <i class="bi bi-arrow-right"></i>
                                    </a>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="flex items-center justify-end pt-4 border-t border-slate-50 dark:border-gray-700/50 gap-2">
                                @if($isUnread)
                                    <button onclick="marcarComoLeida('{{ $notif->id }}')" 
                                            class="btn-premium-outline text-xs py-2 px-3">
                                        <i class="bi bi-check2 mr-1"></i> Marcar leída
                                    </button>
                                @endif
                                
                                <form action="{{ route('paciente.notificaciones.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('¿Eliminar notificación?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-premium-outline text-xs py-2 px-3 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50">
                                        <i class="bi bi-trash mr-1"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-slate-200 dark:border-gray-700 animate-fade-in">
                <div class="w-24 h-24 mx-auto bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <i class="bi bi-bell-slash text-4xl text-slate-300 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Todo está tranquilo</h3>
                <p class="text-slate-500 dark:text-gray-400 max-w-sm mx-auto">
                    No tienes notificaciones pendientes. ¡Disfruta de tu día!
                </p>
                <a href="{{ route('paciente.dashboard') }}" class="mt-8 inline-block px-6 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-black transition-all shadow-lg active:scale-95">
                    Volver al Inicio
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-8">
            {{ $notificaciones->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.notif-checkbox');
        const bulkBar = document.getElementById('bulk-actions-bar');
        const selectedCount = document.getElementById('selected-count');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateBulkBar();
            });
        });

        function updateBulkBar() {
            const checked = document.querySelectorAll('.notif-checkbox:checked').length;
            if (checked > 0) {
                bulkBar.classList.remove('hidden');
                selectedCount.textContent = checked;
            } else {
                bulkBar.classList.add('hidden');
            }
        }
    });

    function marcarComoLeida(id) {
        fetch(`{{ url('paciente/notificaciones') }}/${id}/marcar-leida`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload(); 
            }
        });
    }

    function eliminarSeleccionadas() {
        if (!confirm('¿Confirmar eliminación de seleccionadas?')) return;

        const ids = Array.from(document.querySelectorAll('.notif-checkbox:checked')).map(cb => cb.value);
        
        fetch('{{ route('paciente.notificaciones.destroy-all') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        }).then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
</script>
@endpush

<style>
    .btn-premium-outline {
        @apply inline-flex items-center text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 font-bold rounded-xl transition-all duration-200;
    }
</style>
@endsection
