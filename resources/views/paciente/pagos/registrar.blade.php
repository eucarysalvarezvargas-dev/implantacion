@extends('layouts.paciente')

@section('title', 'Registrar Pago')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
    
    * {
        font-family: 'Inter', sans-serif;
    }

    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .premium-input {
        background: linear-gradient(to right, #ffffff, #f8fafc);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px 20px;
        font-size: 16px;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .premium-input:focus {
        outline: none;
        border-color: #10b981;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .premium-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .premium-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 20px;
        padding-right: 48px;
    }

    .method-card {
        background: white;
        border: 3px solid #e2e8f0;
        border-radius: 24px;
        padding: 28px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .method-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--medical-500), var(--medical-600));
        transform: scaleX(0);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .method-card:hover {
        border-color: var(--medical-500);
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 0 0 3px var(--medical-100);
    }

    .method-card.selected {
        background: linear-gradient(135deg, var(--medical-50), white);
        border-color: var(--medical-500);
        box-shadow: 0 0 0 4px var(--medical-100), 0 12px 40px var(--medical-200);
        transform: translateY(-6px) scale(1.05);
    }

    .method-card.selected::before {
        transform: scaleX(1);
    }

    .method-card .checkmark {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, var(--medical-500), var(--medical-600));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: 0 4px 12px var(--medical-200);
    }

    .method-card.selected .checkmark {
        opacity: 1;
        transform: scale(1);
    }

    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 40px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    }

    .upload-area.dragover {
        border-color: #10b981;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        transform: scale(1.02);
    }

    .amount-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 20px 32px;
        border-radius: 20px;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.02em;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        display: inline-block;
    }

    .info-pill {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 12px 24px;
        border-radius: 100px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-card {
        background: white;
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] py-10 px-4">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="mb-8 flex items-center gap-4" style="animation: slideInFromTop 0.5s ease-out;">
            <a href="{{ route('paciente.citas.show', $cita->id) }}" 
               class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 hover:border-blue-500 hover:text-blue-600 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Confirmar Pago</h1>
                <p class="text-slate-500 mt-1 font-medium">Complete los detalles de su transferencia</p>
            </div>
        </div>

        {{-- Summary Banner Premium con Gradiente Dinámico --}}
        <div class="mb-8" style="animation: fadeIn 0.6s ease-out 0.1s both;">
            <div class="rounded-[2rem] p-8 shadow-2xl relative overflow-hidden" 
                 style="background: linear-gradient(135deg, var(--medical-500) 0%, var(--medical-600) 100%);">
                
                <!-- Orbes decorativos animados -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float-orb"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-2xl animate-float-orb-slow"></div>
                <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-white/10 rounded-full blur-3xl animate-float-orb-delayed"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center font-black text-3xl border-2 border-white/20 shadow-xl" 
                             style="color: var(--text-on-medical) !important;">
                            {{ substr($cita->medico->primer_nombre, 0, 1) }}{{ substr($cita->medico->primer_apellido, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold tracking-wide uppercase opacity-80" 
                               style="color: var(--text-on-medical) !important;">Médico Tratante</p>
                            <p class="text-3xl font-black tracking-tight mt-1" 
                               style="color: var(--text-on-medical) !important; text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                Dr. {{ $cita->medico->primer_nombre }} {{ $cita->medico->primer_apellido }}
                            </p>
                            <span class="inline-flex mt-3 px-4 py-1.5 bg-white/10 backdrop-blur-md rounded-xl text-sm font-bold border border-white/20 shadow-lg"
                                  style="color: var(--text-on-medical) !important;">
                                <i class="bi bi-star-fill mr-2"></i>
                                {{ $cita->especialidad->nombre }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-center md:text-right">
                        <div class="bg-white/15 backdrop-blur-md rounded-2xl p-6 border-2 border-white/20 shadow-2xl">
                            <p class="text-xs font-black uppercase tracking-wider mb-2" 
                               style="color: var(--text-on-medical) !important; opacity: 0.8;">Total a Pagar</p>
                            <div class="text-4xl font-black tracking-tight mb-2" 
                                 style="color: var(--text-on-medical) !important; text-shadow: 0 2px 15px rgba(0,0,0,0.15);">
                                Bs. {{ number_format($cita->tarifa_total * $tasaActual->valor, 2) }}
                            </div>
                            <p class="text-base font-bold" style="color: var(--text-on-medical) !important; opacity: 0.9;">
                                ${{ number_format($cita->tarifa_total, 2) }} USD
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Detalles de la cita -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 pt-6 border-t border-white/20">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center" style="color: var(--text-on-medical) !important;">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div>
                            <p class="text-xs opacity-70 font-semibold uppercase" style="color: var(--text-on-medical) !important;">Fecha</p>
                            <span class="font-black text-sm" style="color: var(--text-on-medical) !important;">
                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center" style="color: var(--text-on-medical) !important;">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div>
                            <p class="text-xs opacity-70 font-semibold uppercase" style="color: var(--text-on-medical) !important;">Hora</p>
                            <span class="font-black text-sm" style="color: var(--text-on-medical) !important;">
                                {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center" style="color: var(--text-on-medical) !important;">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <div>
                            <p class="text-xs opacity-70 font-semibold uppercase" style="color: var(--text-on-medical) !important;">Tasa</p>
                            <span class="font-black text-sm" style="color: var(--text-on-medical) !important;">
                                {{ number_format($tasaActual->valor, 2) }} Bs/$
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Línea decorativa inferior -->
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('paciente.pagos.store') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
            @csrf
            <input type="hidden" name="cita_id" value="{{ $cita->id }}">
            <input type="hidden" name="tasa_aplicada_id" value="{{ $tasaActual->id }}">
            <input type="hidden" name="id_metodo" id="selected_method">

            <div class="space-y-6">
                {{-- Payment Method --}}
                <div class="section-card" style="animation: scaleIn 0.5s ease-out 0.2s both;">
                    <div class="section-header">
                        <div class="icon-wrapper" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Método de Pago</h3>
                            <p class="text-sm text-gray-500">Selecciona cómo realizaste el pago</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($metodosPago as $metodo)
                            @if(in_array($metodo->codigo, ['EFECT', 'TRANSF', 'PAGOMOVIL']))
                            <div class="method-card" data-method="{{ $metodo->id_metodo }}" data-code="{{ $metodo->codigo }}" onclick="selectMethod({{ $metodo->id_metodo }}, '{{ $metodo->codigo }}')">
                            <div class="checkmark">
                                <i class="bi bi-check-lg text-white text-sm"></i>
                            </div>
                            <div class="text-center">
                                <div class="mb-3 text-4xl">
                                    @switch($metodo->codigo)
                                        @case('TRANSF')
                                            <i class="bi bi-bank" style="color: #3b82f6;"></i>
                                            @break
                                        @case('ZELLE')
                                            <i class="bi bi-currency-dollar" style="color: #8b5cf6;"></i>
                                            @break
                                        @case('PAGOMOVIL')
                                            <i class="bi bi-phone" style="color: #10b981;"></i>
                                            @break
                                        @case('EFECT')
                                            <i class="bi bi-cash-stack" style="color: #f59e0b;"></i>
                                            @break
                                        @case('TARJETA')
                                            <i class="bi bi-credit-card" style="color: #ef4444;"></i>
                                            @break
                                    @endswitch
                                </div>
                                <p class="font-semibold text-sm text-gray-700">{{ $metodo->nombre }}</p>
                            </div>
                        </div>
                            @endif
                        @endforeach
                    </div>
                    @error('id_metodo')
                        <p class="text-red-500 text-sm mt-3">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Common Fields (Always Visible) --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="section-card" style="animation: scaleIn 0.5s ease-out 0.3s both;">
                        <div class="section-header">
                            <div class="icon-wrapper" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Detalles del Pago</h3>
                                <p class="text-sm text-gray-500">Fecha y Monto</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            {{-- Date --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-calendar-event text-emerald-600 mr-1"></i>
                                    Fecha de Pago
                                </label>
                                <input type="date" name="fecha_pago" 
                                       class="premium-input w-full"
                                       value="{{ old('fecha_pago', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}" required>
                                @error('fecha_pago')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Amount --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-cash-coin text-emerald-600 mr-1"></i>
                                    Monto Pagado (Bs.)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Bs.</span>
                                    <input type="number" step="0.01" name="monto_pagado_bs" id="monto_bs" 
                                           class="premium-input w-full pl-14 text-2xl font-bold"
                                           placeholder="{{ number_format($cita->tarifa_total * $tasaActual->valor, 2) }}" 
                                           value="{{ old('monto_pagado_bs') }}" required>
                                </div>
                                <div class="mt-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600">Equivalente USD:</span>
                                        <span class="text-2xl font-black text-emerald-600" id="usd_display">$0.00</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i class="bi bi-info-circle mr-1"></i>
                                    Sugerido: <strong>Bs. {{ number_format($cita->tarifa_total * $tasaActual->valor, 2) }}</strong>
                                </p>
                                @error('monto_pagado_bs')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Bank Details & Upload Section (Right Column) --}}
                    <div class="col-span-1 space-y-6">
                        
                        {{-- Bank Info (Shown for Transfer/Zelle/PagoMovil) --}}
                        <div id="bank-details" class="section-card bg-blue-50 border border-blue-100 hidden" style="animation: scaleIn 0.5s ease-out;">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shrink-0 border-2 border-white">
                                    <i class="bi bi-bank text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2" id="bank-title">Datos Bancarios</h3>
                                    
                                    {{-- Transferencia --}}
                                    <div id="info-transf" class="hidden space-y-3">
                                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                                            <p class="text-xs text-blue-600 mb-1 font-semibold uppercase tracking-wider">Banco</p>
                                            <p class="font-bold text-gray-800">{{ $datosBancarios['transferencia']['banco'] }}</p>
                                        </div>
                                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                                            <p class="text-xs text-blue-600 mb-1 font-semibold uppercase tracking-wider">Número de Cuenta</p>
                                            <p class="font-bold text-gray-800 font-mono tracking-wide text-sm md:text-base">{{ $datosBancarios['transferencia']['cuenta'] }}</p>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                <p class="text-xs text-blue-600 mb-1 font-semibold uppercase tracking-wider">Titular</p>
                                                <p class="font-bold text-gray-800 text-sm">{{ $datosBancarios['transferencia']['titular'] }}</p>
                                            </div>
                                            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                <p class="text-xs text-blue-600 mb-1 font-semibold uppercase tracking-wider">RIF</p>
                                                <p class="font-bold text-gray-800 text-sm">{{ $datosBancarios['transferencia']['rif'] }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pago Movil --}}
                                    <div id="info-pagomovil" class="hidden space-y-3">
                                        <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                            <p class="text-xs text-emerald-600 mb-1 font-semibold uppercase tracking-wider">Banco</p>
                                            <p class="font-bold text-gray-800">{{ $datosBancarios['pagomovil']['banco'] }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                                <p class="text-xs text-emerald-600 mb-1 font-semibold uppercase tracking-wider">Teléfono</p>
                                                <p class="font-bold text-gray-800 font-mono">{{ $datosBancarios['pagomovil']['telefono'] }}</p>
                                            </div>
                                            <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                                <p class="text-xs text-emerald-600 mb-1 font-semibold uppercase tracking-wider">RIF</p>
                                                <p class="font-bold text-gray-800">{{ $datosBancarios['pagomovil']['rif'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 pt-3 border-t border-blue-200">
                                        <p class="text-xs text-blue-800">
                                            <i class="bi bi-info-circle-fill mr-1"></i>
                                            Realice el pago y luego registre la referencia abajo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    {{-- Transfer Specific Fields (Hidden for Cash) --}}
                    <div id="transfer-details" class="section-card transition-all duration-300" style="animation: scaleIn 0.5s ease-out 0.4s both;">
                        <div class="section-header">
                            <div class="icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="bi bi-credit-card-2-front"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Comprobante</h3>
                                <p class="text-sm text-gray-500">Referencia y Archivo</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            {{-- Reference --}}
                            <div id="reference-field">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="bi bi-hash text-emerald-600 mr-1"></i>
                                    Número de Referencia
                                </label>
                                <input type="text" name="referencia" id="referencia_input"
                                       class="premium-input w-full font-mono tracking-wider uppercase"
                                       placeholder="Ej: 12345678" value="{{ old('referencia') }}" required>
                                @error('referencia')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- File Upload --}}
                            <div class="upload-area" id="upload-zone"
                                 ondrop="handleDrop(event)" ondragover="allowDrop(event)" ondragleave="removeDrag(event)">
                                <input type="file" name="comprobante" id="comprobante" accept="image/*,application/pdf" class="hidden" onchange="showPreview(event)">
                                
                                <div id="upload-placeholder">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-emerald-50 flex items-center justify-center">
                                        <i class="bi bi-cloud-upload text-3xl text-emerald-600"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-700">Arrastra tu comprobante</p>
                                    <button type="button" onclick="document.getElementById('comprobante').click()" 
                                            class="mt-2 text-xs text-emerald-600 font-bold hover:underline">
                                        Seleccionar Archivo
                                    </button>
                                </div>

                                <div id="file-preview" class="hidden">
                                    <img id="preview-img" class="max-h-32 rounded-lg mx-auto mb-2 shadow-sm">
                                    <p class="text-xs font-bold text-emerald-600 mb-2" id="file-name"></p>
                                    <button type="button" onclick="clearFile()" 
                                            class="text-xs text-red-500 hover:text-red-700 font-medium">
                                        <i class="bi bi-x-circle"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cash Instruction (Hidden by default, shown in right column) --}}
                    <div id="cash-instruction" class="hidden section-card border-l-4 border-l-yellow-400 bg-yellow-50 h-full flex flex-col justify-center" style="animation: scaleIn 0.5s ease-out;">
                        <div class="flex flex-col items-center text-center p-6 gap-4">
                            <div class="w-20 h-20 rounded-full bg-yellow-100 flex items-center justify-center shrink-0 shadow-sm border-4 border-white">
                                <i class="bi bi-cash-coin text-yellow-600 text-4xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Pago Presencial</h3>
                                <div class="bg-white/60 p-4 rounded-xl border border-yellow-200 mb-4">
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        <i class="bi bi-info-circle-fill text-yellow-500 mr-1"></i>
                                        El pago se realizará fisicamente en la caja del consultorio.
                                    </p>
                                </div>
                                <p class="text-yellow-800 font-bold text-sm bg-yellow-100 px-4 py-2 rounded-lg inline-block">
                                    Total a pagar en caja: Bs. <span id="cash-amount-display">0.00</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                {{-- Comments (Always Visible) --}}
                <div class="section-card mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="bi bi-chat-left-text text-emerald-600 mr-1"></i>
                        Notas Adicionales
                    </label>
                    <textarea name="comentarios" rows="3" 
                              class="premium-input w-full resize-none"
                              placeholder="Información adicional sobre el pago...">{{ old('comentarios') }}</textarea>
                </div>

                {{-- Submit --}}
                <div class="section-card flex items-center justify-between" style="animation: scaleIn 0.5s ease-out 0.5s both;">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="bi bi-shield-check text-emerald-600 text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Pago Seguro</p>
                            <p class="text-sm text-gray-500">Verificación en menos de 24h</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('paciente.citas.show', $cita->id) }}" 
                           class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all hover:shadow-xl hover:shadow-emerald-300 hover:-translate-y-0.5">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            Confirmar Pago
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const tasa = {{ $tasaActual->valor }};
    let selectedMethodId = null;

    // Amount conversion and input validation
    const montoInput = document.getElementById('monto_bs');
    
    montoInput.addEventListener('keydown', (e) => {
        if (e.key === ',') {
            e.preventDefault();
        }
    });

    montoInput.addEventListener('input', (e) => {
        // Remove any commas if pasted
        if (e.target.value.includes(',')) {
            e.target.value = e.target.value.replace(/,/g, '');
        }
        
        const bs = parseFloat(e.target.value) || 0;
        const usd = bs / tasa;
        document.getElementById('usd_display').textContent = '$' + usd.toFixed(2);
        
        // Update cash amount display if enabled
        const cashDisplay = document.getElementById('cash-amount-display');
        if (cashDisplay) {
            cashDisplay.textContent = bs.toFixed(2);
        }
    });

    // Select payment method
    function selectMethod(id, code) {
        document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
        document.querySelector(`[data-method="${id}"]`).classList.add('selected');
        document.getElementById('selected_method').value = id;
        selectedMethodId = id;

        const transferDetails = document.getElementById('transfer-details'); // Card de Comprobante y Referencia
        const cashInstruction = document.getElementById('cash-instruction');
        const bankDetails = document.getElementById('bank-details');
        const referenciaInput = document.getElementById('referencia_input');

        // Reset bank infos
        ['info-transf', 'info-pagomovil'].forEach(id => {
            const el = document.getElementById(id);
            if(el) el.classList.add('hidden');
        });

        if (code === 'EFECT') {
            // EFECTIVO
            transferDetails.classList.add('hidden');
            bankDetails.classList.add('hidden');
            cashInstruction.classList.remove('hidden');
            
            referenciaInput.removeAttribute('required');
            referenciaInput.value = 'EFECTIVO-EN-SITIO'; 
            
        } else {
            // DIGITAL / TRANSFERENCIA
            transferDetails.classList.remove('hidden');
            cashInstruction.classList.add('hidden');
            bankDetails.classList.add('hidden'); // Default hide, show beneath if matches

            // Mostrar datos bancarios si aplican
            if (['TRANSF', 'PAGOMOVIL'].includes(code)) {
                bankDetails.classList.remove('hidden');
                
                if (code === 'TRANSF') {
                    document.getElementById('info-transf').classList.remove('hidden');
                    document.getElementById('bank-title').textContent = 'Datos para Transferencia';
                } else if (code === 'PAGOMOVIL') {
                    document.getElementById('info-pagomovil').classList.remove('hidden');
                    document.getElementById('bank-title').textContent = 'Datos Pago Móvil';
                }
            } else {
                // Tarjeta, etc (si hubiera pasarela online, aquí iría, por ahora asumimos POS físico o similar con referencia)
                // Si es Tarjeta/POS físico, actuamos parecido a Efectivo o Transferencia con referencia del voucher
                 bankDetails.classList.add('hidden');
            }
            
            referenciaInput.setAttribute('required', 'required');
            if (referenciaInput.value === 'EFECTIVO-EN-SITIO') {
                referenciaInput.value = '';
            }
        }
    }

    // Form validation submit handler
    document.getElementById('paymentForm').addEventListener('submit', (e) => {
        if (!selectedMethodId) {
            e.preventDefault();
            alert('Por favor selecciona un método de pago');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return false;
        }
        // Validar monto para efectivo
        const monto = parseFloat(document.getElementById('monto_bs').value);
        if (isNaN(monto) || monto <= 0) {
             e.preventDefault();
             alert('Por favor ingrese un monto válido');
             return false;
        }
        
        return true;
    });

    // File handling
    function allowDrop(e) {
        e.preventDefault();
        document.getElementById('upload-zone').classList.add('dragover');
    }

    function removeDrag(e) {
        document.getElementById('upload-zone').classList.remove('dragover');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('upload-zone').classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            document.getElementById('comprobante').files = e.dataTransfer.files;
            showPreview({target: {files: [file]}});
        }
    }

    function showPreview(e) {
        const file = e.target.files[0];
        if (!file) return;

        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('file-preview').classList.remove('hidden');
        document.getElementById('file-name').textContent = file.name;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview-img').classList.add('hidden');
        }
    }

    function clearFile() {
        document.getElementById('comprobante').value = '';
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('file-preview').classList.add('hidden');
    }

    // Form validation
    document.getElementById('paymentForm').addEventListener('submit', (e) => {
        if (!selectedMethodId) {
            e.preventDefault();
            alert('Por favor selecciona un método de pago');
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    });
</script>
@endsection
