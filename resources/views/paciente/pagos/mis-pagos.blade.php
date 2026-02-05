@extends('layouts.paciente')

@section('title', 'Mis Pagos')

@section('content')
<!-- Header con Glassmorphism -->
<div class="relative bg-white dark:bg-gray-800 rounded-3xl p-6 lg:p-10 shadow-xl overflow-hidden mb-8 border border-slate-100 dark:border-gray-700">
    <!-- Decorative Elements -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="bi bi-wallet2 text-xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Mis Pagos</h2>
            </div>
            <p class="text-slate-500 dark:text-gray-400 text-lg">Controla tus finanzas médicas, facturas y pagos pendientes.</p>
        </div>
        
        <div class="hidden md:block opacity-50">
             <i class="bi bi-credit-card text-6xl text-slate-200 dark:text-gray-700"></i>
        </div>
    </div>
</div>

<!-- Summary Cards (Premium Gradients) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Pagado -->
    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/20 group hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="bi bi-cash-stack text-6xl text-white"></i>
        </div>
        <div class="relative z-10">
            <p class="text-blue-100 font-medium mb-1">Total Pagado</p>
            <h3 class="text-3xl font-bold">${{ number_format($stats['total_pagado'] ?? 0, 2) }}</h3>
            <div class="mt-4 inline-flex items-center text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                <i class="bi bi-check-circle-fill mr-1"></i> Al día
            </div>
        </div>
    </div>

    <!-- Pendiente -->
    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-lg shadow-amber-500/20 group hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="bi bi-hourglass-split text-6xl text-white"></i>
        </div>
        <div class="relative z-10">
            <p class="text-amber-100 font-medium mb-1">Pendiente por Pagar</p>
            <h3 class="text-3xl font-bold">${{ number_format($stats['pendiente'] ?? 0, 2) }}</h3>
            @if(($stats['pendiente'] ?? 0) > 0)
                <div class="mt-4 inline-flex items-center text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm animate-pulse">
                    <i class="bi bi-exclamation-circle-fill mr-1"></i> Acción requerida
                </div>
            @else
                <div class="mt-4 inline-flex items-center text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                    <i class="bi bi-emoji-smile-fill mr-1"></i> Sin deudas
                </div>
            @endif
        </div>
    </div>

    <!-- Facturas -->
    <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/20 group hover:-translate-y-1 transition-transform duration-300">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
            <i class="bi bi-file-earmark-text text-6xl text-white"></i>
        </div>
        <div class="relative z-10">
            <p class="text-emerald-100 font-medium mb-1">Facturas Disponibles</p>
            <h3 class="text-3xl font-bold">{{ $stats['total_facturas'] ?? 0 }}</h3>
            <div class="mt-4 inline-flex items-center text-xs font-semibold bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                <i class="bi bi-download mr-1"></i> Descargables
            </div>
        </div>
    </div>
</div>

