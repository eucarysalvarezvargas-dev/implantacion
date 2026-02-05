@extends('layouts.medico')

@section('title', 'Mi Agenda')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Mi Agenda</h2>
            <p class="text-gray-500 mt-1">
                Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('medicos.horarios', $medico->id) }}" class="btn btn-outline">
                <i class="bi bi-clock mr-2"></i>
                Editar Horarios Semanales
            </a>
            <button type="button" onclick="abrirModalFechaIndisponible()" class="btn btn-primary">
                <i class="bi bi-calendar-x mr-2"></i>
                Agregar Día No Laborable
            </button>
        </div>
    </div>
</div>

<!-- Filtros y Navegación de Semana -->
<div class="card p-4 mb-6">
    <form method="GET" action="{{ route('medico.agenda') }}" class="flex flex-wrap items-center gap-4">
        <!-- Navegación de Semana -->
        <div class="flex items-center gap-2">
            <a href="{{ route('medico.agenda', array_merge(request()->except('semana'), ['semana' => $semanaOffset - 1])) }}" 
               class="btn btn-sm btn-outline p-2">
                <i class="bi bi-chevron-left"></i>
            </a>
            <div class="px-4 py-2 bg-gray-100 rounded-lg text-center min-w-[200px]">
                <span class="font-bold text-gray-800">
                    {{ $inicioSemana->format('d M') }} - {{ $finSemana->format('d M, Y') }}
                </span>
                @if($semanaOffset == 0)
                    <span class="ml-2 text-xs font-medium text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded">Semana Actual</span>
                @endif
            </div>
            <a href="{{ route('medico.agenda', array_merge(request()->except('semana'), ['semana' => $semanaOffset + 1])) }}" 
               class="btn btn-sm btn-outline p-2">
                <i class="bi bi-chevron-right"></i>
            </a>
            @if($semanaOffset != 0)
                <a href="{{ route('medico.agenda', array_merge(request()->except('semana'), ['semana' => 0])) }}" 
                   class="btn btn-sm btn-primary">
                    Hoy
                </a>
            @endif
        </div>

        <div class="flex-grow"></div>

        <!-- Filtro Consultorio -->
        @if($consultorios->count() > 1)
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Consultorio:</label>
                <select name="consultorio_id" onchange="this.form.submit()" class="form-select text-sm py-1.5 min-w-[180px]">
                    <option value="">Todos</option>
                    @foreach($consultorios as $cons)
                        <option value="{{ $cons->id }}" {{ $filtroConsultorioId == $cons->id ? 'selected' : '' }}>
                            {{ $cons->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Filtro Especialidad -->
        @if($especialidades->count() > 1)
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Especialidad:</label>
                <select name="especialidad_id" onchange="this.form.submit()" class="form-select text-sm py-1.5 min-w-[180px]">
                    <option value="">Todas</option>
                    @foreach($especialidades as $esp)
                        <option value="{{ $esp->id }}" {{ $filtroEspecialidadId == $esp->id ? 'selected' : '' }}>
                            {{ $esp->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <input type="hidden" name="semana" value="{{ $semanaOffset }}">
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Grilla Semanal Principal -->
    <div class="lg:col-span-3">
        <div class="card p-0 overflow-hidden">
            <!-- Leyenda de Colores -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-emerald-500"></div>
                        <span class="text-gray-700">Horario de Trabajo</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-blue-500"></div>
                        <span class="text-gray-700">Cita Confirmada</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-orange-500"></div>
                        <span class="text-gray-700">Cita Programada</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-gray-600"></div>
                        <span class="text-gray-700">Cita Completada</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-gray-200 border border-gray-300"></div>
                        <span class="text-gray-700">Disponible</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-rose-400"></div>
                        <span class="text-gray-700">No Laborable</span>
                    </div>
                </div>
            </div>

            <!-- Grilla de Tiempo Semanal -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-3 py-3 text-left text-gray-600 font-semibold w-20">Hora</th>
                            @foreach($diasSemana as $dia)
                                <th class="px-2 py-3 text-center {{ $dia['esHoy'] ? 'bg-emerald-50' : '' }}">
                                    <span class="block font-bold text-gray-800">{{ $dia['nombre'] }}</span>
                                    <span class="block text-xs {{ $dia['esHoy'] ? 'text-emerald-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $dia['fechaCorta'] }}
                                        @if($dia['esHoy'])
                                            <span class="ml-1 bg-emerald-500 text-white px-1.5 py-0.5 rounded text-[10px]">HOY</span>
                                        @endif
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Generar las filas por hora basado en horario del consultorio
                            $horaInicio = \Carbon\Carbon::parse($horaInicioConsultorio);
                            $horaFin = \Carbon\Carbon::parse($horaFinConsultorio);
                            $horas = [];
                            $current = $horaInicio->copy();
                            while ($current < $horaFin) {
                                $horas[] = $current->format('H:i');
                                $current->addMinutes(30);
                            }
                        @endphp
                        
                        @foreach($horas as $hora)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                                <td class="px-3 py-2 text-gray-600 font-medium bg-gray-50">
                                    {{ $hora }}
                                </td>
                                @foreach($diasSemana as $dia)
                                    @php
                                        $fecha = $dia['fecha'];
                                        $diaLabel = $dia['nombre'];
                                        
                                        // Verificar si hay fecha indisponible
                                        $fechaIndisponible = $fechasIndisponibles->get($fecha);
                                        $esNoLaborable = false;
                                        $motivoNoLaborable = '';
                                        
                                        if ($fechaIndisponible) {
                                            if ($fechaIndisponible->todo_el_dia) {
                                                $esNoLaborable = true;
                                                $motivoNoLaborable = $fechaIndisponible->motivo;
                                            } else {
                                                $inicioIndis = \Carbon\Carbon::parse($fechaIndisponible->hora_inicio);
                                                $finIndis = \Carbon\Carbon::parse($fechaIndisponible->hora_fin);
                                                $horaActual = \Carbon\Carbon::parse($hora);
                                                if ($horaActual >= $inicioIndis && $horaActual < $finIndis) {
                                                    $esNoLaborable = true;
                                                    $motivoNoLaborable = $fechaIndisponible->motivo;
                                                }
                                            }
                                        }
                                        
                                        // Verificar si hay cita en esta hora
                                        $citasEnEstaHora = $citasPorFechaHora[$fecha][$hora] ?? [];
                                        $tieneCita = count($citasEnEstaHora) > 0;
                                        $infoCita = '';
                                        $estadoCita = '';
                                        
                                        if ($tieneCita) {
                                            $cita = $citasEnEstaHora[0];
                                            $estadoCita = $cita->estado_cita;
                                            $nombrePaciente = $cita->paciente 
                                                ? $cita->paciente->primer_nombre . ' ' . $cita->paciente->primer_apellido
                                                : ($cita->pacienteEspecial->primer_nombre ?? 'Paciente');
                                            $infoCita = $nombrePaciente . ' (' . $estadoCita . ')';
                                        }
                                        
                                        // Verificar si el médico trabaja en este horario
                                        $trabajaEnEstaHora = false;
                                        $consultorioNombre = '';
                                        
                                        if (isset($horarios[$diaLabel])) {
                                            foreach ($horarios[$diaLabel] as $horario) {
                                                $inicioTurno = \Carbon\Carbon::parse($horario->horario_inicio);
                                                $finTurno = \Carbon\Carbon::parse($horario->horario_fin);
                                                $horaActual = \Carbon\Carbon::parse($hora);
                                                
                                                if ($horaActual >= $inicioTurno && $horaActual < $finTurno) {
                                                    $trabajaEnEstaHora = true;
                                                    $consultorioNombre = $horario->consultorio->nombre ?? '';
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        // Determinar clase CSS y contenido
                                        if ($esNoLaborable) {
                                            $cellClass = 'bg-rose-400 text-white';
                                            $cellTitle = $motivoNoLaborable;
                                            $cellIcon = 'bi-x-lg';
                                        } elseif ($tieneCita) {
                                            if ($estadoCita === 'Confirmada') {
                                                $cellClass = 'bg-blue-500 text-white';
                                                $cellIcon = 'bi-check-circle-fill';
                                            } elseif ($estadoCita === 'En Progreso') {
                                                $cellClass = 'bg-purple-500 text-white';
                                                $cellIcon = 'bi-play-circle-fill';
                                            } elseif ($estadoCita === 'Completada') {
                                                $cellClass = 'bg-gray-600 text-white';
                                                $cellIcon = 'bi-check2-all';
                                            } else {
                                                // Programada
                                                $cellClass = 'bg-orange-500 text-white';
                                                $cellIcon = 'bi-calendar-event';
                                            }
                                            $cellTitle = $infoCita;
                                        } elseif ($trabajaEnEstaHora) {
                                            $cellClass = 'bg-emerald-500 text-white';
                                            $cellTitle = $consultorioNombre;
                                            $cellIcon = 'bi-check-lg';
                                        } else {
                                            $cellClass = 'bg-gray-200 border border-gray-300';
                                            $cellTitle = 'Disponible';
                                            $cellIcon = '';
                                        }
                                    @endphp
                                    <td class="px-1 py-1 text-center {{ $dia['esHoy'] ? 'bg-emerald-50/30' : '' }}">
                                        <div class="h-8 rounded {{ $cellClass }} flex items-center justify-center text-xs font-medium transition-all hover:opacity-90 cursor-pointer"
                                             title="{{ $cellTitle }}">
                                            @if($cellIcon)
                                                <i class="bi {{ $cellIcon }}"></i>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Resumen -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">Resumen Semanal</h4>
            <div class="space-y-3">
                @php
                    $diasActivos = $horarios->keys()->count();
                    $horasTotales = 0;
                    foreach ($horarios as $dia => $turnos) {
                        foreach ($turnos as $turno) {
                            $inicio = \Carbon\Carbon::parse($turno->horario_inicio);
                            $fin = \Carbon\Carbon::parse($turno->horario_fin);
                            $horasTotales += $fin->diffInHours($inicio);
                        }
                    }
                    $citasSemana = $citas->count();
                @endphp
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Días activos</span>
                    <span class="font-bold text-medical-600">{{ $diasActivos }} de 7</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Horas semanales</span>
                    <span class="font-bold text-gray-900">{{ $horasTotales }} hrs</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Citas esta semana</span>
                    <span class="font-bold text-teal-600">{{ $citasSemana }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Consultorios</span>
                    <span class="font-bold text-gray-900">{{ $consultorios->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Próximas Fechas No Laborables -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">
                <i class="bi bi-calendar-x text-blue-500 mr-2"></i>
                Días No Laborables
            </h4>
            
            @if($proximasFechasIndisponibles->isEmpty())
                <div class="text-center py-6 text-gray-500">
                    <i class="bi bi-calendar-check text-4xl mb-2"></i>
                    <p class="text-sm">No hay fechas registradas</p>
                </div>
            @else
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    @foreach($proximasFechasIndisponibles as $fecha)
                        <div class="p-3 rounded-lg bg-blue-50 border border-blue-200 group relative">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-bold text-blue-900 text-sm">
                                        {{ \Carbon\Carbon::parse($fecha->fecha)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-blue-700 mt-1">
                                        {{ $fecha->motivo }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        @if($fecha->todo_el_dia)
                                            <i class="bi bi-clock mr-1"></i> Todo el día
                                        @else
                                            <i class="bi bi-clock mr-1"></i> {{ $fecha->hora_inicio }} - {{ $fecha->hora_fin }}
                                        @endif
                                    </p>
                                </div>
                                <form action="{{ route('medico.fecha-indisponible.destroy', $fecha->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Eliminar" onclick="return confirm('¿Eliminar esta fecha no laborable?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Consultorios -->
        <div class="card p-6">
            <h4 class="font-bold text-gray-900 mb-4">
                <i class="bi bi-building text-gray-500 mr-2"></i>
                Consultorios
            </h4>
            @if($consultorios->isEmpty())
                <p class="text-sm text-gray-500">No tiene consultorios asignados</p>
            @else
                <div class="space-y-2">
                    @foreach($consultorios as $consultorio)
                        <div class="p-2 rounded bg-gray-50 text-sm {{ $filtroConsultorioId == $consultorio->id ? 'ring-2 ring-emerald-500' : '' }}">
                            <p class="font-medium text-gray-800">{{ $consultorio->nombre }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $consultorio->horario_inicio }} - {{ $consultorio->horario_fin }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Agregar Fecha No Laborable -->
<div id="modalFechaIndisponible" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="cerrarModalFechaIndisponible()"></div>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('medico.fecha-indisponible.store') }}" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            <i class="bi bi-calendar-x text-blue-500 mr-2"></i>
                            Agregar Día No Laborable
                        </h3>
                        <button type="button" onclick="cerrarModalFechaIndisponible()" class="text-gray-400 hover:text-gray-600">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="form-label">Fecha <span class="text-red-500">*</span></label>
                            <input type="date" name="fecha" class="input w-full" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div>
                            <label class="form-label">Motivo <span class="text-red-500">*</span></label>
                            <input type="text" name="motivo" class="input w-full" placeholder="Ej: Vacaciones, Feriado, Cita personal..." required maxlength="255">
                        </div>

                        <div>
                            <label class="form-label">Duración</label>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <button type="button" onclick="seleccionarPreset('todo_el_dia')" class="preset-btn active" data-preset="todo_el_dia">
                                    <i class="bi bi-sun mr-1"></i> Todo el día
                                </button>
                                <button type="button" onclick="seleccionarPreset('manana')" class="preset-btn" data-preset="manana">
                                    <i class="bi bi-sunrise mr-1"></i> Solo mañana
                                </button>
                                <button type="button" onclick="seleccionarPreset('tarde')" class="preset-btn" data-preset="tarde">
                                    <i class="bi bi-sunset mr-1"></i> Solo tarde
                                </button>
                                <button type="button" onclick="seleccionarPreset('personalizado')" class="preset-btn" data-preset="personalizado">
                                    <i class="bi bi-clock mr-1"></i> Personalizado
                                </button>
                            </div>
                            <input type="hidden" name="duracion_preset" id="duracion_preset" value="todo_el_dia">
                        </div>

                        <div id="horasPersonalizadas" class="hidden">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-sm">Hora Inicio</label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" class="input w-full">
                                </div>
                                <div>
                                    <label class="form-label text-sm">Hora Fin</label>
                                    <input type="time" name="hora_fin" id="hora_fin" class="input w-full">
                                </div>
                            </div>
                        </div>

                        @if($consultorios->count() > 1)
                        <div>
                            <label class="form-label">Aplicar a Consultorio (Opcional)</label>
                            <select name="consultorio_id" class="form-select w-full">
                                <option value="">Todos los consultorios</option>
                                @foreach($consultorios as $cons)
                                    <option value="{{ $cons->id }}">{{ $cons->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="cerrarModalFechaIndisponible()" class="btn btn-outline">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg mr-2"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .preset-btn {
        @apply px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-all;
    }
    .preset-btn.active {
        @apply bg-blue-500 text-white border-blue-500 hover:bg-blue-600;
    }
</style>
@endpush

@push('scripts')
<script>
    function abrirModalFechaIndisponible() {
        document.getElementById('modalFechaIndisponible').classList.remove('hidden');
    }

    function cerrarModalFechaIndisponible() {
        document.getElementById('modalFechaIndisponible').classList.add('hidden');
    }

    function seleccionarPreset(preset) {
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.preset === preset) {
                btn.classList.add('active');
            }
        });

        document.getElementById('duracion_preset').value = preset;

        const horasDiv = document.getElementById('horasPersonalizadas');
        if (preset === 'personalizado') {
            horasDiv.classList.remove('hidden');
        } else {
            horasDiv.classList.add('hidden');
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cerrarModalFechaIndisponible();
        }
    });
</script>
@endpush

@endsection
