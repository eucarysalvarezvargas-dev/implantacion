<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante de Cita - {{ $cita->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .shadow-xl { shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Card Principal -->
    <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-white text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="bi bi-hospital text-9xl"></i>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-center gap-3 mb-2">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <i class="bi bi-check-lg text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-2xl font-bold uppercase tracking-widest">Comprobante de Cita</h1>
                <p class="text-white/80 font-medium">#{{ $cita->id }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">
            
            <!-- Info Cita -->
            <div class="mb-8 text-center">
                <h2 class="text-emerald-600 dark:text-emerald-400 font-black text-xl mb-1">CITA CONFIRMADA</h2>
                <div class="flex justify-center items-center gap-2 text-gray-500 dark:text-gray-400 text-sm">
                    <span>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }}</span>
                    <span>&bull;</span>
                    <span>{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Detalles Médico -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-100 dark:border-gray-700 pb-1">Médico / Especialidad</h3>
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-100">Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $cita->especialidad->nombre ?? 'General' }}</p>
                        </div>
                    </div>
                </div>
                
                 <!-- Detalles Paciente -->
                 <div class="space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 border-b border-gray-100 dark:border-gray-700 pb-1">Paciente</h3>
                    <div class="flex items-start gap-3">
                        <div class="bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-100">{{ $cita->paciente->primer_nombre }} {{ $cita->paciente->primer_apellido }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $cita->paciente->tipo_documento }}-{{ $cita->paciente->numero_documento }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ubicación -->
            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 mb-8 border border-gray-100 dark:border-gray-700">
                <div class="flex gap-3">
                    <i class="bi bi-geo-alt text-red-500 mt-1"></i>
                    <div>
                        <p class="font-bold text-gray-800 dark:text-gray-200 text-sm">Consultorio {{ $cita->consultorio->nombre ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cita->consultorio->direccion_detallada ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detalles Pago -->
             @if($cita->facturaPaciente && $cita->facturaPaciente->pagos->isNotEmpty())
                @php
                    $pago = $cita->facturaPaciente->pagos->where('status', true)->sortByDesc('created_at')->first();
                @endphp
                @if($pago)
                <div class="border-t-2 border-dashed border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-center text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Información del Pago</h3>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Método</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $pago->metodoPago->nombre ?? 'N/A' }}</span>
                        </div>
                         <div class="text-right">
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Referencia</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $pago->referencia }}</span>
                        </div>
                         <div>
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Fecha Pago</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-gray-500 dark:text-gray-400 text-xs">Monto Total</span>
                            <span class="font-bold text-emerald-600 text-lg">${{ number_format($pago->monto_equivalente_usd, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endif
            @endif

        </div>

        <!-- Footer -->
        <div class="bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-700 p-6 text-center">
            <p class="text-gray-500 dark:text-gray-400 text-xs">Este comprobante es válido como constancia de su cita.</p>
            <p class="text-gray-400 dark:text-gray-500 text-[10px] mt-1">Generado el {{ date('d/m/Y h:i A') }}</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex gap-4 no-print">
        <button onclick="window.print()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-2">
            <i class="bi bi-printer"></i> Imprimir
        </button>
        <button onclick="window.close()" class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-2 px-6 rounded-xl shadow border border-gray-200 dark:border-gray-700 transition-colors">
            Cerrar
        </button>
    </div>

</body>
</html>
