@extends('layouts.paciente')

@section('title', 'Mis Órdenes Médicas')

@section('content')
<!-- Header con Glassmorphism -->
<div class="relative bg-white dark:bg-gray-800 rounded-3xl p-6 lg:p-10 shadow-xl overflow-hidden mb-8 border border-slate-100 dark:border-gray-700">
    <!-- Decorative Elements -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                    <i class="bi bi-file-medical-fill text-xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Mis Órdenes Médicas</h2>
            </div>
            <p class="text-slate-500 dark:text-gray-400 text-lg">Gestiona tus recetas, exámenes y referencias médicas.</p>
        </div>

        @if($solicitudesPendientes > 0)
            <a href="{{ route('paciente.ordenes.solicitudes') }}" 
               class="group relative inline-flex items-center gap-3 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-amber-500/30 hover:-translate-y-0.5">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
                </span>
                <span>{{ $solicitudesPendientes }} Solicitudes Pendientes</span>
                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        @endif
    </div>
</div>

<!-- Resumen por tipo (Gradient Cards) -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @php
        $stats = [
            'Receta' => ['count' => $ordenes->where('tipo_orden', 'Receta')->count(), 'icon' => 'bi-capsule', 'color' => 'emerald', 'label' => 'Recetas'],
            'Laboratorio' => ['count' => $ordenes->where('tipo_orden', 'Laboratorio')->count(), 'icon' => 'bi-droplet', 'color' => 'blue', 'label' => 'Laboratorio'],
            'Imagenologia' => ['count' => $ordenes->where('tipo_orden', 'Imagenologia')->count(), 'icon' => 'bi-x-ray', 'color' => 'violet', 'label' => 'Imágenes'],
            'Referencia' => ['count' => $ordenes->where('tipo_orden', 'Referencia')->count(), 'icon' => 'bi-person-badge', 'color' => 'amber', 'label' => 'Referencias'],
        ];
    @endphp

    @foreach($stats as $type => $stat)
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-5 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="bi {{ $stat['icon'] }} text-5xl text-{{ $stat['color'] }}-500"></i>
            </div>
            <div class="relative z-10">
                <div class="w-10 h-10 rounded-lg bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/20 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="bi {{ $stat['icon'] }} text-xl text-{{ $stat['color'] }}-500"></i>
                </div>
                <p class="text-3xl font-bold text-slate-800 dark:text-white mb-1">{{ $stat['count'] }}</p>
                <p class="text-sm font-medium text-slate-500 dark:text-gray-400">{{ $stat['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

<!-- Filtros y Lista -->
<div class="grid lg:grid-cols-4 gap-8">
    <!-- Sidebar Filters (Desktop) / Top Filters (Mobile) -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-slate-100 dark:border-gray-700 shadow-sm sticky top-24">
            <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <i class="bi bi-funnel"></i> Filtros
            </h3>
            
            <form action="{{ route('paciente.ordenes.index') }}" method="GET" id="filterForm">
                
                <!-- Filtro Paciente -->
                @if(isset($esRepresentante) && $esRepresentante && isset($pacientesEspeciales) && $pacientesEspeciales->count() > 0)
                    <div class="mb-6">
                        <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 block">Paciente</label>
                        <select name="filtro_paciente" onchange="this.form.submit()" class="w-full text-sm rounded-xl border-slate-200 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Todos</option>
                            <option value="propias" {{ request('filtro_paciente') == 'propias' ? 'selected' : '' }}>Mis Órdenes</option>
                            @foreach($pacientesEspeciales as $pe)
                                <option value="{{ $pe->id }}" {{ request('filtro_paciente') == $pe->id ? 'selected' : '' }}>
                                    {{ $pe->primer_nombre }} {{ $pe->primer_apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Filtro Tipo (Tabs Verticales) -->
                <div>
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2 block">Tipo de Orden</label>
                    <div class="space-y-2">
                        @php
                            $types = [
                                '' => ['label' => 'Todas', 'icon' => 'bi-grid'],
                                'Receta' => ['label' => 'Recetas', 'icon' => 'bi-capsule'],
                                'Laboratorio' => ['label' => 'Laboratorio', 'icon' => 'bi-droplet'],
                                'Imagenologia' => ['label' => 'Imágenes', 'icon' => 'bi-x-ray'],
                                'Referencia' => ['label' => 'Referencias', 'icon' => 'bi-person-badge'],
                            ];
                            $currentType = request('tipo_orden', '');
                        @endphp

                        @foreach($types as $value => $config)
                            <label class="cursor-pointer block">
                                <input type="radio" name="tipo_orden" value="{{ $value }}" onchange="this.form.submit()" class="peer hidden" {{ $currentType == $value ? 'checked' : '' }}>
                                <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200 
                                            peer-checked:bg-purple-500 peer-checked:text-white peer-checked:shadow-md peer-checked:shadow-purple-500/20
                                            hover:bg-slate-50 dark:hover:bg-gray-700 text-slate-600 dark:text-gray-300">
                                    <i class="bi {{ $config['icon'] }}"></i>
                                    <span class="font-medium text-sm">{{ $config['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Ordenes -->
    <div class="lg:col-span-3 space-y-4">
        @forelse($ordenes as $orden)
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-0 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden animate-in fade-in slide-in-from-bottom-4">
                
                <!-- Status Stripe -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-{{ $orden->color_tipo }}-500"></div>

                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Icon -->
                        <div class="shrink-0">
                            <div class="w-14 h-14 rounded-2xl bg-{{ $orden->color_tipo }}-50 dark:bg-{{ $orden->color_tipo }}-900/20 flex items-center justify-center text-{{ $orden->color_tipo }}-600 dark:text-{{ $orden->color_tipo }}-400 group-hover:scale-110 transition-transform duration-300">
                                <i class="bi {{ $orden->icono_tipo }} text-2xl"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-bold text-slate-800 dark:text-white group-hover:text-purple-600 transition-colors">
                                        {{ $orden->tipo_orden }}
                                    </h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $orden->codigo_orden }}
                                    </span>
                                    
                                    @if($orden->paciente_especial_id)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                                            <i class="bi bi-person-heart mr-1"></i>Paciente
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm font-medium text-slate-400 flex items-center gap-1">
                                    <i class="bi bi-calendar3"></i> {{ $orden->fecha_emision->format('d/m/Y') }}
                                </span>
                            </div>

                            <!-- Doctor & Items Info -->
                            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm text-slate-600 dark:text-gray-300 flex items-center gap-2">
                                        <i class="bi bi-person-badge text-slate-400"></i>
                                        <span>Dr. {{ $orden->medico->primer_nombre ?? '' }} {{ $orden->medico->primer_apellido ?? '' }}</span>
                                    </p>
                                    @if($orden->cita && $orden->cita->consultorio)
                                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                                            <i class="bi bi-geo-alt"></i> {{ $orden->cita->consultorio->nombre }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Items Preview Pills -->
                                <div class="flex flex-wrap gap-2 content-start">
                                    @php
                                        $items = collect();
                                        if($orden->medicamentos->count()) $items = $items->concat($orden->medicamentos->map(fn($i) => ['icon'=>'bi-capsule', 'text'=>$i->medicamento]));
                                        if($orden->examenes->count()) $items = $items->concat($orden->examenes->map(fn($i) => ['icon'=>'bi-droplet', 'text'=>$i->nombre_examen]));
                                    @endphp

                                    @foreach($items->take(2) as $item)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-slate-50 text-slate-600 dark:bg-gray-700/50 dark:text-gray-300 border border-slate-100 dark:border-gray-600">
                                            <i class="bi {{ $item['icon'] }} mr-1.5 text-slate-400"></i>
                                            {{ Str::limit($item['text'], 15) }}
                                        </span>
                                    @endforeach
                                    @if($items->count() > 2)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium text-slate-400">
                                            +{{ $items->count() - 2 }} más
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions Footer -->
                            <div class="flex items-center justify-end pt-4 border-t border-slate-50 dark:border-gray-700/50">
                                <a href="{{ route('paciente.ordenes.show', $orden->id) }}" class="btn-premium-outline text-sm py-2 px-4">
                                    Ver Detalles <i class="bi bi-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-slate-200 dark:border-gray-700">
                <div class="w-20 h-20 mx-auto rounded-full bg-slate-50 dark:bg-gray-900 flex items-center justify-center mb-6">
                    <i class="bi bi-file-medical text-4xl text-slate-300 dark:text-gray-600"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">No hay órdenes para mostrar</h3>
                <p class="text-slate-500 dark:text-gray-400 max-w-sm mx-auto">
                    Intenta cambiar los filtros o espera a que tu médico genere una orden en tu próxima consulta.
                </p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($ordenes->hasPages())
            <div class="mt-8">
                {{ $ordenes->withQueryString()->links('vendor.pagination.medical') }}
            </div>
        @endif
    </div>
</div>

<style>
    .btn-premium-outline {
        @apply inline-flex items-center text-purple-600 bg-transparent border border-purple-200 hover:bg-purple-50 hover:border-purple-300 font-medium rounded-xl transition-all duration-200;
    }
</style>
@endsection
