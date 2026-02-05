@extends('layouts.auth')

@section('title', 'Restablecimiento de Emergencia')

@section('auth-content')
<!-- Header Icon -->
<div class="flex justify-center mb-6">
    <div class="w-16 h-16 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
        <i class="bi bi-shield-exclamation text-3xl"></i>
    </div>
</div>

<!-- Title & Description -->
<div class="text-center mb-8">
    <h2 class="text-3xl font-display font-bold text-slate-900 tracking-tight">
        Configuración Segura
    </h2>
    <p class="mt-2 text-sm text-slate-500">
        Ingresa tu contraseña actual y define nuevas preguntas de seguridad.
    </p>
</div>

<form method="POST" action="{{ route('auth.emergency-reset.submit') }}" id="resetForm" class="space-y-6">
    @csrf

    <!-- Auth Section -->
    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-4">
        <h3 class="text-sm font-bold text-slate-700 mb-3 border-b flex items-center gap-2">
            <i class="bi bi-person-badge"></i> Credenciales Actuales
        </h3>
        
        <!-- Email -->
        <div class="group mb-4">
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="block w-full px-4 py-3 text-sm border-2 border-slate-200 rounded-lg focus:ring-2 focus:ring-red-500/20 focus:border-red-500" required placeholder="tu@email.com">
        </div>

        <!-- Password -->
        <div class="group">
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Contraseña Actual</label>
            <input type="password" name="password" id="password" class="block w-full px-4 py-3 text-sm border-2 border-slate-200 rounded-lg focus:ring-2 focus:ring-red-500/20 focus:border-red-500" required placeholder="••••••••">
        </div>
    </div>

    <!-- Questions Section -->
    <div class="space-y-4">
        <h3 class="text-sm font-bold text-slate-700 border-b pb-2 flex items-center gap-2">
            <i class="bi bi-shield-check"></i> Nuevas Preguntas de Seguridad
        </h3>

        @for($i = 1; $i <= 3; $i++)
        <div class="bg-white p-3 rounded-lg border border-slate-100 shadow-sm">
            <label class="block text-xs font-bold text-slate-500 mb-1">Pregunta {{ $i }}</label>
            <select name="question_{{ $i }}" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500 mb-2" required>
                @foreach($preguntasCatalogo as $pregunta)
                    <option value="{{ $pregunta->id }}">{{ $pregunta->pregunta }}</option>
                @endforeach
            </select>
            <input type="text" name="answer_{{ $i }}" class="block w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500" placeholder="Respuesta {{ $i }}" required minlength="2">
        </div>
        @endfor
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
        <button 
            type="submit" 
            class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-[1.02] transition-all duration-200"
        >
            <i class="bi bi-save2-fill mr-2"></i>
            Guardar Configuración Segura
        </button>
    </div>
</form>

<div class="text-center mt-4">
    <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-slate-700">Volver al Login</a>
</div>
@endsection
