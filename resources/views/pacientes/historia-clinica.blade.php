@extends('layouts.app')

@section('title', 'Historia Clínica - ' . ($paciente->nombre_completo ?? 'Paciente'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('paciente.dashboard') }}" class="btn btn-outline">
                    <i class="bi-arrow-left"></i> Volver
                </a>
                <h1 class="text-3xl font-bold text-gray-800">
                    Historia Clínica
                </h1>
            </div>
        </div>
        
        @if($paciente)
            <!-- Información del Paciente -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nombre Completo</label>
                        <p class="text-lg font-semibold">{{ $paciente->nombre_completo }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Documento</label>
                        <p class="text-lg">{{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Fecha de Nacimiento</label>
                        <p class="text-lg">{{ $paciente->fecha_nac ? $paciente->fecha_nac->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Género</label>
                        <p class="text-lg">{{ $paciente->genero ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <p class="text-red-800">Paciente no encontrado</p>
            </div>
        @endif
    </div>

    @if($paciente)
        <!-- Tabs de Historia Clínica -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button class="tab-btn px-6 py-3 border-b-2 border-blue-500 text-blue-600 font-medium" data-tab="datos-base">
                        Datos Básicos
                    </button>
                    <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium" data-tab="evoluciones">
                        Evoluciones
                    </button>
                    <button class="tab-btn px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium" data-tab="ordenes">
                        Órdenes Médicas
                    </button>
                </nav>
            </div>

            <!-- Contenido de Tabs -->
            <div class="p-6">
                <!-- Datos Básicos -->
                <div id="datos-base" class="tab-content">
                    @if($paciente->historiaClinicaBase)
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-3">Información Médica Base</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Tipo de Sangre</label>
                                        <p class="text-lg">{{ $paciente->historiaClinicaBase->tipo_sangre ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Alergias</label>
                                        <p class="text-lg">{{ $paciente->historiaClinicaBase->alergias ?? 'Ninguna' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Enfermedades Crónicas</label>
                                        <p class="text-lg">{{ $paciente->historiaClinicaBase->enfermedades_cronicas ?? 'Ninguna' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Medicamentos Actuales</label>
                                        <p class="text-lg">{{ $paciente->historiaClinicaBase->medicamentos_actuales ?? 'Ninguno' }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($paciente->historiaClinicaBase->antecedentes_familiares)
                                <div>
                                    <h4 class="text-md font-semibold mb-2">Antecedentes Familiares</h4>
                                    <p class="text-gray-700">{{ $paciente->historiaClinicaBase->antecedentes_familiares }}</p>
                                </div>
                            @endif

                            @if($paciente->historiaClinicaBase->antecedentes_personales)
                                <div>
                                    <h4 class="text-md font-semibold mb-2">Antecedentes Personales</h4>
                                    <p class="text-gray-700">{{ $paciente->historiaClinicaBase->antecedentes_personales }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="bi-clipboard-x text-4xl mb-4"></i>
                            <p>No hay información médica base registrada</p>
                        </div>
                    @endif
                </div>

                <!-- Evoluciones -->
                <div id="evoluciones" class="tab-content hidden">
                    @if($paciente->evolucionesClinicas && $paciente->evolucionesClinicas->count() > 0)
                        <div class="space-y-4">
                            @foreach($paciente->evolucionesClinicas->sortByDesc('created_at') as $evolucion)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold">Evolución del {{ $evolucion->created_at->format('d/m/Y H:i') }}</h4>
                                            @if($evolucion->medico)
                                                <p class="text-sm text-gray-600">Dr. {{ $evolucion->medico->nombre_completo }}</p>
                                            @endif
                                        </div>
                                        @if($evolucion->cita)
                                            <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                Cita: {{ $evolucion->cita->fecha_cita->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($evolucion->motivo_consulta)
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700">Motivo de Consulta:</h5>
                                            <p>{{ $evolucion->motivo_consulta }}</p>
                                        </div>
                                    @endif

                                    @if($evolucion->diagnostico)
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700">Diagnóstico:</h5>
                                            <p>{{ $evolucion->diagnostico }}</p>
                                        </div>
                                    @endif

                                    @if($evolucion->tratamiento)
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700">Tratamiento:</h5>
                                            <p>{{ $evolucion->tratamiento }}</p>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                                        @if($evolucion->peso_kg)
                                            <div><span class="font-medium">Peso:</span> {{ $evolucion->peso_kg }} kg</div>
                                        @endif
                                        @if($evolucion->talla_cm)
                                            <div><span class="font-medium">Talla:</span> {{ $evolucion->talla_cm }} cm</div>
                                        @endif
                                        @if($evolucion->tension_sistolica && $evolucion->tension_diastolica)
                                            <div><span class="font-medium">Tensión:</span> {{ $evolucion->tension_sistolica }}/{{ $evolucion->tension_diastolica }} mmHg</div>
                                        @endif
                                        @if($evolucion->frecuencia_cardiaca)
                                            <div><span class="font-medium">FC:</span> {{ $evolucion->frecuencia_cardiaca }} lpm</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="bi-clipboard2 text-4xl mb-4"></i>
                            <p>No hay evoluciones registradas</p>
                        </div>
                    @endif
                </div>

                <!-- Órdenes Médicas -->
                <div id="ordenes" class="tab-content hidden">
                    @if($paciente->ordenesMedicas && $paciente->ordenesMedicas->count() > 0)
                        <div class="space-y-4">
                            @foreach($paciente->ordenesMedicas->sortByDesc('created_at') as $orden)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold">{{ $orden->codigo_orden }}</h4>
                                            <p class="text-sm text-gray-600">{{ $orden->tipo_orden }} - {{ $orden->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="text-sm bg-{{ $orden->color_tipo }}-100 text-{{ $orden->color_tipo }}-800 px-2 py-1 rounded">
                                            {{ $orden->tipo_orden }}
                                        </span>
                                    </div>

                                    @if($orden->descripcion_detallada)
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700">Descripción:</h5>
                                            <p>{{ $orden->descripcion_detallada }}</p>
                                        </div>
                                    @endif

                                    @if($orden->indicaciones)
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700">Indicaciones:</h5>
                                            <p>{{ $orden->indicaciones }}</p>
                                        </div>
                                    @endif

                                    @if($orden->medicamentos && $orden->medicamentos->count() > 0)
                                        <div class="mb-3">
                                            <h5 class="font-medium text-gray-700">Medicamentos:</h5>
                                            <ul class="list-disc list-inside">
                                                @foreach($orden->medicamentos as $medicamento)
                                                    <li>{{ $medicamento->nombre_medicamento }} - {{ $medicamento->dosis }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="bi-file-medical text-4xl mb-4"></i>
                            <p>No hay órdenes médicas registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Simple tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active classes
            tabBtns.forEach(b => {
                b.classList.remove('border-blue-500', 'text-blue-600');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Add active classes
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600');
            
            document.getElementById(targetTab).classList.remove('hidden');
        });
    });
});
</script>
@endsection
