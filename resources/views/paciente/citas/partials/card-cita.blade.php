<div class="cita-card-wrapper card-premium group relative overflow-hidden rounded-3xl border border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 transition-all hover:border-medical-300 dark:hover:border-medical-500 hover:shadow-xl"
     data-tipo="{{ $cita->tipo_cita_display ?? 'propia' }}"
     data-paciente-especial="{{ $cita->paciente_especial_info->id ?? '' }}">
    
    <!-- Hover Gradient Effect -->
    <div class="absolute inset-0 bg-gradient-to-r from-medical-50/50 to-transparent dark:from-medical-900/10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row gap-6">
        <!-- Date Box Premium -->
        <div class="flex-shrink-0 flex flex-col items-center justify-center w-full md:w-24 rounded-2xl bg-slate-50 dark:bg-gray-700/50 border border-slate-100 dark:border-gray-600 p-4 group-hover:bg-white dark:group-hover:bg-gray-700 group-hover:shadow-md transition-all">
            <span class="text-3xl font-black text-slate-800 dark:text-white group-hover:text-medical-600 dark:group-hover:text-medical-400 transition-colors">
                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d') }}
            </span>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-gray-400 mt-1">
                {{ \Carbon\Carbon::parse($cita->fecha_cita)->isoFormat('MMM') }}
            </span>
            <div class="h-1 w-8 bg-medical-500 rounded-full mt-2 opacity-50 group-hover:opacity-100 transition-opacity"></div>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <!-- Header: Title & Status -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white group-hover:text-medical-700 dark:group-hover:text-medical-400 transition-colors {{ $tipo === 'cancelada' ? 'line-through opacity-75' : '' }}">
                        {{ $cita->especialidad->nombre ?? 'Consulta General' }}
                    </h3>
                    <div class="flex items-center gap-2 mt-1">
                        <i class="bi bi-person-circle text-medical-500 text-sm"></i>
                        <p class="text-sm font-medium text-slate-600 dark:text-gray-300">
                            Dr. {{ $cita->medico->primer_nombre ?? 'No asignado' }} {{ $cita->medico->primer_apellido ?? '' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-2">
                    <!-- Status Badge -->
                    @php
                        $statusColors = [
                            'Confirmada' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                            'Programada' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                            'En Progreso' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                            'Completada' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                            'Cancelada' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                            'No Asistió' => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-400',
                        ];
                        $colorClass = $statusColors[$cita->estado_cita] ?? 'bg-gray-100 text-gray-600';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                        {{ $cita->estado_cita }}
                    </span>

                    <!-- Payment Status Badge -->
                    @php
                        $ultimoPago = $cita->facturaPaciente ? $cita->facturaPaciente->pagos->where('status', true)->sortByDesc('created_at')->first() : null;
                        $pagoStatus = $ultimoPago ? $ultimoPago->estado : null;
                        
                        $pagoConfig = match($pagoStatus) {
                            'Confirmado' => ['bg' => 'bg-emerald-500', 'text' => 'Pagado', 'icon' => 'check-lg'],
                            'Pendiente' => ['bg' => 'bg-amber-500', 'text' => 'Verificando', 'icon' => 'hourglass-split'],
                            'Rechazado' => ['bg' => 'bg-rose-500', 'text' => 'Rechazado', 'icon' => 'x-lg'],
                            default => null
                        };
                    @endphp

                    @if($pagoConfig)
                        <span class="flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-slate-50 dark:bg-gray-700 border border-slate-200 dark:border-gray-600">
                            <span class="h-1.5 w-1.5 rounded-full {{ $pagoConfig['bg'] }}"></span>
                            <span class="text-[10px] font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wide">{{ $pagoConfig['text'] }}</span>
                        </span>
                    @elseif($cita->facturaPaciente && $cita->estado_cita != 'Cancelada' && !$pagoConfig)
                         <span class="flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800">
                            <i class="bi bi-exclamation-circle-fill text-[10px] text-rose-500"></i>
                            <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wide">Pago Pendiente</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-y-3 gap-x-8 mt-4 text-sm">
                 <div class="flex items-center gap-3 text-slate-600 dark:text-gray-400">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400">
                        <i class="bi bi-clock"></i>
                    </div>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</span>
                </div>
                 <div class="flex items-center gap-3 text-slate-600 dark:text-gray-400">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 dark:bg-gray-700 text-slate-500 dark:text-gray-400">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <span class="truncate font-medium">{{ $cita->consultorio->nombre ?? 'Consultorio Externo' }}</span>
                </div>
            </div>

            <!-- Message/Observation Box -->
            @if($cita->motivo && $tipo !== 'cancelada')
                <div class="mt-4 p-3 rounded-xl bg-slate-50 dark:bg-gray-700/50 border border-slate-100 dark:border-gray-600">
                    <p class="text-xs font-bold text-medical-600 dark:text-medical-400 uppercase mb-1">Motivo</p>
                    <p class="text-sm text-slate-600 dark:text-gray-300 line-clamp-2">"{{ $cita->motivo }}"</p>
                </div>
            @endif

            @if($tipo === 'cancelada' && $cita->observaciones)
                <div class="mt-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800">
                    <p class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase mb-1">Causa de Cancelación</p>
                    <p class="text-sm text-slate-700 dark:text-gray-300 italic">"{{ $cita->observaciones }}"</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-between gap-3 mt-6 pt-4 border-t border-slate-100 dark:border-gray-700">
                <a href="{{ route('paciente.citas.show', $cita->id) }}" class="flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-medical-600 dark:hover:text-medical-400 transition-colors">
                    Ver Detalles <i class="bi bi-arrow-right"></i>
                </a>

                <div class="flex items-center gap-2">
                    @php
                        $tienePago = $cita->facturaPaciente && $cita->facturaPaciente->pagos->count() > 0;
                        $pagoPendiente = $cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->where('estado', 'Pendiente')->count() > 0;
                        $pagoConfirmado = $cita->facturaPaciente && $cita->facturaPaciente->pagos->where('status', true)->where('estado', 'Confirmado')->count() > 0;
                    @endphp

                    @if($tipo == 'proxima')
                        @if($pagoConfirmado)
                            <a href="{{ route('paciente.citas.comprobante', $cita->id) }}" target="_blank" class="btn bg-white dark:bg-gray-700 text-slate-600 dark:text-gray-300 border border-slate-200 dark:border-gray-600 hover:bg-slate-50 dark:hover:bg-gray-600 px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition-colors">
                                <i class="bi bi-receipt mr-1"></i> Recibo
                            </a>
                        @elseif(!$pagoConfirmado && !$pagoPendiente && !in_array($cita->estado_cita, ['Cancelada', 'No Asistió']))
                            <a href="{{ route('paciente.pagos.registrar', $cita->id) }}" class="btn bg-medical-600 text-white hover:bg-medical-700 px-4 py-2 rounded-xl text-xs font-bold shadow-md shadow-medical-200 dark:shadow-none hover:-translate-y-0.5 transition-all">
                                <i class="bi bi-credit-card mr-1"></i> Pagar
                            </a>
                        @endif

                        <button onclick="openCancelModal({{ $cita->id }})" class="btn text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 px-3 py-2 rounded-xl text-xs font-bold transition-colors">
                            Cancelar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
