@extends('layouts.medico')

@section('title', 'Horarios del Médico')

@section('content')
<script>
    // 1. Data Sources (Global) - Ejecutado inmediatamente para asegurar disponibilidad
    window.rawHorarios = @json($consultorios->mapWithKeys(function($c) {
        return [$c->id => [
            'inicio' => \Carbon\Carbon::parse($c->horario_inicio)->format('H:i'), 
            'fin' => \Carbon\Carbon::parse($c->horario_fin)->format('H:i')
        ]];
    }));

    // FULL CATALOGS FOR UI (Injected from Controller)
    window.catalogs = {
        especialidades: @json($medico->especialidades),
        consultorios: @json($consultorios)
    };

    // Data de Reglas (Especialidades -> Consultorios)
    // Mapea ID Especialidad => [ID Consultorio, ID Consultorio, ...]
    window.especialidadRules = @json($consultorios->flatMap(function($c) {
        return $c->especialidades->map(function($e) use ($c) {
            return ['e_id' => $e->id, 'c_id' => $c->id];
        });
    })->groupBy('e_id')->map(function($items) {
        return $items->pluck('c_id')->unique()->values();
    }));

    // Helper seguro para obtener horarios
    window.getConsultorioData = function(id) {
        if (!id) return null;
        
        const result = window.rawHorarios[id] || window.rawHorarios[String(id)] || window.rawHorarios[parseInt(id)];
        return result;
    };

    // 2. Component Logic Factory - Mismo patrón que vista de Admin
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
                step: 'summary' // summary, specialty, consultory
            },
            tarde: {
                active: !!data.tarde.active,
                consultorio_id: data.tarde.consultorio_id ? String(data.tarde.consultorio_id) : '',
                especialidad_id: data.tarde.especialidad_id ? String(data.tarde.especialidad_id) : '',
                inicio: data.tarde.inicio || '14:00',
                fin: data.tarde.fin || '18:00',
                step: 'summary' // summary, specialty, consultory
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
                
                // Observadores para corrección automática de horas
                this.$watch('manana.consultorio_id', () => this.validateBounds('manana'));
                this.$watch('tarde.consultorio_id', () => this.validateBounds('tarde'));

                // Observadores para reset de consultorio al cambiar especialidad
                this.$watch('manana.especialidad_id', (val) => {
                    if (this.manana.consultorio_id && !this.isConsultorioAllowed(val, this.manana.consultorio_id)) {
                        this.manana.consultorio_id = '';
                    }
                });
                this.$watch('tarde.especialidad_id', (val) => {
                    if (this.tarde.consultorio_id && !this.isConsultorioAllowed(val, this.tarde.consultorio_id)) {
                        this.tarde.consultorio_id = '';
                    }
                });
            },

            // --- WIZARD ACTIONS ---
            
            // Iniciar edición de una selección
            startSelection(shift, type) {
                // type: 'specialty' or 'consultory'
                this[shift].step = type;
            },

            // Seleccionar item y limpiar paso
            selectItem(shift, type, id) {
                if (type === 'specialty') {
                    this[shift].especialidad_id = String(id);
                    // Auto-advance logic: if consultory is empty or invalid, go to consultory
                    if (!this[shift].consultorio_id || !this.isConsultorioAllowed(id, this[shift].consultorio_id)) {
                         this[shift].consultorio_id = '';
                         this[shift].step = 'consultory';
                    } else {
                        this[shift].step = 'summary';
                    }
                } else if (type === 'consultory') {
                    this[shift].consultorio_id = String(id);
                    this[shift].step = 'summary';
                }
            },

            cancelSelection(shift) {
                this[shift].step = 'summary';
            },

            // --- DATA HELPERS ---
            
            getSpecialtyName(id) {
                if (!id) return 'Seleccionar Especialidad';
                const s = window.catalogs.especialidades.find(x => x.id == id);
                return s ? s.nombre : 'Desconocida';
            },

            getConsultoryName(id) {
                if (!id) return 'Seleccionar Consultorio';
                const c = window.catalogs.consultorios.find(x => x.id == id);
                return c ? c.nombre : 'Desconocido';
            },
            
            getConsultoryDetails(id) {
                 if (!id) return '';
                 const c = window.catalogs.consultorios.find(x => x.id == id);
                 return c ? `${c.direccion || ''} (${c.horario_inicio}-${c.horario_fin})` : '';
            },

            // Obtener lista filtrada para el grid
            getAvailableOptions(shift, type) {
                if (type === 'specialty') {
                    return window.catalogs.especialidades;
                } else if (type === 'consultory') {
                    const specId = this[shift].especialidad_id;
                    if (!specId) return window.catalogs.consultorios; // Should ideally limit, but ok
                    
                    // Filter using rules
                    const allowedIds = window.especialidadRules[specId] || [];
                    return window.catalogs.consultorios.filter(c => allowedIds.includes(c.id));
                }
                return [];
            },


            // Lógica de validación visual (Retorna clases CSS)
            getStatusClass(shift, tipo) {
                const cId = this[shift].consultorio_id;
                const cData = window.getConsultorioData(cId);
                if (!cData) return 'text-gray-500'; // Sin consultorio seleccionado

                const horaUsuario = this[shift][tipo]; // 'inicio' o 'fin'

                if (tipo === 'inicio') {
                    if (horaUsuario < cData.inicio) return 'text-red-600 font-bold';
                }
                if (tipo === 'fin') {
                    if (horaUsuario > cData.fin) return 'text-red-600 font-bold';
                }

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
                    
                    // 1. Determinar límites teóricos del turno
                    let minLimit = (shift === 'manana') ? '00:00' : '12:00';
                    let maxLimit = (shift === 'manana') ? '12:00' : '23:59';

                    // 2. Intersectar con límites del consultorio (si existe)
                    if (cData) {
                        if (cData.inicio > minLimit) minLimit = cData.inicio;
                        if (cData.fin < maxLimit) maxLimit = cData.fin;
                    }

                    // Caso Borde: Consultorio incompatible con turno (ej. abre tarde para turno mañana)
                    if (minLimit > maxLimit) {
                        // Ajustar ambos al límite lógico más cercano para evitar inconsistencias
                        // El usuario verá que no puede expandir el rango
                        this[shift].inicio = maxLimit;
                        this[shift].fin = maxLimit;
                        return;
                    }

                    // 3. Aplicar correcciones estricas
                    
                    // Inicio no puede ser menor al mínimo permitido
                    if (this[shift].inicio < minLimit) this[shift].inicio = minLimit;
                    // Inicio no puede ser mayor al máximo permitido
                    if (this[shift].inicio > maxLimit) this[shift].inicio = maxLimit;

                    // Fin no puede ser mayor al máximo permitido
                    if (this[shift].fin > maxLimit) this[shift].fin = maxLimit;
                    // Fin no puede ser menor al mínimo permitido
                    if (this[shift].fin < minLimit) this[shift].fin = minLimit;

                    // 4. Coherencia Temporal: Inicio <= Fin
                    if (this[shift].inicio > this[shift].fin) {
                        this[shift].fin = this[shift].inicio;
                    }
                });
            },
            
            // Filtro de consultorios por especialidad
            isConsultorioAllowed(especialidadId, consultorioId) {
                if (!especialidadId) return true; // Si no hay especialidad, mostrar todos
                if (!consultorioId) return true;
                
                const allowed = window.especialidadRules[especialidadId] || window.especialidadRules[String(especialidadId)];
                if (!allowed) return false;
                
                return allowed.includes(parseInt(consultorioId));
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
                     x-data='makeScheduleCard(@json($initData))'>
                     
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
                    
                    <!-- Hidden Inputs for Form Submission (Bound to Alpine State) -->
                    <!-- Estos inputs son cruciales ya que los selects visuales ahora son divs -->
                    
                    <!-- Turno Mañana -->
                    <input type="hidden" name="horarios[{{ $key }}][manana_consultorio_id]" x-bind:value="manana.consultorio_id">
                    <input type="hidden" name="horarios[{{ $key }}][manana_especialidad_id]" x-bind:value="manana.especialidad_id">
                    
                    <!-- Turno Tarde -->
                    <input type="hidden" name="horarios[{{ $key }}][tarde_consultorio_id]" x-bind:value="tarde.consultorio_id">
                    <input type="hidden" name="horarios[{{ $key }}][tarde_especialidad_id]" x-bind:value="tarde.especialidad_id">

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
                        <div x-show="!editing && active" x-cloak class="space-y-4">
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
                        <div x-show="editing" x-cloak x-transition class="space-y-6">
                            
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

                                <!-- Contenedor Principal Mañana -->
                                <div x-show="manana_active" x-cloak class="mt-4 animate-in fade-in slide-in-from-top-4 duration-500">
                                    
                                    <!-- STEP: SUMMARY (Default View in Edit Mode) -->
                                    <div x-show="manana.step === 'summary'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            
                                            <!-- Card: Especialidad -->
                                            <div @click="startSelection('manana', 'specialty')" 
                                                 class="cursor-pointer group relative overflow-hidden rounded-2xl border-2 border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 hover:border-blue-500 dark:hover:border-blue-400 transition-all shadow-sm hover:shadow-md">
                                                <div class="flex items-center gap-4">
                                                    <div class="h-12 w-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                                        <i class="bi bi-heart-pulse"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Especialidad</p>
                                                        <h4 class="font-bold text-gray-900 dark:text-gray-100" x-text="getSpecialtyName(manana.especialidad_id)"></h4>
                                                    </div>
                                                    <div class="ml-auto text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card: Consultorio -->
                                            <div @click="startSelection('manana', 'consultory')" 
                                                 class="cursor-pointer group relative overflow-hidden rounded-2xl border-2 border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 hover:border-amber-500 dark:hover:border-amber-400 transition-all shadow-sm hover:shadow-md">
                                                <div class="flex items-center gap-4">
                                                    <div class="h-12 w-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Consultorio</p>
                                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 truncate" x-text="getConsultoryName(manana.consultorio_id)"></h4>
                                                        <p class="text-xs text-gray-400 truncate" x-text="getConsultoryDetails(manana.consultorio_id)"></p>
                                                    </div>
                                                    <div class="ml-auto text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Time Inputs (Premium Style) -->
                                            <div class="md:col-span-2 grid grid-cols-2 gap-4 mt-2">
                                                <div class="relative group">
                                                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-bold text-blue-600 z-10">Inicio</label>
                                                    <div class="relative flex items-center">
                                                        <div class="absolute left-3 text-blue-400"><i class="bi bi-clock"></i></div>
                                                        <input type="time" name="horarios[{{ $key }}][manana_inicio]" 
                                                            class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:border-blue-500 focus:ring-0 font-bold text-gray-700"
                                                            :min="getInputLimits('manana').min" 
                                                            :max="getInputLimits('manana').max"
                                                            x-model="manana.inicio"
                                                            @change="validateBounds('manana')">
                                                    </div>
                                                    <p x-show="manana.consultorio_id" class="text-[10px] mt-1 text-gray-400 text-right">
                                                        Abre: <span x-text="getLimitText('manana', 'inicio')"></span>
                                                    </p>
                                                </div>

                                                <div class="relative group">
                                                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-bold text-blue-600 z-10">Fin</label>
                                                    <div class="relative flex items-center">
                                                        <div class="absolute left-3 text-blue-400"><i class="bi bi-clock-history"></i></div>
                                                        <input type="time" name="horarios[{{ $key }}][manana_fin]" 
                                                            class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:border-blue-500 focus:ring-0 font-bold text-gray-700"
                                                            :min="getInputLimits('manana').min" 
                                                            :max="getInputLimits('manana').max"
                                                            x-model="manana.fin"
                                                            @change="validateBounds('manana')">
                                                    </div>
                                                    <p x-show="manana.consultorio_id" class="text-[10px] mt-1 text-gray-400 text-right">
                                                        Corta: 12:00
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP: SPECIALTY SELECTION GRID -->
                                    <div x-show="manana.step === 'specialty'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                                <i class="bi bi-heart-pulse text-blue-500"></i> Seleccione Especialidad
                                            </h4>
                                            <button type="button" @click="cancelSelection('manana')" class="text-xs text-gray-500 hover:text-gray-800 underline">Cancelar</button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                                            <template x-for="item in getAvailableOptions('manana', 'specialty')" :key="item.id">
                                                <div @click="selectItem('manana', 'specialty', item.id)"
                                                     class="cursor-pointer p-3 rounded-xl border-2 bg-white flex items-center gap-3 transition-all hover:scale-[1.02]"
                                                     :class="manana.especialidad_id == item.id ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-100 hover:border-blue-300'">
                                                    <div class="h-8 w-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                                        <i class="bi bi-heart-pulse"></i>
                                                    </div>
                                                    <span class="text-sm font-bold text-gray-700" x-text="item.nombre"></span>
                                                    <div x-show="manana.especialidad_id == item.id" class="ml-auto text-blue-500">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- STEP: CONSULTORY SELECTION GRID -->
                                    <div x-show="manana.step === 'consultory'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                                <i class="bi bi-building text-amber-500"></i> Seleccione Consultorio
                                            </h4>
                                            <button type="button" @click="cancelSelection('manana')" class="text-xs text-gray-500 hover:text-gray-800 underline">Cancelar</button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                                            <template x-for="item in getAvailableOptions('manana', 'consultory')" :key="item.id">
                                                <div @click="selectItem('manana', 'consultory', item.id)"
                                                     class="cursor-pointer p-3 rounded-xl border-2 bg-white flex items-start gap-3 transition-all hover:scale-[1.02]"
                                                     :class="manana.consultorio_id == item.id ? 'border-amber-500 ring-2 ring-amber-100' : 'border-slate-100 hover:border-amber-300'">
                                                    <div class="h-8 w-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="block text-sm font-bold text-gray-700 truncate" x-text="item.nombre"></span>
                                                        <span class="block text-xs text-gray-500 truncate" x-text="item.direccion"></span>
                                                    </div>
                                                    <div x-show="manana.consultorio_id == item.id" class="ml-auto text-amber-500">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="getAvailableOptions('manana', 'consultory').length === 0" class="col-span-full py-4 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-xl">
                                                No hay consultorios disponibles para esta especialidad.
                                            </div>
                                        </div>
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
                                
                                <!-- Contenedor Principal Tarde -->
                                <div x-show="tarde_active" x-cloak class="mt-4 animate-in fade-in slide-in-from-top-4 duration-500">
                                    
                                    <!-- STEP: SUMMARY (Default View in Edit Mode) -->
                                    <div x-show="tarde.step === 'summary'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            
                                            <!-- Card: Especialidad -->
                                            <div @click="startSelection('tarde', 'specialty')" 
                                                 class="cursor-pointer group relative overflow-hidden rounded-2xl border-2 border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 hover:border-purple-500 dark:hover:border-purple-400 transition-all shadow-sm hover:shadow-md">
                                                <div class="flex items-center gap-4">
                                                    <div class="h-12 w-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                                        <i class="bi bi-heart-pulse"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Especialidad</p>
                                                        <h4 class="font-bold text-gray-900 dark:text-gray-100" x-text="getSpecialtyName(tarde.especialidad_id)"></h4>
                                                    </div>
                                                    <div class="ml-auto text-purple-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card: Consultorio -->
                                            <div @click="startSelection('tarde', 'consultory')" 
                                                 class="cursor-pointer group relative overflow-hidden rounded-2xl border-2 border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 hover:border-amber-500 dark:hover:border-amber-400 transition-all shadow-sm hover:shadow-md">
                                                <div class="flex items-center gap-4">
                                                    <div class="h-12 w-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                                                        <i class="bi bi-building"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Consultorio</p>
                                                        <h4 class="font-bold text-gray-900 dark:text-gray-100 truncate" x-text="getConsultoryName(tarde.consultorio_id)"></h4>
                                                        <p class="text-xs text-gray-400 truncate" x-text="getConsultoryDetails(tarde.consultorio_id)"></p>
                                                    </div>
                                                    <div class="ml-auto text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Time Inputs (Premium Style) -->
                                            <div class="md:col-span-2 grid grid-cols-2 gap-4 mt-2">
                                                <div class="relative group">
                                                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-bold text-orange-600 z-10">Inicio</label>
                                                    <div class="relative flex items-center">
                                                        <div class="absolute left-3 text-orange-400"><i class="bi bi-clock"></i></div>
                                                        <input type="time" name="horarios[{{ $key }}][tarde_inicio]" 
                                                            class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:border-orange-500 focus:ring-0 font-bold text-gray-700"
                                                            :min="getInputLimits('tarde').min" 
                                                            :max="getInputLimits('tarde').max"
                                                            x-model="tarde.inicio"
                                                            @change="validateBounds('tarde')">
                                                    </div>
                                                    <p x-show="tarde.consultorio_id" class="text-[10px] mt-1 text-gray-400 text-right">
                                                        Inicio Tarde: 12:00
                                                    </p>
                                                </div>

                                                <div class="relative group">
                                                    <label class="absolute -top-2 left-3 bg-white px-1 text-xs font-bold text-orange-600 z-10">Fin</label>
                                                    <div class="relative flex items-center">
                                                        <div class="absolute left-3 text-orange-400"><i class="bi bi-door-closed"></i></div>
                                                        <input type="time" name="horarios[{{ $key }}][tarde_fin]" 
                                                            class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-slate-100 focus:border-orange-500 focus:ring-0 font-bold text-gray-700"
                                                            :min="getInputLimits('tarde').min" 
                                                            :max="getInputLimits('tarde').max"
                                                            x-model="tarde.fin"
                                                            @change="validateBounds('tarde')">
                                                    </div>
                                                    <p x-show="tarde.consultorio_id" class="text-[10px] mt-1 transition-colors duration-200 text-right"
                                                       :class="getStatusClass('tarde', 'fin')">
                                                        Cierra: <span x-text="getLimitText('tarde', 'fin')"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP: SPECIALTY SELECTION GRID -->
                                    <div x-show="tarde.step === 'specialty'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                                <i class="bi bi-heart-pulse text-purple-500"></i> Seleccione Especialidad
                                            </h4>
                                            <button type="button" @click="cancelSelection('tarde')" class="text-xs text-gray-500 hover:text-gray-800 underline">Cancelar</button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                                            <template x-for="item in getAvailableOptions('tarde', 'specialty')" :key="item.id">
                                                <div @click="selectItem('tarde', 'specialty', item.id)"
                                                     class="cursor-pointer p-3 rounded-xl border-2 bg-white flex items-center gap-3 transition-all hover:scale-[1.02]"
                                                     :class="tarde.especialidad_id == item.id ? 'border-purple-500 ring-2 ring-purple-100' : 'border-slate-100 hover:border-purple-300'">
                                                    <div class="h-8 w-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                                        <i class="bi bi-heart-pulse"></i>
                                                    </div>
                                                    <span class="text-sm font-bold text-gray-700" x-text="item.nombre"></span>
                                                    <div x-show="tarde.especialidad_id == item.id" class="ml-auto text-purple-500">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- STEP: CONSULTORY SELECTION GRID -->
                                    <div x-show="tarde.step === 'consultory'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-bold text-gray-800 flex items-center gap-2">
                                                <i class="bi bi-building text-amber-500"></i> Seleccione Consultorio
                                            </h4>
                                            <button type="button" @click="cancelSelection('tarde')" class="text-xs text-gray-500 hover:text-gray-800 underline">Cancelar</button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                                            <template x-for="item in getAvailableOptions('tarde', 'consultory')" :key="item.id">
                                                <div @click="selectItem('tarde', 'consultory', item.id)"
                                                     class="cursor-pointer p-3 rounded-xl border-2 bg-white flex items-start gap-3 transition-all hover:scale-[1.02]"
                                                     :class="tarde.consultorio_id == item.id ? 'border-amber-500 ring-2 ring-amber-100' : 'border-slate-100 hover:border-amber-300'">
                                                    <div class="h-8 w-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                                        <i class="bi bi-geo-alt"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="block text-sm font-bold text-gray-700 truncate" x-text="item.nombre"></span>
                                                        <span class="block text-xs text-gray-500 truncate" x-text="item.direccion"></span>
                                                    </div>
                                                    <div x-show="tarde.consultorio_id == item.id" class="ml-auto text-amber-500">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </div>
                                                </div>
                                            </template>
                                            <div x-show="getAvailableOptions('tarde', 'consultory').length === 0" class="col-span-full py-4 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-xl">
                                                No hay consultorios disponibles para esta especialidad.
                                            </div>
                                        </div>
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
