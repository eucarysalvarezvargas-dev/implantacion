@extends('layouts.admin')

@section('title', 'Horarios del Médico')

@section('content')
<script>
    // 1. Data Sources (Global) - Normalizado para aceptar String o Int como clave
    window.rawHorarios = @json($consultorios->mapWithKeys(function($c) {
        return [$c->id => [
            'inicio' => \Carbon\Carbon::parse($c->horario_inicio)->format('H:i'), 
            'fin' => \Carbon\Carbon::parse($c->horario_fin)->format('H:i')
        ]];
    }));

    // Data de Reglas (Especialidades)
    window.globalConsultorioRules = @json($consultorios->mapWithKeys(function($c) { 
        return [$c->id => $c->especialidades->pluck('id')]; 
    }));

    // Helper seguro para obtener horarios
    window.getConsultorioData = function(id) {
        if (!id) return null;
        return window.rawHorarios[id] || window.rawHorarios[String(id)] || window.rawHorarios[parseInt(id)];
    };

    // 2. Component Logic Factory - Robust Version
    window.makeScheduleCard = function(data) {
        return {
            editing: false,
            // Inicializar estados asegurando que no sean null para los inputs
            manana: {
                active: !!data.manana.active,
                consultorio_id: data.manana.consultorio_id ? String(data.manana.consultorio_id) : '',
                especialidad_id: data.manana.especialidad_id ? String(data.manana.especialidad_id) : '',
                inicio: data.manana.inicio || '08:00',
                fin: data.manana.fin || '12:00',
            },
            tarde: {
                active: !!data.tarde.active,
                consultorio_id: data.tarde.consultorio_id ? String(data.tarde.consultorio_id) : '',
                especialidad_id: data.tarde.especialidad_id ? String(data.tarde.especialidad_id) : '',
                inicio: data.tarde.inicio || '14:00',
                fin: data.tarde.fin || '18:00',
            },

            // Computed Helpers para compatibilidad
            get manana_active() { return this.manana.active; },
            set manana_active(val) { this.manana.active = val; },
            get tarde_active() { return this.tarde.active; },
            set tarde_active(val) { this.tarde.active = val; },
            get active() { return this.manana.active || this.tarde.active; },

            init() {
                // Forzar validación inicial si hay datos cargados
                if(this.manana.active) this.validateBounds('manana');
                if(this.tarde.active) this.validateBounds('tarde');
                
                // Observadores para corrección automática
                this.$watch('manana.consultorio_id', () => this.validateBounds('manana'));
                this.$watch('tarde.consultorio_id', () => this.validateBounds('tarde'));
            },

            // Lógica de validación visual (Retorna clases CSS)
            getStatusClass(shift, tipo) {
                const cId = this[shift].consultorio_id;
                const cData = window.getConsultorioData(cId);
                if (!cData) return 'text-gray-500'; // Sin consultorio seleccionado

                const horaUsuario = this[shift][tipo]; // 'inicio' o 'fin'

                if (tipo === 'inicio') {
                    // Si la hora de inicio del médico es ANTES de que abra el consultorio -> ROJO
                    if (horaUsuario < cData.inicio) return 'text-red-600 font-bold';
                }
                if (tipo === 'fin') {
                    // Si la hora fin del médico es DESPUÉS de que cierre el consultorio -> ROJO
                    if (horaUsuario > cData.fin) return 'text-red-600 font-bold';
                }

                // Si todo está bien -> VERDE
                return 'text-green-600 font-medium';
            },

            // Texto dinámico para mostrar horarios del consultorio
            getLimitText(shift, tipo) {
                const cId = this[shift].consultorio_id;
                const cData = window.getConsultorioData(cId);
                if (!cData) return '--:--';
                return cData[tipo];
            },

            // Validación estricta para los inputs (min/max attributes)
            getInputLimits(shift) {
                const cId = this[shift].consultorio_id;
                const cData = window.getConsultorioData(cId);
                
                let limits = { min: '00:00', max: '23:59' };
                
                if (cData) {
                    if (shift === 'manana') {
                        limits.min = cData.inicio;
                        limits.max = '12:00'; // Límite lógico de turno mañana
                    } else {
                        limits.min = '12:00'; // Límite lógico de turno tarde
                        limits.max = cData.fin;
                    }
                } else {
                    // Fallbacks si no hay consultorio
                    if (shift === 'manana') limits.max = '12:00';
                    if (shift === 'tarde') limits.min = '12:00';
                }
                return limits;
            },

            // Autocorrección (Llamada por watchers)
            validateBounds(shift) {
                this.$nextTick(() => {
                    const cId = this[shift].consultorio_id;
                    const cData = window.getConsultorioData(cId);
                    if (!cData) return;

                    // Si el inicio es menor a la apertura, corregir
                    if (this[shift].inicio < cData.inicio) {
                         this[shift].inicio = cData.inicio;
                    }
                    // Si el fin es mayor al cierre, corregir
                    if (this[shift].fin > cData.fin) {
                        this[shift].fin = cData.fin;
                    }
                });
            },
            
            // Filtro de especialidades
            isAllowed(consultorioId, especialidadId) {
                if (!consultorioId) return true; 
                const rules = window.globalConsultorioRules[consultorioId] || window.globalConsultorioRules[String(consultorioId)];
                if (!rules) return true;
                return rules.includes(parseInt(especialidadId));
            }
        };
    };
