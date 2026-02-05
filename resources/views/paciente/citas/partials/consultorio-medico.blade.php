<div class="card-premium rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-gray-700 animate-in fade-in slide-in-from-left duration-500 delay-100 relative overflow-hidden">
    
    <!-- Decorative background -->
    <div class="absolute -top-10 -right-10 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 shadow-sm">
                    <i class="bi bi-hospital text-xl"></i>
                </div>
                <div>
                    <span class="block text-sm font-normal text-slate-500 dark:text-gray-400">Paso 1.2</span>
                    Detalles Médicos
                </div>
            </h3>

            <!-- Toggle Switch -->
            <div class="bg-slate-100 dark:bg-gray-800 p-1.5 rounded-xl inline-flex relative w-full sm:w-auto">
                <div class="absolute h-[calc(100%-12px)] top-1.5 w-[calc(50%-6px)] bg-white dark:bg-gray-700 shadow-sm rounded-lg transition-all duration-300 ease-out" id="toggle-indicator"></div>
                
                <button type="button" onclick="setMode('especialidad')" class="relative z-10 flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg transition-colors duration-300 text-purple-600 dark:text-purple-400" id="btn-mode-specialty">
                    <i class="bi bi-heart-pulse mr-2"></i>Por Especialidad
                </button>
                <button type="button" onclick="setMode('consultorio')" class="relative z-10 flex-1 sm:flex-none px-6 py-2 text-sm font-medium rounded-lg transition-colors duration-300 text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200" id="btn-mode-consultory">
                    <i class="bi bi-geo-alt mr-2"></i>Por Sede
                </button>
            </div>
        </div>

        <!-- Hidden Inputs for Form Submission -->
        <input type="hidden" name="especialidad_id" id="final_especialidad_id">
        <input type="hidden" name="medico_id" id="final_medico_id">
        <input type="hidden" name="consultorio_id" id="final_consultorio_id">

        <!-- ========================================================================= -->
        <!-- MODE A: BY SPECIALTY -->
        <!-- ========================================================================= -->
        <div id="panel-specialty" class="flex flex-col gap-6 transition-opacity duration-300">
            
            <!-- STEP 1: Specialty -->
            <div id="step-spec-1" class="relative group space-y-4">
                <div class="flex items-center justify-between">
                     <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                        <i class="bi bi-heart-pulse text-purple-500"></i>
                        1. Seleccione Especialidad
                    </label>
                </div>
                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="ui_spec_specialty_grid">
                    @foreach($especialidades as $especialidad)
                        <div onclick="selectCard('spec_specialty', {{ $especialidad->id }}, this, '{{ $especialidad->nombre }}')" 
                             class="cursor-pointer p-4 rounded-xl border-2 border-slate-200 dark:border-gray-700 hover:border-purple-400 dark:hover:border-purple-500/50 bg-white/50 dark:bg-gray-800/50 transition-all duration-200 relative group/card flex items-center gap-4">
                            
                            <div class="h-10 w-10 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                                <i class="bi bi-heart-pulse"></i>
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1" title="{{ $especialidad->nombre }}">{{ $especialidad->nombre }}</h4>
                            </div>

                             <div class="opacity-0 group-hover/card:opacity-100 transition-opacity text-purple-500 selection-check">
                                <i class="bi bi-check-circle-fill text-xl"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
                 <input type="hidden" id="ui_spec_specialty" onchange="handleSpecialtyModeSpecialtyChange(this.value)">
            </div>
            
            <!-- Summary Header Step 1 -->
            <div id="summary-spec-1" class="hidden bg-purple-50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-900/30 rounded-xl p-3 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div>
                        <span class="text-xs text-purple-600 dark:text-purple-400 font-medium block">Especialidad</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white" id="summary-text-spec-1">...</span>
                    </div>
                </div>
                <button type="button" onclick="editStep('spec', 1)" class="text-xs font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 underline">
                    Cambiar
                </button>
            </div>

            <!-- STEP 2: Doctor (Dynamic) -->
            <div id="step-spec-2" class="relative group space-y-4 hidden animate-in fade-in slide-in-from-bottom-4">
                 <div class="flex items-center justify-between">
                    <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                        <i class="bi bi-person-workspace text-emerald-500"></i>
                        2. Seleccione Médico Especialista
                    </label>
                </div>
                <div id="ui_spec_doctor_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- JS populated -->
                </div>
                 <input type="hidden" id="ui_spec_doctor" onchange="handleSpecialtyModeDoctorChange(this.value)">
            </div>

             <!-- Summary Header Step 2 -->
             <div id="summary-spec-2" class="hidden bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-xl p-3 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium block">Médico</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white" id="summary-text-spec-2">...</span>
                    </div>
                </div>
                <button type="button" onclick="editStep('spec', 2)" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 underline">
                    Cambiar
                </button>
            </div>

            <!-- STEP 3: Consultory (Dynamic) -->
            <div id="step-spec-3" class="relative group space-y-4 hidden animate-in fade-in slide-in-from-bottom-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                        <i class="bi bi-geo-alt text-amber-500"></i>
                         3. Seleccione Sede
                    </label>
                </div>
                <div id="ui_spec_consultory_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- JS populated -->
                </div>
            </div>
             <!-- Summary Header Step 3 -->
             <div id="summary-spec-3" class="hidden bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-xl p-3 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <span class="text-xs text-amber-600 dark:text-amber-400 font-medium block">Sede</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white" id="summary-text-spec-3">...</span>
                    </div>
                </div>
                <button type="button" onclick="editStep('spec', 3)" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 underline">
                    Cambiar
                </button>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- MODE B: BY CONSULTORY -->
        <!-- ========================================================================= -->
        <div id="panel-consultory" class="flex flex-col gap-6 hidden transition-opacity duration-300 opacity-0">
            
            <!-- STEP 1: Consultory -->
            <div id="step-cons-1" class="relative group space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                        <i class="bi bi-geo-alt text-amber-500"></i>
                        1. Seleccione Sede
                    </label>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="ui_cons_consultory_grid">
                    @foreach($consultorios as $consultorio)
                        <div onclick="selectCard('cons_consultory', {{ $consultorio->id }}, this, '{{ $consultorio->nombre }}')" 
                             class="cursor-pointer p-4 rounded-xl border-2 border-slate-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500/50 bg-white/50 dark:bg-gray-800/50 transition-all duration-200 relative group/card flex flex-col gap-2">
                            
                            <div class="flex items-start justify-between">
                                <div class="h-10 w-10 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="opacity-0 group-hover/card:opacity-100 transition-opacity text-amber-500 selection-check">
                                    <i class="bi bi-check-circle-fill text-xl"></i>
                                </div>
                            </div>
                            
                             <div>
                                <h4 class="font-bold text-slate-800 dark:text-white text-sm mb-1 line-clamp-1" title="{{ $consultorio->nombre }}">{{ $consultorio->nombre }}</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-400 leading-tight flex items-center gap-1">
                                    <i class="bi bi-geo-alt-fill text-[10px]"></i>
                                    {{ $consultorio->ciudad->ciudad ?? 'Ciudad' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" id="ui_cons_consultory" onchange="handleConsultoryModeConsultoryChange(this.value)">
            </div>

            <!-- Summary Header Step 1 (Cons) -->
            <div id="summary-cons-1" class="hidden bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-xl p-3 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <span class="text-xs text-amber-600 dark:text-amber-400 font-medium block">Sede</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white" id="summary-text-cons-1">...</span>
                    </div>
                </div>
                <button type="button" onclick="editStep('cons', 1)" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 underline">
                    Cambiar
                </button>
            </div>

            <!-- STEP 2: Specialty (Dynamic) -->
            <div id="step-cons-2" class="relative group space-y-4 hidden animate-in fade-in slide-in-from-bottom-4">
                 <div class="flex items-center justify-between">
                     <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                        <i class="bi bi-heart-pulse text-purple-500"></i>
                        2. Seleccione Especialidad
                    </label>
                </div>
                <div id="ui_cons_specialty_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                     <!-- JS populated -->
                </div>
                <input type="hidden" id="ui_cons_specialty" onchange="handleConsultoryModeSpecialtyChange(this.value)">
            </div>
             <!-- Summary Header Step 2 (Cons) -->
             <div id="summary-cons-2" class="hidden bg-purple-50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-900/30 rounded-xl p-3 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div>
                        <span class="text-xs text-purple-600 dark:text-purple-400 font-medium block">Especialidad</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white" id="summary-text-cons-2">...</span>
                    </div>
                </div>
                <button type="button" onclick="editStep('cons', 2)" class="text-xs font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 underline">
                    Cambiar
                </button>
            </div>


             <!-- STEP 3: Doctor (Dynamic) -->
             <div id="step-cons-3" class="relative group space-y-4 hidden animate-in fade-in slide-in-from-bottom-4">
                <div class="flex items-center justify-between">
                     <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                        <i class="bi bi-person-workspace text-emerald-500"></i>
                        3. Seleccione Médico Especialista
                    </label>
                </div>
                <div id="ui_cons_doctor_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                     <!-- JS populated -->
                </div>
                <input type="hidden" id="ui_cons_doctor" onchange="handleConsultoryModeDoctorChange(this.value)">
            </div>
             <!-- Summary Header Step 3 (Cons) -->
             <div id="summary-cons-3" class="hidden bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-xl p-3 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium block">Médico</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-white" id="summary-text-cons-3">...</span>
                    </div>
                </div>
                <button type="button" onclick="editStep('cons', 3)" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 underline">
                    Cambiar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentMode = 'especialidad';

    function setMode(mode) {
        currentMode = mode;
        const indicator = document.getElementById('toggle-indicator');
        const btnSpec = document.getElementById('btn-mode-specialty');
        const btnCons = document.getElementById('btn-mode-consultory');
        const panelSpec = document.getElementById('panel-specialty');
        const panelCons = document.getElementById('panel-consultory');

        // Reset Inputs
        resetInputs();

        if (mode === 'especialidad') {
            // UI Toggle
            indicator.style.transform = 'translateX(0)';
            btnSpec.classList.replace('text-slate-500', 'text-purple-600');
            btnSpec.classList.remove('hover:text-slate-700');
            btnCons.classList.replace('text-purple-600', 'text-slate-500');
            btnCons.classList.add('hover:text-slate-700');
            
            // Panel Toggle
            panelCons.classList.add('opacity-0');
            setTimeout(() => {
                panelCons.classList.add('hidden');
                panelSpec.classList.remove('hidden');
                setTimeout(() => panelSpec.classList.remove('opacity-0'), 20);
            }, 300);
            
            // Show Step 1
            document.getElementById('step-spec-1').classList.remove('hidden');

        } else {
            // UI Toggle
            indicator.style.transform = 'translateX(100%)';
            btnCons.classList.replace('text-slate-500', 'text-purple-600');
            btnCons.classList.remove('hover:text-slate-700');
            btnSpec.classList.replace('text-purple-600', 'text-slate-500');
            btnSpec.classList.add('hover:text-slate-700');

            // Panel Toggle
            panelSpec.classList.add('opacity-0');
            setTimeout(() => {
                panelSpec.classList.add('hidden');
                panelCons.classList.remove('hidden');
                setTimeout(() => panelCons.classList.remove('opacity-0'), 20);
            }, 300);
             // Show Step 1
             document.getElementById('step-cons-1').classList.remove('hidden');
        }
    }

    // ==========================================
    // WIZARD SEQUENCER WITH ANIMATIONS
    // ==========================================
    function nextStep(mode, completedStep, text) {
        // 1. Update Summary Text
        document.getElementById(`summary-text-${mode}-${completedStep}`).textContent = text;
        
        // 2. Animate Hide Current Grid
        const stepDiv = document.getElementById(`step-${mode}-${completedStep}`);
        animateHide(stepDiv, () => {
             // 3. Show Summary Header (Fade In)
            const summaryDiv = document.getElementById(`summary-${mode}-${completedStep}`);
            animateShow(summaryDiv);

             // 4. Show Next Step (if not last)
            const next = completedStep + 1;
            const nextStepDiv = document.getElementById(`step-${mode}-${next}`);
            
            if(nextStepDiv) {
                animateShow(nextStepDiv);
            }
        });
    }

    function editStep(mode, stepToEdit) {
        // 1. Hide Step to Edit's Summary
        const summaryDiv = document.getElementById(`summary-${mode}-${stepToEdit}`);
        animateHide(summaryDiv, () => {
             // 2. Show Step to Edit's Grid
            const stepDiv = document.getElementById(`step-${mode}-${stepToEdit}`);
            animateShow(stepDiv);
        });

        // 3. Hide & RESET all subsequent Steps
        for(let i = stepToEdit + 1; i <= 3; i++) {
             const sDiv = document.getElementById(`step-${mode}-${i}`);
             if(sDiv && !sDiv.classList.contains('hidden')) {
                 animateHide(sDiv); // Just hide, no callback needed usually as we are resetting
             } else if(sDiv) {
                 sDiv.classList.add('hidden'); // Ensure hidden if not already
             }
             
             const sumDiv = document.getElementById(`summary-${mode}-${i}`);
             if(sumDiv && !sumDiv.classList.contains('hidden')) {
                 animateHide(sumDiv);
             } else if(sumDiv) {
                 sumDiv.classList.add('hidden');
             }
        }
        
        // 4. Clear Hidden Inputs downstream
        if (mode === 'spec') {
            if(stepToEdit === 1) {
                document.getElementById('final_medico_id').value = '';
                document.getElementById('final_consultorio_id').value = '';
            } else if (stepToEdit === 2) {
                 document.getElementById('final_consultorio_id').value = '';
            }
        } else if (mode === 'cons') {
             if(stepToEdit === 1) {
                document.getElementById('final_especialidad_id').value = '';
                document.getElementById('final_medico_id').value = '';
            } else if (stepToEdit === 2) {
                 document.getElementById('final_medico_id').value = '';
            }
        }
    }

    // Animation Helpers
    function animateHide(element, callback) {
        if(!element) return;
        
        // Prepare for exit
        element.classList.remove('animate-in', 'fade-in', 'slide-in-from-bottom-4', 'slide-in-from-top-2');
        element.classList.add('transition-all', 'duration-300', 'ease-in-out', 'opacity-0', '-translate-y-2');
        
        setTimeout(() => {
            element.classList.add('hidden');
            element.classList.remove('transition-all', 'duration-300', 'ease-in-out', 'opacity-0', '-translate-y-2');
            if(callback) callback();
        }, 300);
    }

    function animateShow(element) {
        if(!element) return;
        
        element.classList.remove('hidden');
        // Add Tailwind animate-in classes for smooth entry
        // We ensure opacity is 0 first to prevent flash
        element.classList.add('opacity-0');
        
        requestAnimationFrame(() => {
             element.classList.remove('opacity-0');
             element.classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-4', 'duration-500');
        });
    }

    function resetInputs() {
        // Clear Hiddens
        document.getElementById('final_especialidad_id').value = '';
        document.getElementById('final_medico_id').value = '';
        document.getElementById('final_consultorio_id').value = '';

        // Reset Visuals Spec
        forceShow('step-spec-1');
        forceHide('summary-spec-1');
        forceHide('step-spec-2');
        forceHide('summary-spec-2');
        forceHide('step-spec-3');
        forceHide('summary-spec-3');
        
        // Reset Visuals Cons
        forceShow('step-cons-1');
        forceHide('summary-cons-1');
        forceHide('step-cons-2');
        forceHide('summary-cons-2');
        forceHide('step-cons-3');
        forceHide('summary-cons-3');

        // Clear Grids
        document.getElementById('ui_spec_doctor_grid').innerHTML = '';
        document.getElementById('ui_spec_consultory_grid').innerHTML = '';
        document.getElementById('ui_cons_specialty_grid').innerHTML = '';
        document.getElementById('ui_cons_doctor_grid').innerHTML = '';
        
        // Clear selections
        clearGridSelection('ui_spec_specialty_grid');
        clearGridSelection('ui_cons_consultory_grid');
    }

    function forceShow(id) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.remove('hidden', 'opacity-0');
            el.classList.add('animate-in', 'fade-in');
        }
    }

    function forceHide(id) {
        const el = document.getElementById(id);
        if(el) el.classList.add('hidden');
    }

    // ==========================================
    // LOGIC: MODE SPECIALTY
    // ==========================================
    function handleSpecialtyModeSpecialtyChange(specId) {
        document.getElementById('final_especialidad_id').value = specId;
        
        fetch(`${BASE_URL}/ajax/citas/medicos?especialidad_id=${specId}`)
            .then(response => response.json())
            .then(data => {
                renderGrid('ui_spec_doctor_grid', data, 'spec_doctor');
            });
    }

    function handleSpecialtyModeDoctorChange(medicoId) {
        document.getElementById('final_medico_id').value = medicoId;
        const specId = document.getElementById('final_especialidad_id').value;

        fetch(`${BASE_URL}/ajax/citas/consultorios-por-especialidad/${specId}?medico_id=${medicoId}`)
            .then(response => response.json())
            .then(data => {
                renderGrid('ui_spec_consultory_grid', data, 'spec_consultory');
            });
    }

    // ==========================================
    // LOGIC: MODE CONSULTORY
    // ==========================================
    function handleConsultoryModeConsultoryChange(consId) {
        document.getElementById('final_consultorio_id').value = consId;
        
        fetch(`${BASE_URL}/ajax/citas/especialidades-por-consultorio/${consId}`)
            .then(response => response.json())
            .then(data => {
                renderGrid('ui_cons_specialty_grid', data, 'cons_specialty');
            });
    }

    function handleConsultoryModeSpecialtyChange(specId) {
        document.getElementById('final_especialidad_id').value = specId;
        const consId = document.getElementById('final_consultorio_id').value;

        fetch(`${BASE_URL}/ajax/citas/medicos?especialidad_id=${specId}&consultorio_id=${consId}`)
            .then(response => response.json())
            .then(data => {
                renderGrid('ui_cons_doctor_grid', data, 'cons_doctor');
            });
    }

    function handleConsultoryModeDoctorChange(medicoId) {
        document.getElementById('final_medico_id').value = medicoId;
    }


    // ==========================================
    // GLOBAL CARD RENDERER & SELECTION
    // ==========================================
    
    function renderGrid(containerId, data, type) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        if (data.length === 0) {
            container.innerHTML = '<p class="col-span-full text-slate-400 text-sm py-4 text-center border border-dashed border-slate-200 rounded-xl">No hay opciones disponibles.</p>';
            return;
        }

        data.forEach(item => {
            const isSelected = data.length === 1; // Auto select if only one
            const activeClass = isSelected ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20 ring-2 ring-purple-200' : 'border-slate-200 dark:border-gray-700 hover:border-purple-400';
            const colorClass = (type.includes('consultory') || type.includes('cons_')) ? 'amber' : (type.includes('doctor') ? 'emerald' : 'purple');
            
            let finalActiveClass = activeClass;
            if (colorClass === 'amber') finalActiveClass = finalActiveClass.replace(/purple/g, 'amber');
            if (colorClass === 'emerald') finalActiveClass = finalActiveClass.replace(/purple/g, 'emerald');

            let icon = 'bi-circle';
            let title = item.nombre || item.pregunta || 'Item';
            let subtitle = '';

            if (type.includes('doctor')) {
                icon = 'bi-person-workspace';
                title = item.nombre || (item.primer_nombre ? `Dr. ${item.primer_nombre} ${item.primer_apellido}` : 'Médico');
                if (item.tarifa) subtitle = `Consulta: $${item.tarifa}`;
            } else if (type.includes('consultory')) {
                icon = 'bi-building';
                subtitle = item.direccion_detallada || '';
            } else if (type.includes('specialty')) {
                icon = 'bi-heart-pulse';
            }

            // If auto-select, trigger logic but NOT full visual transition immediately if we want user to see it? 
            // Better to just let user select for wizard flow, unless 1 option.
            // For wizard flow, auto-advance might be disorienting if invisible.
            // Let's keep manual selection for wizard unless explicitly forced. 
            // ACTUALLY: For wizard, if there is only 1 option, we might want to allow user to click it to confirm.
            
            if (isSelected) {
                 if (type.includes('doctor')) handleAutoSelect(type, item.id);
                 else if (type.includes('consultory')) setFinalConsultory(item.id);
                 // Note: We don't auto-advance visually here to avoid jumping steps too fast.
            }

            const html = `
                <div onclick="selectCard('${type}', ${item.id}, this, '${title.replace(/'/g, "\\'")}')" 
                     class="cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 relative group/card flex items-start gap-3 ${finalActiveClass}">
                    
                    <div class="h-10 w-10 rounded-lg bg-${colorClass}-100 dark:bg-${colorClass}-900/50 flex items-center justify-center text-${colorClass}-600 dark:text-${colorClass}-400 shrink-0">
                        <i class="bi ${icon}"></i>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm line-clamp-1" title="${title}">${title}</h4>
                        ${subtitle ? `<p class="text-xs text-slate-500 dark:text-gray-400 line-clamp-1">${subtitle}</p>` : ''}
                    </div>

                    <div class="absolute top-3 right-3 ${isSelected ? 'opacity-100' : 'opacity-0'} group-hover/card:opacity-100 transition-opacity text-${colorClass}-500 selection-check">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    function selectCard(type, id, el, textLabel) {
        // Visual Selection in Grid (Short lived, as it will hide)
        const grid = el.parentElement;
        const colorClass = (type.includes('consultory') || type.includes('cons_')) ? 'amber' : (type.includes('doctor') ? 'emerald' : 'purple');

        Array.from(grid.children).forEach(card => {
            card.className = `cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 relative group/card flex items-start gap-3 border-slate-200 dark:border-gray-700 hover:border-${colorClass}-400 bg-white/50 dark:bg-gray-800/50`;
            const check = card.querySelector('.selection-check');
            if(check) check.classList.replace('opacity-100', 'opacity-0');
        });

        el.className = `cursor-pointer p-4 rounded-xl border-2 transition-all duration-200 relative group/card flex items-start gap-3 border-${colorClass}-500 bg-${colorClass}-50 dark:bg-${colorClass}-900/20 ring-2 ring-${colorClass}-200`;
        const check = el.querySelector('.selection-check');
        if(check) check.classList.replace('opacity-0', 'opacity-100');


        // ADVANCE WIZARD
        // Map types to Steps
        if (type === 'spec_specialty') {
            document.getElementById('ui_spec_specialty').value = id;
            handleSpecialtyModeSpecialtyChange(id);
            nextStep('spec', 1, textLabel);
        } else if (type === 'spec_doctor') {
            document.getElementById('ui_spec_doctor').value = id;
            handleSpecialtyModeDoctorChange(id);
            nextStep('spec', 2, textLabel);
        } else if (type === 'spec_consultory') {
            setFinalConsultory(id);
            // End of Line for Mode A (Visual confirmation?)
            nextStep('spec', 3, textLabel); // Shows summary, but no step 4
        } else if (type === 'cons_consultory') {
            document.getElementById('ui_cons_consultory').value = id;
            handleConsultoryModeConsultoryChange(id);
            nextStep('cons', 1, textLabel);
        } else if (type === 'cons_specialty') {
            document.getElementById('ui_cons_specialty').value = id;
            handleConsultoryModeSpecialtyChange(id);
            nextStep('cons', 2, textLabel);
        } else if (type === 'cons_doctor') {
            document.getElementById('ui_cons_doctor').value = id;
            handleConsultoryModeDoctorChange(id);
            nextStep('cons', 3, textLabel);
        }
    }

    // Helper to clear visually
    function clearGridSelection(gridId) {
        const grid = document.getElementById(gridId);
        if(!grid) return;
        Array.from(grid.children).forEach(card => {
            card.classList.remove('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20', 'ring-2', 'ring-purple-200', 'border-amber-500', 'bg-amber-50', 'ring-amber-200', 'border-emerald-500', 'bg-emerald-50', 'ring-emerald-200');
            card.classList.add('border-slate-200', 'dark:border-gray-700');
            const check = card.querySelector('.selection-check');
            if(check) check.classList.replace('opacity-100', 'opacity-0');
        });
    }

    function handleAutoSelect(type, id) {
         if (type === 'spec_doctor') {
            document.getElementById('ui_spec_doctor').value = id;
            handleSpecialtyModeDoctorChange(id);
        } else if (type === 'cons_doctor') {
             document.getElementById('ui_cons_doctor').value = id;
             handleConsultoryModeDoctorChange(id);
        }
    }

    function setFinalConsultory(id) {
        document.getElementById('final_consultorio_id').value = id;
    }
</script>
@endpush
