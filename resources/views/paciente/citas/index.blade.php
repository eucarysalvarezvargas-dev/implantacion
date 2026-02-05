@extends('layouts.paciente')

@section('title', 'Mis Citas')

@section('content')
<div class="space-y-8 relative">
    
    <!-- Premium Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-white to-slate-50 dark:from-gray-800 dark:to-gray-900 border border-slate-200 dark:border-gray-700 shadow-xl">
        <!-- Background Effects -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-medical-500/10 dark:bg-medical-400/10 blur-[80px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 dark:bg-blue-400/10 blur-[60px] rounded-full pointer-events-none"></div>
        
        <div class="relative z-10 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="relative group">
                    <div class="absolute inset-0 bg-medical-500/20 dark:bg-medical-400/20 blur-xl rounded-full group-hover:blur-2xl transition-all duration-500"></div>
                    <div class="relative h-16 w-16 md:h-20 md:w-20 rounded-2xl bg-gradient-to-br from-medical-500 to-medical-600 flex items-center justify-center text-white shadow-lg shadow-medical-200 dark:shadow-none transform group-hover:scale-105 transition-all duration-300">
                        <i class="bi bi-calendar2-check text-3xl md:text-4xl animate-pulse-slow"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Mis Citas</h1>
                    <p class="text-slate-500 dark:text-gray-400 font-medium mt-1">Agenda y gestiona tus consultas médicas</p>
                </div>
            </div>
            
            <a href="{{ route('paciente.citas.create') }}" class="btn-primary-dynamic group flex items-center gap-3 px-6 py-3.5 rounded-xl font-bold shadow-lg shadow-medical-200/50 dark:shadow-none hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20 text-white group-hover:scale-110 transition-transform">
                    <i class="bi bi-plus-lg text-lg"></i>
                </div>
                <span>Nueva Cita</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Próximas -->
        <div class="card-premium relative overflow-hidden rounded-3xl p-6 border border-white/40 dark:border-gray-700/50 min-h-[140px] flex items-center justify-between group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent dark:from-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1">Próximas</p>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">
                    {{ ($citas ?? collect())->filter(fn($c) => in_array($c->estado_cita, ['Programada', 'Confirmada']))->count() }}
                </h3>
            </div>
            <div class="h-16 w-16 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>

        <!-- Completadas -->
        <div class="card-premium relative overflow-hidden rounded-3xl p-6 border border-white/40 dark:border-gray-700/50 min-h-[140px] flex items-center justify-between group">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 to-transparent dark:from-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1">Completadas</p>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">
                    {{ ($citas ?? collect())->where('estado_cita', 'Completada')->count() }}
                </h3>
            </div>
            <div class="h-16 w-16 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <!-- Canceladas -->
        <div class="card-premium relative overflow-hidden rounded-3xl p-6 border border-white/40 dark:border-gray-700/50 min-h-[140px] flex items-center justify-between group">
            <div class="absolute inset-0 bg-gradient-to-br from-rose-50/50 to-transparent dark:from-rose-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div>
                <p class="text-sm font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1">Canceladas</p>
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">
                    {{ ($citas ?? collect())->filter(fn($c) => in_array($c->estado_cita, ['Cancelada', 'No Asistió']))->count() }}
                </h3>
            </div>
            <div class="h-16 w-16 rounded-2xl bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform duration-300">
                <i class="bi bi-x-circle"></i>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    @if(isset($pacientesEspeciales) && $pacientesEspeciales->count() > 0)
    <div class="card-premium rounded-2xl p-4 border border-slate-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-md">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase mb-1.5 block ml-1">Tipo de Cita</label>
                <div class="relative">
                    <select id="filtro-tipo" class="w-full pl-4 pr-10 py-2.5 rounded-xl border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-slate-700 dark:text-gray-200 text-sm font-bold shadow-sm focus:border-medical-500 focus:ring-medical-500 cursor-pointer appearance-none transition-colors" onchange="filtrarCitas()">
                        <option value="todas">Todas las citas</option>
                        <option value="propia">Solo citas propias</option>
                        <option value="terceros">Solo citas para terceros</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-3 text-slate-400 pointer-events-none text-xs"></i>
                </div>
            </div>
            
            <div id="filtro-paciente-container" class="flex-1 min-w-[200px] hidden animate-in fade-in slide-in-from-left-2 duration-300">
                <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase mb-1.5 block ml-1">Paciente Especial</label>
                <div class="relative">
                    <select id="filtro-paciente" class="w-full pl-4 pr-10 py-2.5 rounded-xl border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-slate-700 dark:text-gray-200 text-sm font-bold shadow-sm focus:border-medical-500 focus:ring-medical-500 cursor-pointer appearance-none transition-colors" onchange="filtrarCitas()">
                        <option value="">Todos los pacientes</option>
                        @foreach($pacientesEspeciales ?? [] as $pe)
                        <option value="{{ $pe->id }}">{{ $pe->primer_nombre }} {{ $pe->primer_apellido }}</option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-3 text-slate-400 pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Area with Tabs -->
    <div class="rounded-3xl bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 shadow-xl overflow-hidden">
        <!-- Tabs Header -->
        <div class="border-b border-slate-100 dark:border-gray-700 bg-slate-50/50 dark:bg-gray-800/50 px-6 pt-6">
            <div class="flex flex-wrap gap-6">
                <button class="tab-button active group relative pb-4 px-2 text-sm font-bold text-medical-600 dark:text-medical-400 transition-colors" data-tab="proximas">
                    <span class="flex items-center gap-2 relative z-10">
                        <i class="bi bi-calendar-check text-lg"></i>
                        Próximas
                    </span>
                    <div class="absolute bottom-0 left-0 h-0.5 w-full bg-medical-500 transform scale-x-100 transition-transform duration-300 origin-left"></div>
                </button>

                <button class="tab-button group relative pb-4 px-2 text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-200 transition-colors" data-tab="realizadas">
                    <span class="flex items-center gap-2 relative z-10">
                        <i class="bi bi-clock-history text-lg"></i>
                        Realizadas
                    </span>
                    <div class="absolute bottom-0 left-0 h-0.5 w-full bg-slate-300 dark:bg-gray-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </button>

                <button class="tab-button group relative pb-4 px-2 text-sm font-bold text-slate-500 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors" data-tab="canceladas">
                    <span class="flex items-center gap-2 relative z-10">
                        <i class="bi bi-x-circle text-lg"></i>
                        Canceladas
                    </span>
                    <div class="absolute bottom-0 left-0 h-0.5 w-full bg-rose-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></div>
                </button>
            </div>
        </div>

        @php
            $citasProximas = ($citas ?? collect())->filter(function($c) {
                return in_array($c->estado_cita, ['Programada', 'Confirmada', 'En Progreso']);
            });
            $citasRealizadas = ($citas ?? collect())->where('estado_cita', 'Completada');
            $citasCanceladas = ($citas ?? collect())->whereIn('estado_cita', ['Cancelada', 'No Asistió']);
        @endphp

        <div class="p-6 md:p-8">
            <!-- Tab: Próximas -->
            <div id="tab-proximas" class="tab-content animate-in fade-in slide-in-from-bottom-2 duration-300">
                <div class="space-y-4">
                    @forelse($citasProximas as $cita)
                        @include('paciente.citas.partials.card-cita', ['cita' => $cita, 'tipo' => 'proxima'])
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <div class="h-24 w-24 bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                <i class="bi bi-calendar-plus text-4xl text-slate-300 dark:text-gray-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">No tienes citas próximas</h3>
                            <p class="text-slate-500 dark:text-gray-400 max-w-md mx-auto mb-8">¿Necesitas atención médica? Agenda una nueva cita con nuestros especialistas.</p>
                            <a href="{{ route('paciente.citas.create') }}" class="btn-primary-dynamic px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all">
                                Agendar Cita Ahora
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab: Realizadas -->
            <div id="tab-realizadas" class="tab-content hidden animate-in fade-in slide-in-from-bottom-2 duration-300">
                <div class="space-y-4">
                    @forelse($citasRealizadas as $cita)
                        @include('paciente.citas.partials.card-cita', ['cita' => $cita, 'tipo' => 'realizada'])
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <i class="bi bi-journal-medical text-5xl text-slate-200 dark:text-gray-700 mb-4"></i>
                            <p class="text-slate-400 dark:text-gray-500 font-medium">No hay historial de citas realizadas</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab: Canceladas -->
            <div id="tab-canceladas" class="tab-content hidden animate-in fade-in slide-in-from-bottom-2 duration-300">
                <div class="space-y-4">
                    @forelse($citasCanceladas as $cita)
                        @include('paciente.citas.partials.card-cita', ['cita' => $cita, 'tipo' => 'cancelada'])
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center">
                            <i class="bi bi-calendar-x text-5xl text-slate-200 dark:text-gray-700 mb-4"></i>
                            <p class="text-slate-400 dark:text-gray-500 font-medium">No hay citas canceladas recientes</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cancelación Premium -->
<div id="modalCancelacion" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop con Glassmorphism -->
    <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg scale-95 opacity-0" id="modalPanel">
                
                <!-- Decorative Top Bar -->
                <div class="h-2 w-full bg-gradient-to-r from-rose-500 via-red-500 to-orange-500"></div>

                <div class="px-6 py-8 sm:p-8">
                    <div class="flex flex-col items-center text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-900/30 mb-6 ring-4 ring-rose-50/50 dark:ring-rose-900/20">
                            <i class="bi bi-calendar2-x-fill text-3xl text-rose-600 dark:text-rose-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white" id="modal-title">¿Cancelar esta cita?</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-gray-400 font-medium">
                            Lamentamos que no puedas asistir. Por favor, indícanos el motivo para ayudarnos a mejorar.
                        </p>
                    </div>

                    <div class="mt-8 space-y-5">
                        <div class="space-y-2">
                            <label for="motivo_cancelacion_input" class="text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wide ml-1">Motivo Principal</label>
                            <div class="relative">
                                <select id="motivo_cancelacion_input" class="block w-full rounded-xl border-slate-200 dark:border-gray-600 bg-slate-50 dark:bg-gray-700 py-3 pl-4 pr-10 text-slate-800 dark:text-white font-bold focus:border-rose-500 focus:ring-rose-500 transition-colors appearance-none" required>
                                    <option value="" class="text-gray-400">Seleccione un motivo...</option>
                                    <option value="Salud">Problemas de Salud</option>
                                    <option value="Trabajo">Motivos Laborales</option>
                                    <option value="Personal">Asuntos Personales</option>
                                    <option value="Transporte">Problemas de Transporte</option>
                                    <option value="Economico">Motivos Económicos</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="explicacion_input" class="text-xs font-bold text-slate-700 dark:text-gray-300 uppercase tracking-wide ml-1">Detalles Adicionales</label>
                            <textarea id="explicacion_input" rows="3" 
                                class="block w-full rounded-xl border-slate-200 dark:border-gray-600 bg-slate-50 dark:bg-gray-700 px-4 py-3 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-gray-500 focus:bg-white dark:focus:bg-gray-900 focus:border-rose-500 focus:ring-rose-500 transition-all resize-none shadow-sm"
                                placeholder="Escribe aquí los detalles..."
                                oninput="document.getElementById('motivo_error').classList.add('hidden')" required></textarea>
                        </div>

                        <p id="motivo_error" class="hidden text-xs font-bold text-rose-500 flex items-center justify-center gap-1 bg-rose-50 dark:bg-rose-900/30 p-2 rounded-lg">
                            <i class="bi bi-exclamation-circle-fill"></i> Debes completar todos los campos
                        </p>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 gap-3 border-t border-slate-100 dark:border-gray-700">
                    <button id="confirmCancelBtn" type="button" onclick="confirmarCancelacion()" class="inline-flex w-full justify-center rounded-xl bg-rose-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 dark:shadow-none hover:bg-rose-700 sm:w-auto hover:-translate-y-0.5 transition-all">
                        Confirmar Cancelación
                    </button>
                    <button type="button" onclick="closeCancelModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-800 px-5 py-3 text-sm font-bold text-slate-700 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-gray-600 hover:bg-slate-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto hover:-translate-y-0.5 transition-all">
                        Volver
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching logic with local storage persistence
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab-button');
        const contents = document.querySelectorAll('.tab-content');
        
        // Restore active tab
        const savedTab = localStorage.getItem('activeCitasTab') || 'proximas';
        activateTab(savedTab);

        tabs.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.dataset.tab;
                activateTab(tabId);
                localStorage.setItem('activeCitasTab', tabId);
            });
        });

        function activateTab(tabId) {
            // Update buttons
            tabs.forEach(btn => {
                const isActive = btn.dataset.tab === tabId;
                const underline = btn.querySelector('.absolute');
                
                if (isActive) {
                    btn.classList.add('active', 'text-medical-600', 'dark:text-medical-400');
                    btn.classList.remove('text-slate-500', 'dark:text-gray-400');
                    underline.classList.replace('scale-x-0', 'scale-x-100');
                } else {
                    btn.classList.remove('active', 'text-medical-600', 'dark:text-medical-400');
                    btn.classList.add('text-slate-500', 'dark:text-gray-400');
                    underline.classList.replace('scale-x-100', 'scale-x-0');
                }
            });

            // Update content
            contents.forEach(content => {
                if (content.id === `tab-${tabId}`) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }
    });

    // Filtering Logic
    function filtrarCitas() {
        const tipoFiltro = document.getElementById('filtro-tipo').value;
        const pacienteFiltro = document.getElementById('filtro-paciente')?.value || '';
        const containerPaciente = document.getElementById('filtro-paciente-container');
        
        if (containerPaciente) {
            if (tipoFiltro === 'terceros') containerPaciente.classList.remove('hidden');
            else containerPaciente.classList.add('hidden');
        }
        
        document.querySelectorAll('.cita-card-wrapper').forEach(card => {
            const tipoCita = card.dataset.tipo;
            const pacienteEspecialId = card.dataset.pacienteEspecial;
            let mostrar = true;
            
            if (tipoFiltro === 'propia' && tipoCita !== 'propia') mostrar = false;
            if (tipoFiltro === 'terceros' && tipoCita !== 'terceros') mostrar = false;
            if (tipoFiltro === 'terceros' && pacienteFiltro && pacienteEspecialId !== pacienteFiltro) mostrar = false;
            
            if (mostrar) {
                card.style.display = '';
                // Add simple fade in
                card.classList.add('animate-in', 'fade-in');
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Modern Modal Logic
    let currentCitaId = null;
    const modal = document.getElementById('modalCancelacion');
    const backdrop = document.getElementById('modalBackdrop');
    const panel = document.getElementById('modalPanel');

    function openCancelModal(citaId) {
        currentCitaId = citaId;
        modal.classList.remove('hidden');
        
        // Animating entrance
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
            panel.classList.add('opacity-100', 'scale-100');
        });
    }

    function closeCancelModal() {
        backdrop.classList.add('opacity-0');
        panel.classList.remove('opacity-100', 'scale-100');
        panel.classList.add('opacity-0', 'scale-95');

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
            // Shake effect
            const panel = document.getElementById('modalPanel');
            panel.classList.add('animate-pulse'); // Simple shake replacement
            setTimeout(() => panel.classList.remove('animate-pulse'), 500);
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
                btn.classList.replace('bg-rose-600', 'bg-emerald-500');
                btn.innerHTML = '<i class="bi bi-check-lg mr-2"></i> Confirmado';
                setTimeout(() => location.reload(), 1000);
            } else {
                throw new Error(data.message || 'Error al cancelar');
            }
        } catch (error) {
            console.error(error);
            alert('Error: ' + error.message);
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>
@endpush
@endsection
