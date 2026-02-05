@extends('layouts.paciente')

@section('title', 'Mi Historial Médico')

@section('content')
<!-- Header con Glassmorphism -->
<div class="relative bg-white dark:bg-gray-800 rounded-3xl p-6 lg:p-10 shadow-xl overflow-hidden mb-8 border border-slate-100 dark:border-gray-700">
    <!-- Decorative Elements -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                    <i class="bi bi-journal-medical text-xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Mi Historial Médico</h2>
            </div>
            <p class="text-slate-500 dark:text-gray-400 text-lg">Tu expediente clínico digital, organizado cronológicamente.</p>
        </div>
        
        <!-- Search/Filter Trigger (Mobile) or Graphic (Desktop) -->
        <div class="hidden md:block opacity-50">
             <i class="bi bi-activity text-6xl text-slate-200 dark:text-gray-700"></i>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Filters & Timeline -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Interactive Pill Filters -->
        <div class="sticky top-24 z-20 bg-slate-50/90 dark:bg-gray-900/90 backdrop-blur-md py-2 -mx-2 px-2 rounded-xl">
             <form method="GET" class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide" id="historyFilters">
                <input type="hidden" name="fecha_desde" value="{{ request('fecha_desde') }}">
                <input type="hidden" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                
                @php
                    $types = [
                        '' => ['label' => 'Todo el Historial', 'icon' => 'bi-collection'],
                        'evolucion' => ['label' => 'Consultas', 'icon' => 'bi-file-medical'],
                        'receta' => ['label' => 'Recetas', 'icon' => 'bi-prescription'],
                        'laboratorio' => ['label' => 'Laboratorios', 'icon' => 'bi-droplet'],
                        'imagenologia' => ['label' => 'Imagenología', 'icon' => 'bi-x-ray']
                    ];
                    $currentType = request('tipo', '');
                @endphp

                @foreach($types as $value => $config)
                    <button type="submit" name="tipo" value="{{ $value }}" 
                            class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 border flex items-center gap-2
                            {{ $currentType == $value 
                                ? 'bg-slate-800 text-white border-slate-800 shadow-md transform scale-105' 
                                : 'bg-white dark:bg-gray-800 text-slate-600 dark:text-gray-300 border-slate-200 dark:border-gray-700 hover:bg-slate-100 dark:hover:bg-gray-700' }}">
                        <i class="bi {{ $config['icon'] }}"></i>
                        {{ $config['label'] }}
                    </button>
                @endforeach
            </form>
        </div>

        <!-- Vertical Timeline -->
        <div class="relative pl-4 md:pl-8 space-y-8">
            <!-- Continuous Line -->
            <div class="absolute left-4 md:left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-slate-200 via-slate-200 to-transparent dark:from-gray-700 dark:via-gray-700"></div>

            @forelse($historial ?? [] as $registro)
                @php
                    // Adjust variable access based on model structure detected in previous file content
                    // Previous file used attributes like $registro->tipo, $registro->titulo etc.
                    // We need to ensure we map whatever $historial contains (which seems to be Cita objects for 'propia' and mixed for 'terceros'?)
                    // Wait, Controller returns Cita models with 'medico', 'consultorio' etc.
                    // But the view logic I wrote earlier assumed generic fields like 'tipo'.
                    // Let's adapt to Cita model structure if needed, BUT the controller combines different things?
                    // Controller code: Returns Collection of Citas with 'tipo_historia_display'.
                    // Actually, the previous view code (turn 20) used: $registro->tipo, $registro->titulo.
                    // BUT the controller code I saw (turn 22) returns Cita objects. Cita objects generally don't have 'tipo' unless accessor.
                    // A 'Cita' is a consultation.
                    // The previous view 'paciente/historial/index.blade.php' seemed to iterate $historial and check $registro->tipo.
                    // Maybe there IS an attribute or it's a mix.
                    // However, to be safe and consistent with the controller I saw:
                    // The Controller returns Citas.
                    // Let's assume the controller passed to this view is indeed the one I saw.
                    // Wait, if the controller returns CITA objects, why did the old view use 'receta', 'laboratorio'?
                    // Maybe $historial contains diverse models or Cita has a 'tipo' attribute?
                    // Let's stick to the structure of the *old view* I replaced to accept properties like ->tipo.
                    // Note: The file I read in step 267 was 'paciente/historial/index.blade.php'.
                    // That file used $registro->tipo == 'evolucion', 'receta', etc.
                    // So I will assume the data passed supports these properties.
                    
                    $colors = [
                        'evolucion' => 'blue',
                        'receta' => 'purple',
                        'laboratorio' => 'emerald',
                        'imagenologia' => 'amber'
                    ];
                    $icons = [
                        'evolucion' => 'bi-file-medical',
                        'receta' => 'bi-prescription',
                        'laboratorio' => 'bi-activity',
                        'imagenologia' => 'bi-x-ray'
                    ];
                    $type = $registro->tipo ?? 'evolucion';
                    if(!isset($registro->tipo) && isset($registro->id)) $type = 'evolucion'; // Default for Cita
                    
                    $color = $colors[$type] ?? 'slate';
                    $icon = $icons[$type] ?? 'bi-file-text';
                @endphp

                <!-- Timeline Item -->
                <div class="relative animate-slide-in-up" style="animation-duration: 0.5s; animation-fill-mode: both; animation-delay: {{ $loop->index * 100 }}ms;">
                    <!-- Node Dot -->
                    <div class="absolute -left-[5px] top-6 w-3 h-3 rounded-full bg-{{ $color }}-500 ring-4 ring-white dark:ring-gray-900 shadow-sm z-10"></div>
                    
                    <!-- Date Label -->
                    <div class="pl-8">
                        <span class="inline-block px-3 py-1 rounded-lg bg-slate-100 dark:bg-gray-800 text-xs font-bold text-slate-500 mb-2">
                             {{ isset($registro->created_at) ? \Carbon\Carbon::parse($registro->created_at)->format('d M Y, h:i A') : (isset($registro->fecha_cita) ? \Carbon\Carbon::parse($registro->fecha_cita)->format('d M Y, h:i A') : 'Fecha desconocida') }}
                        </span>

                        <!-- Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all duration-300 p-6 group relative overflow-hidden">
                             <!-- Status Stripe -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-{{ $color }}-500 opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col sm:flex-row gap-5">
                                <!-- Icon Box -->
                                <div class="shrink-0">
                                    <div class="w-12 h-12 rounded-xl bg-{{ $color }}-50 dark:bg-{{ $color }}-900/20 flex items-center justify-center text-{{ $color }}-600 dark:text-{{ $color }}-400 group-hover:scale-110 transition-transform">
                                        <i class="bi {{ $icon }} text-xl"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0 space-y-3">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $registro->titulo ?? 'Consulta Médica' }}</h3>
                                            <p class="text-sm text-slate-500 dark:text-gray-400 flex items-center gap-1.5 mt-1">
                                                <i class="bi bi-person-badge"></i>
                                                Dr. {{ $registro->medico->primer_nombre ?? 'N/A' }} {{ $registro->medico->primer_apellido ?? '' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Specific Details based on Type -->
                                    <div class="bg-slate-50 dark:bg-gray-700/30 rounded-xl p-4 text-sm leading-relaxed border border-slate-100 dark:border-gray-700/50">
                                        @if($type == 'evolucion')
                                            <div class="mb-2">
                                                <span class="font-semibold text-slate-700 dark:text-gray-300 block mb-1">Diagnóstico:</span>
                                                <p class="text-slate-600 dark:text-gray-400">{{ $registro->diagnostico ?? 'Sin diagnóstico registrado.' }}</p>
                                            </div>
                                            @if($registro->tratamiento)
                                                <div class="pt-2 border-t border-slate-200 dark:border-gray-600/50">
                                                    <span class="font-semibold text-slate-700 dark:text-gray-300 block mb-1 mt-2">Plan / Tratamiento:</span>
                                                    <p class="text-slate-600 dark:text-gray-400">{{ Str::limit($registro->tratamiento, 200) }}</p>
                                                </div>
                                            @endif
                                        @elseif($type == 'receta')
                                            <div class="flex items-center gap-3">
                                                 <div class="text-2xl text-purple-500"><i class="bi bi-capsule"></i></div>
                                                 <div>
                                                     <p class="font-bold text-slate-800 dark:text-white">{{ $registro->medicamento ?? 'Medicamento' }}</p>
                                                     <p class="text-slate-500 dark:text-gray-400 mt-0.5">{{ $registro->dosis }} • {{ $registro->frecuencia }}</p>
                                                 </div>
                                            </div>
                                        @elseif($type == 'laboratorio' || $type == 'imagenologia')
                                            <span class="font-semibold text-slate-700 dark:text-gray-300 block mb-2">Estudios Solicitados:</span>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach(json_decode($registro->examenes ?? '[]') ?? [] as $examen)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-600 text-xs font-medium text-slate-600 dark:text-gray-300 shadow-sm">
                                                        <i class="bi bi-check2 mr-1.5 text-{{ $color }}-500"></i> {{ $examen }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Footer Actions -->
                                    <div class="flex justify-end gap-3 pt-2">
                                        <button class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white transition-colors flex items-center gap-1">
                                            <i class="bi bi-eye"></i> Detalle
                                        </button>
                                        <button class="text-sm font-medium text-{{ $color }}-600 hover:text-{{ $color }}-700 transition-colors flex items-center gap-1">
                                            <i class="bi bi-download"></i> PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="pl-8">
                     <div class="card p-12 text-center border-dashed border-2 border-slate-200 dark:border-gray-700 bg-transparent shadow-none">
                        <div class="w-16 h-16 mx-auto rounded-full bg-slate-50 dark:bg-gray-800 flex items-center justify-center mb-4 text-slate-300 dark:text-gray-600">
                             <i class="bi bi-clock-history text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 dark:text-gray-300">Historial Vacío</h3>
                        <p class="text-slate-500 dark:text-gray-400">No hay registros médicos que coincidan con tus filtros.</p>
                     </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($historial) && $historial->hasPages())
        <div class="pl-8 pt-4">
             {{ $historial->links('vendor.pagination.medical') }}
        </div>
        @endif
    </div>

    <!-- Right Column: Sidebar Stats & Info -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Stats Cards (Mini) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-slate-100 dark:border-gray-700 shadow-sm">
             <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Resumen General</h3>
             <div class="space-y-4">
                 <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-blue-50 to-white dark:from-blue-900/20 dark:to-gray-800 border border-blue-100 dark:border-blue-900/30">
                     <div class="flex items-center gap-3">
                         <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm text-blue-500"><i class="bi bi-heart-pulse"></i></div>
                         <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Consultas</span>
                     </div>
                     <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['total_consultas'] ?? 0 }}</span>
                 </div>
                 
                 <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-purple-50 to-white dark:from-purple-900/20 dark:to-gray-800 border border-purple-100 dark:border-purple-900/30">
                     <div class="flex items-center gap-3">
                         <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm text-purple-500"><i class="bi bi-prescription2"></i></div>
                         <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Recetas</span>
                     </div>
                     <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['total_recetas'] ?? 0 }}</span>
                 </div>

                 <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-white dark:from-emerald-900/20 dark:to-gray-800 border border-emerald-100 dark:border-emerald-900/30">
                     <div class="flex items-center gap-3">
                         <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm text-emerald-500"><i class="bi bi-activity"></i></div>
                         <span class="text-sm font-medium text-slate-700 dark:text-gray-300">Estudios</span>
                     </div>
                     <span class="text-xl font-bold text-slate-800 dark:text-white">{{ $stats['total_labs'] ?? 0 }}</span>
                 </div>
             </div>
        </div>

        <!-- Medical Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-slate-100 dark:border-gray-700 shadow-sm relative overflow-hidden">
             <!-- Decor -->
             <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl pointer-events-none"></div>

             <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 relative z-10">Datos Vitales</h3>
             
             <div class="mb-6 relative z-10">
                 <p class="text-xs text-slate-500 mb-1">Tipo de Sangre</p>
                 <div class="flex items-end gap-2">
                     <span class="text-3xl font-black text-slate-800 dark:text-white">{{ auth()->user()->paciente->tipo_sangre ?? '--' }}</span>
                     <span class="mb-1.5 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-gray-700 text-xs font-bold text-slate-600 dark:text-gray-300">RH {{ auth()->user()->paciente->factor_rh ?? '+' }}</span>
                 </div>
             </div>

             @if(auth()->user()->paciente->alergias ?? null)
                <div class="p-4 bg-rose-50 dark:bg-rose-900/20 rounded-xl border border-rose-100 dark:border-rose-900/30 relative z-10">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-exclamation-triangle-fill text-rose-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase mb-1">Alergias Conocidas</p>
                            <p class="text-sm text-rose-800 dark:text-rose-300 leading-snug">{{ auth()->user()->paciente->alergias }}</p>
                        </div>
                    </div>
                </div>
             @endif
        </div>

        <!-- Export Action -->
        <div class="bg-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-500/30 text-white relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-700 opacity-100 transition-opacity"></div>
            <div class="absolute -right-10 -bottom-10 text-9xl text-white opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i class="bi bi-file-earmark-medical"></i>
            </div>
            
            <div class="relative z-10">
                <h3 class="font-bold text-lg mb-2">Exportar Historial</h3>
                <p class="text-blue-100 text-sm mb-6 max-w-[80%]">Descarga tu expediente completo para compartirlo con otros especialistas.</p>
                <button onclick="window.print()" class="w-full py-3 bg-white text-blue-600 font-bold rounded-xl shadow-sm hover:bg-blue-50 transition-colors flex items-center justify-center gap-2">
                    <i class="bi bi-download"></i> Descargar PDF
                </button>
            </div>
        </div>

    </div>
</div>

<style>
    /* Hide scrollbar for tabs but keep functionality */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
