@extends('layouts.paciente')

@section('title', 'Mi Portal')

@section('content')
<!-- Welcome Banner Premium -->
<div class="relative overflow-hidden rounded-3xl shadow-xl dark:shadow-2xl mb-8 group transition-all duration-300">
    <!-- Dynamic Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-medical-500 to-medical-600 transition-colors duration-300"></div>
    
    <!-- Orbes Animados de Fondo -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float-orb mix-blend-overlay"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl animate-float-orb-slow mix-blend-overlay"></div>
    <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-white/10 rounded-full blur-2xl animate-float-orb-delayed mix-blend-overlay"></div>
    
    <!-- Patrón de Puntos Decorativos -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
    
    <div class="relative z-10 p-8 md:p-10">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-white text-center md:text-left flex-1">
                <!-- Icono de Saludo Animado -->
                <div class="inline-flex items-center gap-2 mb-4 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 shadow-sm">
                    <i class="bi bi-sun-fill text-xl animate-pulse text-yellow-300"></i>
                    <span class="text-xs font-bold uppercase tracking-widest text-white/90">{{ \Carbon\Carbon::now()->format('H') < 12 ? 'Buenos Días' : (\Carbon\Carbon::now()->format('H') < 18 ? 'Buenas Tardes' : 'Buenas Noches') }}</span>
                </div>
                
                <h2 class="text-3xl md:text-5xl font-display font-bold mb-3 leading-tight tracking-tight drop-shadow-md">
                    ¡Hola, <span class="inline-block transform hover:scale-105 transition-transform duration-300">{{ strtok(auth()->user()->paciente->primer_nombre, ' ') ?? 'Paciente' }}</span>! 👋
                </h2>
                <p class="text-lg text-white/90 font-medium max-w-2xl leading-relaxed drop-shadow-sm">
                    ¿Cómo te sientes hoy? Tu bienestar es nuestra prioridad.
                </p>
            </div>
            
            <a href="{{ route('paciente.citas.create') }}" 
               class="group/btn flex items-center gap-3 px-6 py-3.5 bg-white text-medical-600 rounded-2xl font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700">
                <i class="bi bi-calendar-plus text-xl group-hover/btn:rotate-12 transition-transform"></i>
                <span>Solicitar Cita</span>
                <i class="bi bi-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <!-- Mini Stats Cards Premium -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-10">
            @php
                $statsConfig = [
                    ['icon' => 'calendar-check', 'value' => $stats['citas_proximas'] ?? 0, 'label' => 'Próximas Citas'],
                    ['icon' => 'file-medical', 'value' => $stats['historias'] ?? 0, 'label' => 'Historias'],
                    ['icon' => 'prescription', 'value' => $stats['recetas_activas'] ?? 0, 'label' => 'Recetas Activas'],
                    ['icon' => 'heart-pulse', 'value' => $stats['consultas_mes'] ?? 0, 'label' => 'Este Mes']
                ];
            @endphp
            
            @foreach($statsConfig as $stat)
            <div class="bg-white/10 dark:bg-black/20 backdrop-blur-md rounded-2xl p-4 border border-white/20 hover:bg-white/20 dark:hover:bg-black/30 transition-all duration-300 group/stat cursor-pointer">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center group-hover/stat:scale-110 transition-transform">
                        <i class="bi bi-{{ $stat['icon'] }} text-xl text-white"></i>
                    </div>
                    <i class="bi bi-arrow-up-right text-xs text-white/60 group-hover/stat:text-white group-hover/stat:translate-x-1 group-hover/stat:-translate-y-1 transition-all"></i>
                </div>
                <p class="text-2xl font-bold text-white mb-0.5 tracking-tight">{{ $stat['value'] }}</p>
                <p class="text-xs font-semibold text-white/80 uppercase tracking-wide">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Appointments & History -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Mis Citas Próximas Premium -->
        <div class="rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-lg hover:shadow-2xl bg-white dark:bg-gray-800 transition-all duration-300">
            <!-- Header con Gradiente Sutil -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-br from-medical-50 via-white to-blue-50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 relative overflow-hidden">
                <!-- Patrón decorativo de fondo -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-medical-100 dark:bg-medical-900/20 rounded-full -mr-32 -mt-32 opacity-20 blur-3xl"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center shadow-lg shadow-medical-200 dark:shadow-none">
                                <i class="bi bi-calendar-event text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    Mis Próximas Citas
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Tus consultas programadas</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('paciente.citas.index') }}" class="group/link flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 hover:border-medical-500 dark:hover:border-medical-500 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-200 hover:text-medical-600 dark:hover:text-medical-400 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <span>Ver todas</span>
                        <i class="bi bi-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($citas_proximas ?? [] as $cita)
                <div class="p-6 hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent dark:hover:from-gray-700/50 transition-all group relative">
                    @php
                        $pagosActivos = $cita->facturaPaciente ? $cita->facturaPaciente->pagos->where('status', true) : collect();
                        $tienePago = $pagosActivos->count() > 0;
                        $pagoPendiente = $pagosActivos->where('estado', 'Pendiente')->count() > 0;
                        $pagoConfirmado = $pagosActivos->where('estado', 'Confirmado')->count() > 0;
                        $pagoRechazado = $pagosActivos->where('estado', 'Rechazado')->isNotEmpty() && !$pagoConfirmado && !$pagoPendiente;
                        $ultimoRechazo = $pagoRechazado ? $pagosActivos->where('estado', 'Rechazado')->sortByDesc('created_at')->first() : null;
                    @endphp

                    @if($pagoRechazado)
                        <div class="absolute top-4 right-6 flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/30 dark:to-rose-900/30 rounded-full border border-red-200 dark:border-red-800 shadow-md z-10 animate-pulse">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            <p class="text-xs font-black text-red-600 dark:text-red-400 uppercase tracking-wider">
                                Pago Rechazado
                                @if($ultimoRechazo && $ultimoRechazo->comentarios)
                                    <span class="mx-1 text-red-300">|</span>
                                    <span class="normal-case font-semibold text-red-500 dark:text-red-300 italic">"{{ Str::limit($ultimoRechazo->comentarios, 40) }}"</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    <div class="flex gap-6">
                        <!-- Date Box Premium -->
                        <div class="flex-shrink-0 text-center">
                            <div class="relative w-24 h-24 border-3 border-medical-200 dark:border-medical-800 rounded-2xl p-3 bg-gradient-to-br from-white to-medical-50 dark:from-gray-800 dark:to-gray-900 group-hover:border-medical-400 dark:group-hover:border-medical-600 group-hover:shadow-xl dark:group-hover:shadow-medical-900/20 group-hover:scale-105 transition-all duration-300">
                                <span class="block text-4xl font-black text-medical-700 dark:text-medical-400">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d') }}
                                </span>
                                <span class="block text-xs uppercase font-black text-gray-600 dark:text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('M') }}
                                </span>
                                <!-- Decoración punto -->
                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-medical-500 rounded-full border-2 border-white dark:border-gray-800 shadow-sm"></div>
                            </div>
                        </div>
                        
                        <!-- Info Section -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="font-black text-gray-900 dark:text-white text-xl group-hover:text-medical-700 dark:group-hover:text-medical-400 transition-colors mb-2">
                                        {{ $cita->especialidad->nombre ?? 'Consulta General' }}
                                    </h4>
                                    <p class="text-gray-700 dark:text-gray-300 flex items-center gap-2 font-medium">
                                        <div class="w-8 h-8 rounded-lg bg-medical-100 dark:bg-medical-900/30 flex items-center justify-center">
                                            <i class="bi bi-person-badge text-medical-600 dark:text-medical-400"></i>
                                        </div>
                                        <span class="text-sm">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</span>
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    @php
                                        $badgeColor = match($cita->estado_cita) {
                                            'Confirmada' => 'success',
                                            'Programada' => 'warning',
                                            'En Progreso' => 'info',
                                            'Completada' => 'success',
                                            'Cancelada', 'No Asistió' => 'danger',
                                            default => 'gray'
                                        };

                                        $pagoStatusText = 'PAGO PENDIENTE';
                                        $pagoBadgeType = 'danger';

                                        if($pagoConfirmado) {
                                            $pagoStatusText = 'PAGO CONFIRMADO';
                                            $pagoBadgeType = 'success';
                                        } elseif($pagoPendiente) {
                                            $pagoStatusText = 'PAGO EN REVISIÓN';
                                            $pagoBadgeType = 'warning';
                                        } elseif($pagoRechazado) {
                                            $pagoStatusText = 'PAGO RECHAZADO';
                                            $pagoBadgeType = 'danger';
                                        }
                                    @endphp
                                    <span class="badge badge-{{ $badgeColor }} uppercase font-black tracking-wider text-xs px-4 py-2">
                                        {{ $cita->estado_cita }}
                                    </span>
                                    <span class="badge badge-{{ $pagoBadgeType }} uppercase font-black tracking-wider text-xs px-4 py-2">
                                        {{ $pagoStatusText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Detalles Grid -->
                            <div class="grid grid-cols-2 gap-3 text-xs font-bold mb-4">
                                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 bg-gradient-to-r from-slate-50 to-transparent dark:from-gray-700/50 border border-slate-200 dark:border-gray-600 px-4 py-3 rounded-xl hover:border-medical-300 dark:hover:border-medical-500 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-medical-100 dark:bg-medical-900/30 flex items-center justify-center">
                                        <i class="bi bi-clock text-medical-600 dark:text-medical-400"></i>
                                    </div>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('h:i A') }}</span>
                                </div>
                                
                                <div class="flex items-center gap-3 text-slate-700 dark:text-slate-300 bg-gradient-to-r from-slate-50 to-transparent dark:from-gray-700/50 border border-slate-200 dark:border-gray-600 px-4 py-3 rounded-xl hover:border-medical-300 dark:hover:border-medical-500 transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <i class="bi bi-geo-alt text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span class="font-semibold truncate">Consultorio {{ $cita->medico->consultorio ?? 'Central' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                @if($cita->estado_cita == 'Programada' && $tienePago)
                                    <a href="{{ route('paciente.citas.comprobante', $cita->id) }}" target="_blank" class="flex-1 btn bg-white dark:bg-gray-700 text-slate-700 dark:text-slate-200 border-2 border-slate-200 dark:border-gray-600 hover:border-slate-400 dark:hover:border-gray-500 font-bold hover:bg-slate-50 dark:hover:bg-gray-600">
                                        <i class="bi bi-receipt"></i> Recibo
                                    </a>
                                @elseif($cita->estado_cita == 'Programada' && !$tienePago)
                                    <a href="{{ route('paciente.pagos.registrar', $cita->id) }}" class="flex-1 btn bg-medical-600 text-white shadow-lg shadow-medical-200 dark:shadow-none hover:-translate-y-1 transition-transform font-bold">
                                        <i class="bi bi-credit-card-2-front"></i> Pagar Ahora
                                    </a>
                                @endif
                                
                                <a href="{{ route('paciente.citas.show', $cita->id) }}" 
                                   class="flex-1 btn bg-medical-50 dark:bg-medical-900/20 text-medical-700 dark:text-medical-400 border border-medical-100 dark:border-medical-800 hover:bg-medical-100 dark:hover:bg-medical-900/40 font-bold">
                                    Ver Detalle
                                </a>
                            </div>

                            @if($cita->motivo ?? null)
                            <div class="p-4 bg-gradient-to-r from-medical-50 to-transparent rounded-xl mb-4 border-l-4 border-medical-500">
                                <p class="text-sm text-gray-700 font-medium"><strong class="text-medical-700">Motivo:</strong> {{ $cita->motivo }}</p>
                            </div>
                            @endif
                            
                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                <a href="{{ route('paciente.citas.show', $cita->id) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-medical-600 bg-medical-50 hover:bg-medical-100 border-2 border-medical-200 hover:border-medical-400 transition-all hover:shadow-md transform hover:-translate-y-0.5">
                                    <i class="bi bi-eye"></i>
                                    <span>Ver Detalles</span>
                                </a>

                                @if(!$pagoConfirmado && !$pagoPendiente && !in_array($cita->estado_cita, ['Cancelada', 'No Asistió']))
                                    <a href="{{ route('paciente.pagos.registrar', $cita->id) }}" class="flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-white bg-gradient-to-r from-medical-500 to-medical-600 hover:from-medical-600 hover:to-medical-700 shadow-lg shadow-medical-200 hover:shadow-xl hover:shadow-medical-300 transition-all transform hover:-translate-y-0.5">
                                        <i class="bi bi-credit-card"></i>
                                        <span>Registrar Pago</span>
                                    </a>
                                @endif

                                @if(in_array($cita->estado_cita, ['Programada', 'Confirmada']))
                                    <button onclick="openCancelModal({{ $cita->id }})" class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border-2 border-rose-200 hover:border-rose-400 transition-all hover:shadow-md transform hover:-translate-y-0.5">
                                        <i class="bi bi-x-circle"></i>
                                        <span>Cancelar Cita</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-16 text-center">
                    <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 mb-6 shadow-inner">
                        <i class="bi bi-calendar-x text-6xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-600 mb-2 font-bold text-xl">No tienes citas próximas agendadas</p>
                    <p class="text-gray-400 text-sm mb-6 max-w-md mx-auto">Comienza tu cuidado médico agendando tu primera consulta con nuestros especialistas</p>
                    <a href="{{ route('paciente.citas.create') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-medical-500 to-medical-600 text-white rounded-2xl font-bold shadow-lg shadow-medical-200 hover:shadow-xl hover:shadow-medical-300 transition-all transform hover:-translate-y-1">
                        <i class="bi bi-calendar-plus text-xl"></i>
                        <span>Agendar mi primera cita</span>
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Historial Reciente -->
        <div class="card bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-white dark:from-blue-900/20 dark:to-gray-800">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-display font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="bi bi-clock-history text-blue-600 dark:text-blue-400"></i>
                            Historial Médico Reciente
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tus últimas consultas y procedimientos</p>
                    </div>
                    <a href="{{ route('paciente.historial') }}" class="btn btn-sm btn-outline border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Ver historial completo</a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($historial_reciente ?? [] as $registro)
                    <div class="flex gap-4 items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer border border-transparent dark:border-gray-600/50">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-file-medical text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $registro->diagnostico ?? 'Consulta Médica' }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Dr. {{ $registro->medico->usuario->nombre ?? 'Médico' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 flex items-center gap-2">
                                <i class="bi bi-calendar3"></i>
                                {{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y') }}
                            </p>
                        </div>
                        <a href="{{ url('paciente/historial/' . $registro->id) }}" class="btn btn-sm btn-outline opacity-0 group-hover:opacity-100 transition-opacity dark:border-gray-500 dark:text-gray-300">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-folder2-open text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No hay registros en tu historial médico</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recetas Activas -->
        <div class="card bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-white dark:from-purple-900/20 dark:to-gray-800">
                <h3 class="text-lg font-display font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="bi bi-prescription text-purple-600 dark:text-purple-400"></i>
                    Recetas Activas
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tus medicamentos actuales</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @forelse($recetas_activas ?? [] as $receta)
                    <div class="p-4 bg-purple-50 dark:bg-purple-900/10 rounded-xl border border-purple-200 dark:border-purple-800/50">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $receta->medicamento ?? 'Medicamento' }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    <strong>Dosis:</strong> {{ $receta->dosis ?? 'N/A' }} - {{ $receta->frecuencia ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <strong>Duración:</strong> {{ $receta->duracion ?? 'N/A' }}
                                </p>
                                @if($receta->instrucciones ?? null)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="bi bi-info-circle"></i> {{ $receta->instrucciones }}
                                </p>
                                @endif
                            </div>
                            <span class="badge badge-purple bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 border-none">Activa</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="bi bi-prescription text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No tienes recetas activas</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Profile & Quick Menu -->
    <div class="space-y-6">
        <!-- Perfil Card -->
        <div class="card p-0 overflow-hidden bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 shadow-sm">
            @if($paciente->banner_perfil)
                <div class="relative h-32 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $paciente->banner_perfil) }}?v={{ time() }}')">
                    <div class="absolute inset-0 bg-black/10"></div>
                </div>
            @else
                <div class="relative h-32 {{ $paciente->banner_color ?? 'bg-gradient-to-r from-medical-100 via-green-100 to-blue-100' }}"
                     style="{{ str_contains($paciente->banner_color ?? '', '#') ? 'background-color: ' . $paciente->banner_color : '' }}"></div>
            @endif
            <div class="relative px-6 pb-6">
                <div class="flex flex-col items-center -mt-16">
                    <div class="inline-block p-1.5 bg-white dark:bg-gray-800 rounded-full shadow-lg mb-3">
                        @if(auth()->user()->paciente->foto_perfil)
                            <img src="{{ asset('storage/' . auth()->user()->paciente->foto_perfil) }}?v={{ time() }}" 
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->paciente->primer_nombre . ' ' . auth()->user()->paciente->primer_apellido) }}&background=10b981&color=fff'"
                                 alt="Foto de perfil" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->paciente->primer_nombre . ' ' . auth()->user()->paciente->primer_apellido) }}&background=10b981&color=fff"
                                 class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-700">
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ auth()->user()->paciente->primer_nombre ?? 'Usuario' }}
                        {{ auth()->user()->paciente->primer_apellido ?? '' }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-2">Paciente</p>
                    
                    <!-- Health Info -->
                    <div class="w-full space-y-2 mt-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Tipo de Sangre</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $paciente->historiaClinicaBase->tipo_sangre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Edad</span>
                            <span class="font-bold text-gray-900 dark:text-white">
                                {{ isset($paciente->fecha_nac) ? \Carbon\Carbon::parse($paciente->fecha_nac)->age . ' años' : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="w-full grid grid-cols-3 gap-3 text-center border-t border-gray-100 dark:border-gray-700 pt-5 mt-5">
                        <div>
                            <span class="block font-bold text-gray-900 dark:text-white text-xl">{{ $stats['total_citas'] ?? 0 }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Citas</span>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-900 dark:text-white text-xl">{{ $stats['recetas_activas'] ?? 0 }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Recetas</span>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-900 dark:text-white text-xl">
                                {{ isset(auth()->user()->created_at) ? \Carbon\Carbon::parse(auth()->user()->created_at)->diffInMonths(\Carbon\Carbon::now()) : 0 }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Meses</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Directos -->
        <div class="card p-6 bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 shadow-sm">
            <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="bi bi-grid text-medical-600 dark:text-medical-400"></i>
                Menú Rápido
            </h4>
            <div class="space-y-2">
                <a href="{{ route('paciente.citas.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-medical-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 hover:text-medical-600 dark:hover:text-medical-400 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-medical-50 dark:bg-medical-900/30 flex items-center justify-center text-medical-600 dark:text-medical-400 group-hover:bg-medical-200/20 transition-colors">
                        <i class="bi bi-calendar-plus text-lg"></i>
                    </div>
                    <span class="font-medium flex-1">Agendar Cita</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('paciente.historial') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-600 dark:text-gray-300 hover:text-blue-700 dark:hover:text-blue-400 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 transition-colors">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <span class="font-medium flex-1">Mi Historial</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('paciente.pagos') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-600 dark:text-gray-300 hover:text-purple-700 dark:hover:text-purple-400 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/40 transition-colors">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <span class="font-medium flex-1">Mis Pagos</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="{{ route('paciente.perfil.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-600 dark:text-gray-300 hover:text-amber-700 dark:hover:text-amber-400 transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:bg-amber-100 dark:group-hover:bg-amber-900/40 transition-colors">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <span class="font-medium flex-1">Editar Mi Perfil</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Health Tips -->
        <div class="card p-6 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/10 dark:to-gray-800 border-blue-200 dark:border-blue-800/20">
            <div class="flex gap-3">
                <i class="bi bi-lightbulb text-blue-600 dark:text-blue-400 text-2xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Consejo de Salud</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Recuerda beber al menos 8 vasos de agua al día y mantener una alimentación balanceada para una mejor salud.</p>
                </div>
            </div>
        </div>

        <!-- Ayuda -->
        <div class="card p-6 bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex gap-3">
                <i class="bi bi-question-circle text-medical-600 dark:text-medical-400 text-2xl"></i>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-1">¿Necesitas ayuda?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Contacta con soporte para cualquier consulta</p>
                    <a href="#" class="text-sm text-medical-600 dark:text-medical-400 hover:text-medical-700 dark:hover:text-medical-300 font-semibold">
                        Contactar Soporte <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentCitaId = null;

    function openCancelModal(citaId) {
        currentCitaId = citaId;
        const modal = document.getElementById('modalCancelacion');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeCancelModal() {
        const modal = document.getElementById('modalCancelacion');
        const modalContent = modal.querySelector('.modal-content');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            currentCitaId = null;
            document.getElementById('motivo_cancelacion_input').value = '';
            document.getElementById('explicacion_input').value = '';
            document.getElementById('motivo_error').classList.add('hidden');
        }, 300);
    }

    async function confirmarCancelacion() {
        const motivo = document.getElementById('motivo_cancelacion_input').value;
        const explicacion = document.getElementById('explicacion_input').value;

        if (!motivo || !explicacion) {
            document.getElementById('motivo_error').classList.remove('hidden');
            return;
        }

        const btn = document.getElementById('confirmCancelBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Procesando...';

        try {
            const formData = new FormData();
            formData.append('motivo_cancelacion', motivo);
            formData.append('explicacion', explicacion);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch(`{{ url('citas') }}/${currentCitaId}/solicitar-cancelacion`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (response.ok && data.success !== false) {
                btn.innerHTML = '<i class="bi bi-check-lg mr-2"></i> ¡Hecho!';
                setTimeout(() => location.reload(), 1000);
            } else {
                alert(data.message || 'No se pudo cancelar la cita');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error(error);
            alert('Hubo un problema de conexión');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>

<!-- Custom Modal: Cancelar Cita -->
<div id="modalCancelacion" class="fixed inset-0 z-50 hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeCancelModal()"></div>
    <div class="modal-content relative bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-gray-100 dark:border-gray-700">
        <div class="h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>
        <div class="p-8">
            <div class="w-16 h-16 bg-red-50 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mb-6 ring-4 ring-red-50/50 dark:ring-red-900/20">
                <i class="bi bi-calendar-x-fill text-red-500 dark:text-red-400 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-gray-900 dark:text-white mb-2">¿Cancelar esta cita?</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium">Por favor, indícanos el motivo de la cancelación para reagendarte pronto.</p>
            <div class="space-y-4">
                <div class="form-control">
                    <label for="motivo_cancelacion_input" class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 mb-1 block">Motivo Principal</label>
                    <select id="motivo_cancelacion_input" class="select select-bordered w-full bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:bg-white dark:focus:bg-gray-600 transition-colors" required>
                        <option value="">Seleccione un motivo...</option>
                        <option value="Salud">Problemas de Salud</option>
                        <option value="Trabajo">Motivos Laborales</option>
                        <option value="Personal">Asuntos Personales</option>
                        <option value="Transporte">Problemas de Transporte</option>
                        <option value="Economico">Motivos Económicos</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="form-control">
                    <label for="explicacion_input" class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 mb-1 block">Explícanos un poco más</label>
                    <textarea id="explicacion_input" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all resize-none placeholder:text-gray-400"
                        placeholder="Detalles adicionales..."
                        oninput="document.getElementById('motivo_error').classList.add('hidden')" required></textarea>
                </div>

                <p id="motivo_error" class="hidden text-xs font-bold text-red-500 mt-1 flex items-center gap-1">
                    <i class="bi bi-exclamation-circle"></i> Debes completar todos los campos
                </p>
            </div>
            <div class="flex gap-3 mt-8">
                <button onclick="closeCancelModal()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-all active:scale-95">
                    Volver
                </button>
                <button id="confirmCancelBtn" onclick="confirmarCancelacion()" 
                    class="flex-1 px-6 py-3.5 rounded-xl font-bold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95 flex items-center justify-center">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
@endpush