</script>

<div class="mb-6">
    <a href="{{ route('medicos.show', $medico->id) }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver al Perfil
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Horarios de Atención</h2>
            <p class="text-gray-500 mt-1">
                Dr. {{ $medico->primer_nombre }} {{ $medico->primer_apellido }} 
                @if($medico->especialidades->count() > 0)
                    - {{ $medico->especialidades->pluck('nombre')->implode(', ') }}
                @endif
            </p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('horariosForm').submit()">
            <i class="bi bi-save mr-2"></i>
            Guardar Cambios
        </button>
    </div>
</div>

<form id="horariosForm" method="POST" action="{{ route('medicos.guardar-horario', $medico->id) }}">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuración de Horarios -->
        <div class="lg:col-span-2 space-y-4">
            
            @php
                $diasSemana = [
                    'lunes' => 'Lunes', 
                    'martes' => 'Martes', 
                    'miercoles' => 'Miércoles', 
                    'jueves' => 'Jueves', 
                    'viernes' => 'Viernes', 
                    'sabado' => 'Sábado', 
                    'domingo' => 'Domingo'
                ];
            @endphp

            @foreach($diasSemana as $key => $diaLabel)
                @php
                    $dayRecords = $horarios->filter(function($h) use ($key, $diaLabel) {
                        return stripos($h->dia_semana, $key) !== false 
                            || stripos($h->dia_semana, $diaLabel) !== false;
                    });
                    
                    $hManana = $dayRecords->first(function($h) { return stripos($h->turno, 'm') === 0; });
                    $hTarde = $dayRecords->first(function($h) { return stripos($h->turno, 't') === 0; });

                    $isActive = $hManana || $hTarde; 
                    
                    $initData = [
                        'manana' => [
                            'active' => (bool)$hManana,
                            'consultorio_id' => $hManana ? $hManana->consultorio_id : null,
                            'especialidad_id' => $hManana ? $hManana->especialidad_id : null,
                            'inicio' => $hManana ? \Carbon\Carbon::parse($hManana->horario_inicio)->format('H:i') : null,
                            'fin' => $hManana ? \Carbon\Carbon::parse($hManana->horario_fin)->format('H:i') : null,
                        ],
                        'tarde' => [
                            'active' => (bool)$hTarde,
                            'consultorio_id' => $hTarde ? $hTarde->consultorio_id : null,
                            'especialidad_id' => $hTarde ? $hTarde->especialidad_id : null,
                            'inicio' => $hTarde ? \Carbon\Carbon::parse($hTarde->horario_inicio)->format('H:i') : null,
                            'fin' => $hTarde ? \Carbon\Carbon::parse($hTarde->horario_fin)->format('H:i') : null,
                        ]
                    ];
                @endphp
                
                <div class="card p-0 overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 mb-4" 
                     x-data="makeScheduleCard(@json($initData))">
                     
                    <!-- Hidden Input for Active State (Calculated) -->
                    <input type="hidden" name="horarios[{{ $key }}][activo]" 
                           value="{{ $isActive ? 1 : 0 }}" 
                           x-bind:value="active ? 1 : 0">
                           
                    <!-- Hidden Map for Shifts (Guarantees submission even if toggle is unchecked/UI hidden) -->
                    <input type="hidden" name="horarios[{{ $key }}][manana_activa]" 
                           value="{{ $hManana ? 1 : 0 }}" 
                           x-bind:value="manana_active ? 1 : 0">
                           
                    <input type="hidden" name="horarios[{{ $key }}][tarde_activa]" 
                           value="{{ $hTarde ? 1 : 0 }}" 
                           x-bind:value="tarde_active ? 1 : 0">
                    
                    <!-- Datos de Turno Mañana (siempre se envían para preservar valores existentes) -->
                    @if($hManana)
                    <input type="hidden" name="horarios[{{ $key }}][manana_consultorio_id]" value="{{ $hManana->consultorio_id }}" x-bind:value="manana.consultorio_id">
                    <input type="hidden" name="horarios[{{ $key }}][manana_especialidad_id]" value="{{ $hManana->especialidad_id }}" x-bind:value="manana.especialidad_id">
                    <input type="hidden" name="horarios[{{ $key }}][manana_inicio]" value="{{ \Carbon\Carbon::parse($hManana->horario_inicio)->format('H:i') }}">
                    <input type="hidden" name="horarios[{{ $key }}][manana_fin]" value="{{ \Carbon\Carbon::parse($hManana->horario_fin)->format('H:i') }}">
                    @endif
                    
                    <!-- Datos de Turno Tarde (siempre se envían para preservar valores existentes) -->
                    @if($hTarde)
                    <input type="hidden" name="horarios[{{ $key }}][tarde_consultorio_id]" value="{{ $hTarde->consultorio_id }}" x-bind:value="tarde.consultorio_id">
                    <input type="hidden" name="horarios[{{ $key }}][tarde_especialidad_id]" value="{{ $hTarde->especialidad_id }}" x-bind:value="tarde.especialidad_id">
                    <input type="hidden" name="horarios[{{ $key }}][tarde_inicio]" value="{{ \Carbon\Carbon::parse($hTarde->horario_inicio)->format('H:i') }}">
                    <input type="hidden" name="horarios[{{ $key }}][tarde_fin]" value="{{ \Carbon\Carbon::parse($hTarde->horario_fin)->format('H:i') }}">
                    @endif

                    <!-- HEADER -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white shadow-sm"
                                 :class="active ? 'bg-medical-500' : 'bg-gray-300'">
                                {{ strtoupper(substr($diaLabel, 0, 1)) }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $diaLabel }}</h3>
                        </div>
                        
                        <!-- Actions -->
                        <div>
                            <template x-if="!editing">
                                <button type="button" @click="editing = true" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg font-medium flex items-center transition-colors shadow-sm">
                                    <i class="bi bi-pencil-square mr-1"></i> Editar
                                </button>
                            </template>
                            
                            <template x-if="editing">
                                <button type="button" @click="editing = false" class="text-sm bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg font-medium flex items-center transition-colors shadow-sm">
                                    <i class="bi bi-check2-circle mr-1"></i> Listo
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- CONTENT BODY -->
                    <div class="p-6">
                        
                        <!-- STATE 1: SUMMARY (Saved & Not Editing) -->
                        <div x-show="!editing && active" class="space-y-4">
                            @if($hManana)
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50/50 border border-blue-100">
                                    <div class="bg-blue-100 text-blue-600 p-2 rounded-md">
                                        <i class="bi bi-sun-fill"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-blue-900">Mañana ({{ \Carbon\Carbon::parse($hManana->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($hManana->horario_fin)->format('H:i') }})</p>
                                        <p class="text-xs text-blue-700 mt-1">
                                            {{ $hManana->consultorio->nombre ?? 'Sin Consultorio' }} • {{ $hManana->especialidad->nombre ?? 'Sin Especialidad' }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($hTarde)
                                <div class="flex items-start gap-3 p-3 rounded-lg bg-orange-50/50 border border-orange-100">
                                    <div class="bg-orange-100 text-orange-600 p-2 rounded-md">
                                        <i class="bi bi-sunset-fill"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-orange-900">Tarde ({{ \Carbon\Carbon::parse($hTarde->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($hTarde->horario_fin)->format('H:i') }})</p>
                                        <p class="text-xs text-orange-700 mt-1">
                                            {{ $hTarde->consultorio->nombre ?? 'Sin Consultorio' }} • {{ $hTarde->especialidad->nombre ?? 'Sin Especialidad' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        

                        <!-- STATE 3: EDITING FORM -->
                        <div x-show="editing" x-transition class="space-y-6">
                            
                            <!-- Turno Mañana Toggle -->
                            <div class="border-l-4 border-blue-500 pl-4">
                                <label class="flex items-center gap-2 mb-3 cursor-pointer">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox" name="horarios[{{ $key }}][manana_activa]" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer" 
                                            value="1" x-model="manana_active" :checked="manana_active"
                                            :class="{'right-0 border-blue-600': manana_active, 'right-auto border-gray-300': !manana_active}"/>
                                        <div class="toggle-label block overflow-hidden h-5 rounded-full cursor-pointer"
                                            :class="{'bg-blue-600': manana_active, 'bg-gray-300': !manana_active}"></div>
                                    </div>
                                    <span class="font-bold text-gray-700">Turno Mañana</span>
                                </label>

                                <div x-show="manana_active" class="grid grid-cols-1 md:grid-cols-2 gap-3 animate-fade-in-down">
                                    <div>
                                        <label class="form-label text-xs">Consultorio</label>
                                        <select name="horarios[{{ $key }}][manana_consultorio_id]" class="form-select text-sm"
                                                x-model="manana.consultorio_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($consultorios as $consultorio)
                                                <option value="{{ $consultorio->id }}"
                                                    {{ ($hManana && $hManana->consultorio_id == $consultorio->id) ? 'selected' : '' }}>
                                                    {{ $consultorio->nombre }} ({{ \Carbon\Carbon::parse($consultorio->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($consultorio->horario_fin)->format('H:i') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Especialidad</label>
                                        <select name="horarios[{{ $key }}][manana_especialidad_id]" class="form-select text-sm"
                                                x-model="manana.especialidad_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($medico->especialidades as $especialidad)
                                                <option value="{{ $especialidad->id }}" 
                                                    x-show="isAllowed(manana.consultorio_id, '{{ $especialidad->id }}')"
                                                    {{ ($hManana && $hManana->especialidad_id == $especialidad->id) ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-info-600 mt-1" 
                                           x-show="manana.consultorio_id && !isAllowed(manana.consultorio_id, $el.previousElementSibling.value)">
                                            <!-- Simple feedback if selected option becomes invalid -->
                                        </p>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Inicio</label>
                                        <p x-show="manana.consultorio_id" class="text-xs mb-1 transition-colors duration-200"
                                           :class="getStatusClass('manana', 'inicio')">
                                            <i class="bi bi-clock"></i> Consultorio abre: <span x-text="getLimitText('manana', 'inicio')"></span>
                                        </p>
                                        <input type="time" name="horarios[{{ $key }}][manana_inicio]" class="input text-sm" 
                                            :min="getInputLimits('manana').min" 
                                            :max="getInputLimits('manana').max"
                                            x-model="manana.inicio"
                                            @change="validateBounds('manana')">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Fin</label>
                                        <p x-show="manana.consultorio_id" class="text-xs text-gray-500 mb-1">
                                            Corte turno mañana: 12:00
                                        </p>
                                        <input type="time" name="horarios[{{ $key }}][manana_fin]" class="input text-sm" 
                                            :min="getInputLimits('manana').min" 
                                            :max="getInputLimits('manana').max"
                                            x-model="manana.fin"
                                            @change="validateBounds('manana')">
                                    </div>
                                </div>
                            </div>

                            <!-- Turno Tarde Toggle -->
                            <div class="border-l-4 border-orange-500 pl-4">
                                <label class="flex items-center gap-2 mb-3 cursor-pointer">
                                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                        <input type="checkbox" name="horarios[{{ $key }}][tarde_activa]" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer" 
                                            value="1" x-model="tarde_active" :checked="tarde_active"
                                            :class="{'right-0 border-orange-600': tarde_active, 'right-auto border-gray-300': !tarde_active}"/>
                                        <div class="toggle-label block overflow-hidden h-5 rounded-full cursor-pointer"
                                            :class="{'bg-orange-600': tarde_active, 'bg-gray-300': !tarde_active}"></div>
                                    </div>
                                    <span class="font-bold text-gray-700">Turno Tarde</span>
                                </label>
                                
                                <div x-show="tarde_active" class="grid grid-cols-1 md:grid-cols-2 gap-3 animate-fade-in-down">
                                    <div>
                                        <label class="form-label text-xs">Consultorio</label>
                                        <select name="horarios[{{ $key }}][tarde_consultorio_id]" class="form-select text-sm"
                                                x-model="tarde.consultorio_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($consultorios as $consultorio)
                                                <option value="{{ $consultorio->id }}"
                                                    {{ ($hTarde && $hTarde->consultorio_id == $consultorio->id) ? 'selected' : '' }}>
                                                    {{ $consultorio->nombre }} ({{ \Carbon\Carbon::parse($consultorio->horario_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($consultorio->horario_fin)->format('H:i') }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Especialidad</label>
                                        <select name="horarios[{{ $key }}][tarde_especialidad_id]" class="form-select text-sm"
                                                x-model="tarde.especialidad_id">
                                            <option value="">Seleccione...</option>
                                            @foreach($medico->especialidades as $especialidad)
                                                <option value="{{ $especialidad->id }}" 
                                                    x-show="isAllowed(tarde.consultorio_id, '{{ $especialidad->id }}')"
                                                    {{ ($hTarde && $hTarde->especialidad_id == $especialidad->id) ? 'selected' : '' }}>
                                                    {{ $especialidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Inicio</label>
                                        <p x-show="tarde.consultorio_id" class="text-xs text-gray-500 mb-1">
                                            Inicio turno tarde: 12:00
                                        </p>
                                        <input type="time" name="horarios[{{ $key }}][tarde_inicio]" class="input text-sm" 
                                            :min="getInputLimits('tarde').min" 
                                            :max="getInputLimits('tarde').max"
                                            x-model="tarde.inicio"
                                            @change="validateBounds('tarde')">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">Fin</label>
                                        <p x-show="tarde.consultorio_id" class="text-xs mb-1 transition-colors duration-200"
                                           :class="getStatusClass('tarde', 'fin')">
                                            <i class="bi bi-door-closed"></i> Consultorio cierra: <span x-text="getLimitText('tarde', 'fin')"></span>
                                        </p>
                                        <input type="time" name="horarios[{{ $key }}][tarde_fin]" class="input text-sm" 
                                            :min="getInputLimits('tarde').min" 
                                            :max="getInputLimits('tarde').max"
                                            x-model="tarde.fin"
                                            @change="validateBounds('tarde')">
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Resumen Semanal -->
            <div class="card p-6 sticky top-6">
                <h4 class="font-bold text-gray-900 mb-4">Resumen Semanal</h4>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Días activos</span>
                        <span class="font-bold text-medical-600">5 de 7</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Horas semanales</span>
                        <span class="font-bold text-gray-900">36 hrs</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Cupos máximos/día</span>
                        <span class="font-bold text-success-600">~16 citas</span>
                    </div>
                </div>

                <div class="bg-medical-50 rounded-xl p-4 mb-4">
                    <p class="text-xs text-medical-700 font-medium mb-2">💡 Consejo</p>
                    <p class="text-xs text-gray-600">
                        Configure descansos de 15-30 min cada 4 horas para mantener la calidad de atención.
                    </p>
                </div>

                <button type="button" class="btn btn-outline w-full text-sm">
                    <i class="bi bi-clipboard mr-2"></i>
                    Copiar de semana anterior
                </button>
            </div>

            <!-- Plantillas Rápidas -->
            <div class="card p-6">
                <h4 class="font-bold text-gray-900 mb-4">Plantillas Rápidas</h4>
                <div class="space-y-2">
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-clock mr-2"></i>
                        Jornada Completa (8-12, 2-6)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-sunrise mr-2"></i>
                        Solo Mañanas (8-12)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline w-full justify-start">
                        <i class="bi bi-sunset mr-2"></i>
                        Solo Tardes (2-6)
                    </button>
                </div>
            </div>

            <!-- Vista Previa Calendario -->
            <div class="card p-6 bg-gradient-to-br from-medical-50 to-info-50">
                <h4 class="font-bold text-gray-900 mb-4">Disponibilidad</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Lun-Mar</span>
                        <span class="text-xs badge badge-success">8 hrs/día</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Miércoles</span>
                        <span class="text-xs badge badge-gray">Cerrado</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Jueves</span>
                        <span class="text-xs badge badge-warning">4 hrs</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Viernes</span>
                        <span class="text-xs badge badge-success">8 hrs</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Fin de semana</span>
                        <span class="text-xs badge badge-gray">Cerrado</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