<!-- Tabs & Content -->
<div class="space-y-6">
    <!-- Custom Pills -->
    <div class="flex flex-wrap gap-2 p-1.5 bg-slate-100 dark:bg-gray-800/50 rounded-2xl w-fit">
        <button data-tab-target="#tab-pendientes" 
                class="tab-btn active px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2 bg-white dark:bg-gray-800 text-slate-800 dark:text-white shadow-sm">
            <i class="bi bi-hourglass-split"></i> Pendientes
            @if(($stats['pendiente'] ?? 0) > 0)
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            @endif
        </button>
        <button data-tab-target="#tab-pagados" 
                class="tab-btn px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2 text-slate-500 dark:text-gray-400 hover:text-slate-700">
            <i class="bi bi-check2-circle"></i> Historial de Pagos
        </button>
        <button data-tab-target="#tab-facturas" 
                class="tab-btn px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2 text-slate-500 dark:text-gray-400 hover:text-slate-700">
            <i class="bi bi-file-text"></i> Mis Facturas
        </button>
    </div>

    <!-- Contenido Pendientes -->
    <div id="tab-pendientes" class="tab-content space-y-4 animate-slide-in-up">
        @forelse($pagosPendientes ?? [] as $pago)
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 border border-amber-100 dark:border-amber-900/30 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                <div class="absolute top-0 left-0 bottom-0 w-1.5 bg-amber-500"></div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <i class="bi bi-exclamation-lg text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $pago->concepto ?? 'Pago de Consulta' }}</h3>
                            <p class="text-sm text-slate-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                                <i class="bi bi-calendar-event"></i> Vence: {{ isset($pago->fecha_vencimiento) ? \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d M, Y') : 'N/A' }}
                            </p>
                            <span class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-xs font-bold text-amber-600 dark:text-amber-400">
                                Pendiente
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-3">
                         <div class="text-right">
                            <p class="text-sm text-slate-400 mb-0.5">Monto a pagar</p>
                            <p class="text-3xl font-black text-slate-800 dark:text-white">${{ number_format($pago->monto ?? 0, 2) }}</p>
                        </div>
                        <button class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all active:scale-95 flex items-center gap-2">
                            <i class="bi bi-credit-card-2-front"></i> Pagar Ahora
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-slate-200 dark:border-gray-700">
                <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center mb-4">
                    <i class="bi bi-check-lg text-4xl text-emerald-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">¡Todo al día!</h3>
                <p class="text-slate-500 dark:text-gray-400">No tienes pagos pendientes en este momento.</p>
            </div>
        @endforelse
    </div>

    <!-- Contenido Pagados -->
    <div id="tab-pagados" class="tab-content space-y-4 hidden">
         @forelse($pagosPagados ?? [] as $index => $pago)
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-slate-100 dark:border-gray-700 shadow-sm hover:bg-slate-50 dark:hover:bg-gray-700/30 transition-colors" 
                 style="animation-delay: {{ $index * 50 }}ms;">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                            <i class="bi bi-check2 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $pago->concepto ?? 'Pago Realizado' }}</h4>
                            <div class="flex flex-wrap items-center gap-3 mt-1 text-sm text-slate-500 dark:text-gray-400">
                                <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> {{ isset($pago->fecha_pago) ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d M, Y') : 'N/A' }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                <span class="flex items-center gap-1"><i class="bi bi-wallet2"></i> {{ ucfirst($pago->metodo_pago ?? 'N/A') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-slate-100 dark:border-gray-700">
                        <span class="text-xl font-bold text-slate-800 dark:text-white">${{ number_format($pago->monto ?? 0, 2) }}</span>
                        <div class="flex gap-2">
                             <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Ver Recibo">
                                <i class="bi bi-eye text-lg"></i>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Descargar PDF">
                                <i class="bi bi-download text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
         @empty
            <div class="text-center py-12">
                <p class="text-slate-500">No hay pagos registrados en el historial.</p>
            </div>
         @endforelse
    </div>
    
     <!-- Contenido Facturas -->
    <div id="tab-facturas" class="tab-content grid md:grid-cols-2 gap-4 hidden">
         @forelse($facturas ?? [] as $factura)
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                         <i class="bi bi-file-earmark-text text-xl"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        PAGADA
                    </span>
                </div>
                
                <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Factura #{{ $factura->numero ?? '0000' }}</h4>
                <p class="text-sm text-slate-500 dark:text-gray-400 mb-4">{{ isset($factura->fecha) ? \Carbon\Carbon::parse($factura->fecha)->format('d M, Y') : 'N/A' }}</p>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-50 dark:border-gray-700/50">
                    <span class="text-xl font-black text-slate-800 dark:text-white">${{ number_format($factura->total ?? 0, 2) }}</span>
                    <button class="text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        Descargar <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
         @empty
            <div class="col-span-2 text-center py-12">
                <p class="text-slate-500">No hay facturas disponibles.</p>
            </div>
         @endforelse
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const triggers = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');

        triggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const targetId = this.getAttribute('data-tab-target');
                const targetContent = document.querySelector(targetId);

                // Reset Triggers
                triggers.forEach(t => {
                    t.classList.remove('bg-white', 'text-slate-800', 'shadow-sm', 'dark:bg-gray-800', 'dark:text-white', 'active');
                    t.classList.add('text-slate-500', 'dark:text-gray-400', 'hover:text-slate-700');
                });

                // Activate Clicked Trigger
                this.classList.remove('text-slate-500', 'dark:text-gray-400', 'hover:text-slate-700');
                this.classList.add('bg-white', 'text-slate-800', 'shadow-sm', 'dark:bg-gray-800', 'dark:text-white', 'active');

                // Hide All Content & Reset Animation
                contents.forEach(content => {
                    content.classList.add('hidden');
                    content.classList.remove('animate-slide-in-up');
                });

                // Show Target & Animate
                targetContent.classList.remove('hidden');
                
                // Add staggered animation to children if they don't have it
                if(targetId === '#tab-pagados') {
                    Array.from(targetContent.children).forEach(child => {
                        child.classList.add('animate-slide-in-up');
                    });
                } else {
                     targetContent.classList.add('animate-slide-in-up');
                }
            });
        });
    });
</script>
@endpush
@endsection
