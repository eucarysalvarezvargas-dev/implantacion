@extends('layouts.admin')

@section('title', 'Métodos de Pago')

@section('content')
<div class="mb-6">
    <a href="{{ route('configuracion.index') }}" class="text-medical-600 hover:text-medical-700 inline-flex items-center text-sm font-medium mb-3">
        <i class="bi bi-arrow-left mr-1"></i> Volver a Configuración
    </a>
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-display font-bold text-gray-900">Métodos de Pago</h2>
            <p class="text-gray-500 mt-1">Bancos, pasarelas y formas de pago aceptadas</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#nuevoMetodoModal">
            <i class="bi bi-plus-lg mr-2"></i>
            Nuevo Método
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Transferencia Bancaria -->
    <div class="card p-6 border-t-4 border-t-medical-500 group hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-medical-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-bank2 text-medical-600 text-2xl"></i>
            </div>
            <span class="badge badge-success">Activo</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Transferencia Bancaria</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $datosBancarios['transferencia']['banco'] ?: 'Banco no configurado' }}</p>
        
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Titular:</span>
                <span class="font-medium">{{ Str::limit($datosBancarios['transferencia']['titular'], 20) ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Cuenta:</span>
                <span class="font-mono font-medium text-xs">{{ $datosBancarios['transferencia']['cuenta'] ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">RIF:</span>
                <span class="font-medium">{{ $datosBancarios['transferencia']['rif'] ?: '-' }}</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            <button class="btn btn-sm btn-outline flex-1" data-toggle="modal" data-target="#modalEditarTransferencia">
                <i class="bi bi-pencil"></i> Editar
            </button>
        </div>
    </div>

    <!-- Pago Móvil -->
    <div class="card p-6 border-t-4 border-t-success-500 group hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-success-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-phone text-success-600 text-2xl"></i>
            </div>
            <span class="badge badge-success">Activo</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Pago Móvil</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $datosBancarios['pagomovil']['banco'] ?: 'Banco no configurado' }}</p>
        
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Banco:</span>
                <span class="font-medium">{{ Str::limit($datosBancarios['pagomovil']['banco'], 20) ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Teléfono:</span>
                <span class="font-mono font-medium">{{ $datosBancarios['pagomovil']['telefono'] ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">RIF:</span>
                <span class="font-medium">{{ $datosBancarios['pagomovil']['rif'] ?: '-' }}</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            <button class="btn btn-sm btn-outline flex-1" data-toggle="modal" data-target="#modalEditarPagoMovil">
                <i class="bi bi-pencil"></i> Editar
            </button>
        </div>
    </div>

    <!-- Efectivo USD -->
    <div class="card p-6 border-t-4 border-t-warning-500 group hover:shadow-xl transition-all">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-warning-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-cash-stack text-warning-600 text-2xl"></i>
            </div>
            <span class="badge badge-success">Activo</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Efectivo (USD)</h3>
        <p class="text-sm text-gray-500 mb-4">Dólares americanos en caja</p>
        
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Moneda:</span>
                <span class="font-medium">Dólares (USD)</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Destino:</span>
                <span class="font-medium">Caja Principal</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Nota:</span>
                <span class="font-medium text-xs">Confirmación inmediata</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            <button class="btn btn-sm btn-outline flex-1">
                <i class="bi bi-pencil"></i> Editar
            </button>
            <button class="btn btn-sm btn-ghost text-danger-600">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    <!-- Tarjeta de Débito/Crédito -->
    <div class="card p-6 border-t-4 border-t-info-500 group hover:shadow-xl transition-all opacity-75">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-info-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-credit-card text-info-600 text-2xl"></i>
            </div>
            <span class="badge badge-gray">Inactivo</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Punto de Venta</h3>
        <p class="text-sm text-gray-500 mb-4">TDD / TDC - Conexión bancaria</p>
        
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Terminal:</span>
                <span class="font-medium">No configurado</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Afiliación:</span>
                <span class="font-medium">-</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            <button class="btn btn-sm btn-success flex-1">
                <i class="bi bi-check-lg"></i> Activar
            </button>
            <button class="btn btn-sm btn-ghost text-danger-600">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    <!-- PayPal (Placeholder) -->
    <div class="card p-6 border-t-4 border-t-premium-500 group hover:shadow-xl transition-all opacity-75">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-premium-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-paypal text-premium-600 text-2xl"></i>
            </div>
            <span class="badge badge-gray">Inactivo</span>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">PayPal</h3>
        <p class="text-sm text-gray-500 mb-4">Pasarela internacional</p>
        
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">API:</span>
                <span class="font-medium">No configurado</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Comisión:</span>
                <span class="font-medium">4.5% + $0.30</span>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            <button class="btn btn-sm btn-outline flex-1">
                <i class="bi bi-gear"></i> Configurar
            </button>
            <button class="btn btn-sm btn-ghost text-danger-600">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    <!-- Botón Agregar Nuevo -->
    <button class="card p-6 border-2 border-dashed border-gray-300 hover:border-medical-400 hover:bg-medical-50/30 transition-all flex flex-col items-center justify-center text-center group cursor-pointer">
        <div class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-medical-100 flex items-center justify-center mb-4 transition-colors">
            <i class="bi bi-plus-lg text-3xl text-gray-400 group-hover:text-medical-600"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-600 group-hover:text-medical-700 transition-colors">Agregar Nuevo</h3>
        <p class="text-sm text-gray-500 mt-2">Configura un nuevo método de pago</p>
    </button>
</div>

<!-- Opciones Globales -->
<div class="card p-6 mt-6">
    <h3 class="text-xl font-bold text-gray-900 mb-6">Configuración Global de Pagos</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                <input type="checkbox" class="form-checkbox mt-0.5 h-5 w-5 text-medical-600" checked>
                <div>
                    <span class="font-semibold text-gray-900 block mb-1">Requerir Comprobante</span>
                    <p class="text-sm text-gray-500">Solicitar imagen del comprobante al registrar pagos en transferencias</p>
                </div>
            </label>
        </div>
        
        <div>
            <label class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                <input type="checkbox" class="form-checkbox mt-0.5 h-5 w-5 text-medical-600">
                <div>
                    <span class="font-semibold text-gray-900 block mb-1">Pagos Parciales</span>
                    <p class="text-sm text-gray-500">Permitir que los pacientes realicen abonos o pagos parciales</p>
                </div>
            </label>
        </div>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal Editar Transferencia -->
<div class="modal fade" id="modalEditarTransferencia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-2xl">
            <div class="modal-header border-b border-gray-100 p-6">
                <h5 class="text-xl font-bold text-gray-900">Editar Transferencia Bancaria</h5>
                <button type="button" class="close text-gray-400 hover:text-gray-600" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('configuracion.metodos-pago.bancarios') }}" method="POST">
                @csrf
                <div class="modal-body p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Banco</label>
                        <input type="text" name="banco_transferencia_banco" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500" 
                               value="{{ $datosBancarios['transferencia']['banco'] }}" placeholder="Ej: Banco Mercantil">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Número de Cuenta</label>
                        <input type="text" name="banco_transferencia_cuenta" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500 font-mono" 
                               value="{{ $datosBancarios['transferencia']['cuenta'] }}" placeholder="0105-...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titular de la Cuenta</label>
                        <input type="text" name="banco_transferencia_titular" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500" 
                               value="{{ $datosBancarios['transferencia']['titular'] }}" placeholder="Nombre del Titular">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RIF / Documento</label>
                        <input type="text" name="banco_transferencia_rif" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500 uppercase" 
                               value="{{ $datosBancarios['transferencia']['rif'] }}" placeholder="J-12345678-9">
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-end gap-3">
                    <button type="button" class="btn btn-ghost text-gray-600" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-medical-600 hover:bg-medical-700 text-white border-0 shadow-lg shadow-medical-200">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Pago Móvil -->
<div class="modal fade" id="modalEditarPagoMovil" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-2xl">
            <div class="modal-header border-b border-gray-100 p-6">
                <h5 class="text-xl font-bold text-gray-900">Editar Pago Móvil</h5>
                <button type="button" class="close text-gray-400 hover:text-gray-600" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('configuracion.metodos-pago.bancarios') }}" method="POST">
                @csrf
                <div class="modal-body p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Banco</label>
                        <input type="text" name="banco_pagomovil_banco" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500" 
                               value="{{ $datosBancarios['pagomovil']['banco'] }}" placeholder="Ej: Banesco (0134)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="banco_pagomovil_telefono" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500 font-mono" 
                               value="{{ $datosBancarios['pagomovil']['telefono'] }}" placeholder="0414-1234567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RIF / Cédula</label>
                        <input type="text" name="banco_pagomovil_rif" class="form-input w-full rounded-lg border-gray-300 focus:border-medical-500 focus:ring-medical-500 uppercase" 
                               value="{{ $datosBancarios['pagomovil']['rif'] }}" placeholder="V-12345678">
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-end gap-3">
                    <button type="button" class="btn btn-ghost text-gray-600" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary bg-medical-600 hover:bg-medical-700 text-white border-0 shadow-lg shadow-medical-200">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
