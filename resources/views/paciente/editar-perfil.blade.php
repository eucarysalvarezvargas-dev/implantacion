@extends('layouts.paciente')

@section('title', 'Editar Mi Perfil')

@section('content')
<!-- Header con Glassmorphism Premium -->
<div class="relative bg-white dark:bg-gray-800 rounded-3xl p-6 lg:p-10 shadow-xl overflow-hidden mb-8 border border-slate-100 dark:border-gray-700 animate-fade-in-down">
    <!-- Decorative Elements -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-medical-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>

    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('paciente.dashboard') }}" class="group w-8 h-8 rounded-full bg-white/50 hover:bg-white dark:bg-gray-700/50 dark:hover:bg-gray-700 flex items-center justify-center transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-slate-600 dark:text-gray-300 group-hover:text-medical-600 dark:group-hover:text-medical-400"></i>
                </a>
                <div class="h-6 w-px bg-slate-300 dark:bg-gray-600 mx-1"></div>
                <div class="p-2 bg-medical-100 dark:bg-medical-900/30 rounded-lg text-medical-600 dark:text-medical-400">
                    <i class="bi bi-person-gear text-xl"></i>
                </div>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Editar Perfil
            </h1>
            <p class="text-slate-500 dark:text-gray-400 text-lg mt-1 font-medium">Actualiza tu información personal y mantén tu cuenta segura.</p>
        </div>
        
        <div class="hidden md:block">
            <div class="relative group cursor-default">
                 <div class="absolute inset-0 bg-medical-500 blur-xl opacity-20 group-hover:opacity-30 transition-opacity rounded-full"></div>
                 <img src="{{ auth()->user()->paciente->foto_perfil ? asset('storage/' . auth()->user()->paciente->foto_perfil) : asset('images/default-avatar.png') }}" 
                      onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->paciente->primer_nombre) }}&background=10b981&color=fff'"
                      class="relative w-24 h-24 rounded-full border-4 border-white dark:border-gray-800 shadow-2xl object-cover transform group-hover:scale-105 transition-transform duration-300">
                 <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full shadow-sm z-20" title="Activo"></div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('paciente.perfil.update') }}" method="POST" enctype="multipart/form-data" class="max-w-7xl mx-auto">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Main Form Area -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- SECTION 1: Personal Information -->
            <div class="group bg-white dark:bg-gray-800 rounded-3xl p-0 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden animate-slide-in-up" style="animation-delay: 0ms;">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-medical-500"></div>
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-50 dark:border-gray-700/50">
                        <div class="w-12 h-12 rounded-2xl bg-medical-50 dark:bg-medical-900/20 flex items-center justify-center text-medical-600 dark:text-medical-400 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                            <i class="bi bi-person-vcard text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Información Personal</h3>
                            <p class="text-sm text-slate-500 dark:text-gray-400">Datos básicos de identificación.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Primer Nombre <span class="text-rose-500">*</span></label>
                            <div class="relative group/input">
                                <i class="bi bi-person absolute left-4 top-3.5 text-slate-400 group-focus-within/input:text-medical-500 transition-colors"></i>
                                <input type="text" name="primer_nombre" value="{{ old('primer_nombre', $paciente->primer_nombre) }}" required
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-semibold text-slate-700 dark:text-gray-200 placeholder-slate-400">
                            </div>
                            @error('primer_nombre') <p class="text-xs text-rose-500 font-bold mt-1 ml-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Segundo Nombre</label>
                            <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $paciente->segundo_nombre) }}"
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Primer Apellido <span class="text-rose-500">*</span></label>
                            <div class="relative group/input">
                                <i class="bi bi-card-text absolute left-4 top-3.5 text-slate-400 group-focus-within/input:text-medical-500 transition-colors"></i>
                                <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $paciente->primer_apellido) }}" required
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-semibold text-slate-700 dark:text-gray-200">
                            </div>
                             @error('primer_apellido') <p class="text-xs text-rose-500 font-bold mt-1 ml-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Segundo Apellido</label>
                            <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $paciente->segundo_apellido) }}"
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nac" value="{{ old('fecha_nac', $paciente->fecha_nac) }}"
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Género</label>
                            <div class="relative">
                                <select name="genero" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer">
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino" {{ old('genero', $paciente->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ old('genero', $paciente->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ old('genero', $paciente->genero) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Ocupación</label>
                            <input type="text" name="ocupacion" value="{{ old('ocupacion', $paciente->ocupacion) }}" placeholder="Ej: Ingeniero, Estudiante..."
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Estado Civil</label>
                            <div class="relative">
                                <select name="estado_civil" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-medical-500 focus:border-medical-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer">
                                    <option value="">Seleccione...</option>
                                    <option value="Soltero" {{ old('estado_civil', $paciente->estado_civil) == 'Soltero' ? 'selected' : '' }}>Soltero(a)</option>
                                    <option value="Casado" {{ old('estado_civil', $paciente->estado_civil) == 'Casado' ? 'selected' : '' }}>Casado(a)</option>
                                    <option value="Divorciado" {{ old('estado_civil', $paciente->estado_civil) == 'Divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                                    <option value="Viudo" {{ old('estado_civil', $paciente->estado_civil) == 'Viudo' ? 'selected' : '' }}>Viudo(a)</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Contact Info -->
             <div class="group bg-white dark:bg-gray-800 rounded-3xl p-0 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden animate-slide-in-up" style="animation-delay: 100ms;">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-50 dark:border-gray-700/50">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                            <i class="bi bi-telephone text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Contacto</h3>
                            <p class="text-sm text-slate-500 dark:text-gray-400">Medios para comunicarnos contigo.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Prefijo</label>
                            <div class="relative">
                                <select name="prefijo_tlf" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer">
                                    <option value="">Seleccione...</option>
                                    <option value="+58" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+58' ? 'selected' : '' }}>+58 (Venezuela)</option>
                                    <option value="+57" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+57' ? 'selected' : '' }}>+57 (Colombia)</option>
                                    <option value="+1" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+1' ? 'selected' : '' }}>+1 (USA/Canadá)</option>
                                    <option value="+34" {{ old('prefijo_tlf', $paciente->prefijo_tlf) == '+34' ? 'selected' : '' }}>+34 (España)</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Número Teléfono</label>
                            <div class="relative group/input">
                                <i class="bi bi-phone absolute left-4 top-3.5 text-slate-400 group-focus-within/input:text-blue-500 transition-colors"></i>
                                <input type="text" name="numero_tlf" value="{{ old('numero_tlf', $paciente->numero_tlf) }}" placeholder="Ej: 4241234567"
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Location -->
             <div class="group bg-white dark:bg-gray-800 rounded-3xl p-0 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden animate-slide-in-up" style="animation-delay: 200ms;">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-purple-500"></div>
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-50 dark:border-gray-700/50">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                            <i class="bi bi-geo-alt text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Dirección y Ubicación</h3>
                            <p class="text-sm text-slate-500 dark:text-gray-400">¿Dónde resides actualmente?</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Estado</label>
                             <div class="relative">
                                <select name="estado_id" id="estado_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer">
                                    <option value="">Seleccione...</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id_estado }}" {{ old('estado_id', $paciente->estado_id) == $estado->id_estado ? 'selected' : '' }}>
                                            {{ $estado->estado }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Ciudad</label>
                             <div class="relative">
                                <select name="ciudad_id" id="ciudad_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer disabled:opacity-50">
                                    <option value="">Seleccione Estado primero...</option>
                                     @foreach($ciudades as $ciudad)
                                        <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id', $paciente->ciudad_id) == $ciudad->id_ciudad ? 'selected' : '' }}>
                                            {{ $ciudad->ciudad }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Municipio</label>
                             <div class="relative">
                                <select name="municipio_id" id="municipio_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer disabled:opacity-50">
                                    <option value="">Seleccione Estado primero...</option>
                                    @foreach($municipios as $municipio)
                                        <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id', $paciente->municipio_id) == $municipio->id_municipio ? 'selected' : '' }}>
                                            {{ $municipio->municipio }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Parroquia</label>
                             <div class="relative">
                                <select name="parroquia_id" id="parroquia_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 appearance-none cursor-pointer disabled:opacity-50">
                                    <option value="">Seleccione Municipio primero...</option>
                                     @foreach($parroquias as $parroquia)
                                        <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id', $paciente->parroquia_id) == $parroquia->id_parroquia ? 'selected' : '' }}>
                                            {{ $parroquia->parroquia }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                     <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Dirección Detallada</label>
                        <div class="relative group/input">
                            <textarea name="direccion_detallada" rows="3" placeholder="Ej: Urbanización Los Mangos, Casa Nro 5..."
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200 resize-none">{{ old('direccion_detallada', $paciente->direccion_detallada) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Security (Optional) -->
            <div class="group bg-white dark:bg-gray-800 rounded-3xl p-0 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden animate-slide-in-up" style="animation-delay: 300ms;">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>
                <div class="p-8">
                    <div class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-50 dark:border-gray-700/50">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                            <i class="bi bi-shield-lock text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white">Seguridad de la Cuenta</h3>
                            <p class="text-sm text-slate-500 dark:text-gray-400">Actualiza tu contraseña si es necesario.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Nueva Contraseña</label>
                             <div class="relative group/input">
                                <i class="bi bi-lock absolute left-4 top-3.5 text-slate-400 group-focus-within/input:text-amber-500 transition-colors"></i>
                                <input type="password" name="password" placeholder="Mínimo 8 caracteres"
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                            </div>
                        </div>

                         <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider ml-1">Confirmar Contraseña</label>
                             <div class="relative group/input">
                                <i class="bi bi-lock-fill absolute left-4 top-3.5 text-slate-400 group-focus-within/input:text-amber-500 transition-colors"></i>
                                <input type="password" name="password_confirmation" placeholder="Repite la contraseña"
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-gray-900/50 border border-slate-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all font-medium text-slate-700 dark:text-gray-200">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-gray-700/50">
                        <a href="{{ route('paciente.security-questions') }}" class="flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-100 dark:border-amber-800/30 hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                                    <i class="bi bi-shield-check text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 dark:text-white text-sm">Preguntas de Seguridad</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-400">Configura tus preguntas para recuperar el acceso</p>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-slate-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar: Photo & Stats -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Photo Upload Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-slate-100 dark:border-gray-700 shadow-lg hover:shadow-xl transition-all duration-300 relative group animate-fade-in">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Foto de Perfil</h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400">Esta imagen te representará en el sistema</p>
                </div>
                
                <div class="flex justify-center mb-6">
                    <div class="relative group/a cursor-pointer" onclick="document.getElementById('foto_perfil').click()">
                        <div class="absolute -inset-1 bg-gradient-to-br from-medical-400 to-blue-500 rounded-full blur opacity-50 group-hover/a:opacity-75 transition duration-500"></div>
                         @if($paciente->foto_perfil)
                            <img id="preview_image" src="{{ asset('storage/' . $paciente->foto_perfil) }}" class="relative w-40 h-40 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-xl z-10 transition-transform transform group-hover/a:scale-105">
                        @else
                            <div id="preview_image_container" class="relative w-40 h-40 rounded-full bg-slate-100 dark:bg-gray-700 border-4 border-white dark:border-gray-800 shadow-xl z-10 flex items-center justify-center text-4xl text-slate-400 group-hover/a:scale-105 transition-transform">
                                <i class="bi bi-camera"></i>
                            </div>
                        @endif
                        <div class="absolute inset-x-0 bottom-0 py-2 bg-black/60 rounded-b-full z-20 opacity-0 group-hover/a:opacity-100 transition-opacity flex justify-center">
                            <span class="text-white text-xs font-bold uppercase tracking-widest"><i class="bi bi-pencil shadow-sm"></i></span>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <input type="file" id="foto_perfil" name="foto_perfil" class="hidden" accept="image/*" onchange="previewImage(event)">
                    <button type="button" onclick="document.getElementById('foto_perfil').click()" 
                            class="px-5 py-2.5 bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-gray-600 transition-colors w-full">
                        <i class="bi bi-cloud-upload mr-2"></i> Cambiar Imagen
                    </button>
                    <p class="text-[10px] text-slate-400 mt-2">JPG, PNG, WEBP max 2MB</p>
                </div>
            </div>

            <!-- Banner Customization -->
             <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 animate-fade-in" style="animation-delay: 100ms;">
                <div class="mb-4">
                     <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Personalización</h3>
                     <p class="text-xs text-slate-500 dark:text-gray-400">Elige un tema para tu dashboard</p>
                </div>

                <div class="mb-6">
                    <div class="relative h-32 rounded-2xl overflow-hidden border border-slate-200 dark:border-gray-700 group/banner">
                        <div id="banner_preview" class="absolute inset-0 bg-cover bg-center transition-all duration-300"
                             style="@if($paciente->banner_perfil) background-image: url('{{ asset('storage/' . $paciente->banner_perfil) }}'); @else background-color: {{ $paciente->banner_color ?? '#10b981' }}; @endif">
                             <!-- Dynamic Gradient/Image Preview -->
                             @if(!$paciente->banner_perfil && $paciente->banner_color && !str_starts_with($paciente->banner_color, '#'))
                                <div class="w-full h-full {{ $paciente->banner_color }}"></div>
                             @endif
                        </div>
                        <div class="absolute inset-0 bg-black/10 group-hover/banner:bg-black/20 transition-colors"></div>
                        <div class="absolute bottom-3 right-3">
                            <button type="button" onclick="document.getElementById('banner_perfil').click()" 
                                    class="p-2 bg-white/90 dark:bg-gray-800/90 text-slate-700 dark:text-gray-200 rounded-lg shadow-sm hover:scale-105 transition-transform text-xs font-bold flex items-center gap-2 backdrop-blur-sm">
                                <i class="bi bi-image"></i> Cambiar Banner
                            </button>
                        </div>
                        <input type="file" id="banner_perfil" name="banner_perfil" class="hidden" accept="image/*" onchange="previewBanner(event)">
                    </div>
                </div>

                <div class="grid grid-cols-5 gap-2 mb-4">
                    @php
                        $colors = [
                            // Cool & Calm
                            'bg-gradient-to-r from-emerald-100 via-green-100 to-blue-100',
                            'bg-gradient-to-r from-blue-100 via-indigo-100 to-purple-100',
                            'bg-gradient-to-r from-cyan-500 to-blue-500',
                            'bg-gradient-to-r from-sky-400 to-indigo-500',
                            'bg-gradient-to-r from-teal-400 to-emerald-500',
                            
                            // Warm & Energetic
                            'bg-gradient-to-r from-rose-400 to-orange-500',
                            'bg-gradient-to-r from-amber-200 via-orange-300 to-red-400',
                            'bg-gradient-to-r from-pink-500 to-rose-500',
                            
                            // Dark & Sophisticated
                            'bg-gradient-to-r from-slate-700 to-slate-900',
                            'bg-gradient-to-r from-gray-700 via-gray-900 to-black',
                            'bg-gradient-to-r from-stone-500 to-stone-700',

                            // Special
                            'bg-gradient-to-r from-medical-500 to-teal-700',
                            'bg-gradient-to-r from-indigo-400 to-cyan-400',
                        ];
                    @endphp
                     @foreach($colors as $color)
                        <button type="button" 
                                onclick="setBannerColor('{{ $color }}')"
                                class="w-8 h-8 rounded-full {{ $color }} border-2 {{ ($paciente->banner_color == $color) ? 'border-medical-500 shadow-md transform scale-110' : 'border-transparent hover:border-slate-300 hover:scale-105' }} transition-all focus:outline-none"></button>
                    @endforeach
                    
                    <!-- Custom Color Picker -->
                    <div class="relative w-8 h-8 rounded-full bg-white dark:bg-gray-700 border-2 border-slate-200 dark:border-gray-600 hover:border-medical-500 transition-colors flex items-center justify-center cursor-pointer overflow-hidden">
                         <input type="color" id="custom_color_picker" 
                                onchange="setBannerColor(this.value)"
                                class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-10">
                         <i class="bi bi-palette text-slate-400 dark:text-gray-300 text-sm"></i>
                    </div>

                    <input type="hidden" name="banner_color" id="banner_color_input" value="{{ $paciente->banner_color }}">
                </div>

                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-gray-900/50 rounded-2xl border border-slate-100 dark:border-gray-700">
                    <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Tema Dinámico</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tema_dinamico" value="1" class="sr-only peer" {{ $paciente->tema_dinamico ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-medical-500"></div>
                    </label>
                </div>

             </div>

             <!-- Action Buttons (Sticky on Desktop) -->
             <div class="sticky top-6 pt-6">
                 <button type="submit" class="w-full py-4 bg-gradient-to-r from-medical-500 to-medical-600 hover:from-medical-600 hover:to-medical-700 text-white rounded-2xl font-black text-lg shadow-xl shadow-medical-500/30 hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-2 group">
                     <span>Guardar Cambios</span>
                     <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                 </button>
                 
                 <a href="{{ route('paciente.dashboard') }}" class="block w-full text-center mt-4 py-3 text-slate-500 dark:text-gray-400 font-bold hover:text-rose-500 transition-colors">
                     Cancelar
                 </a>
             </div>
        </div>

    </div>
</form>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if(!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            // Update Sidebar Preview
            const container = document.getElementById('preview_image_container');
            const imgHtml = `<img id="preview_image" src="${e.target.result}" class="relative w-40 h-40 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-xl z-10 transition-transform transform group-hover/a:scale-105">`;
            
            if(container) {
                container.outerHTML = imgHtml;
            } else {
                document.getElementById('preview_image').src = e.target.result;
            }

            // Update Header Avatar
            const headerAvatar = document.querySelector('.group img[src*="storage"], .group img[src*="ui-avatars"]');
            if(headerAvatar) headerAvatar.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }

    function previewBanner(event) {
        const file = event.target.files[0];
        if(!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('banner_preview');
            preview.style.backgroundImage = `url('${e.target.result}')`;
            preview.innerHTML = ''; // Clear colors
        }
        reader.readAsDataURL(file);
    }

    function setBannerColor(color) {
        document.getElementById('banner_color_input').value = color;
        const preview = document.getElementById('banner_preview');
        
        // If it's a gradient class
        if(!color.startsWith('#')) {
             preview.style.backgroundImage = 'none';
             preview.innerHTML = `<div class="w-full h-full ${color}"></div>`;
        } else {
             preview.style.backgroundImage = 'none';
             preview.style.backgroundColor = color;
             preview.innerHTML = '';
        }

        // Validate buttons
        const buttons = document.querySelectorAll('[onclick^="setBannerColor"]');
        buttons.forEach(btn => {
             btn.classList.remove('border-medical-500', 'shadow-md', 'transform', 'scale-110');
             btn.classList.add('border-transparent');
             if(btn.className.includes(color)) {
                  btn.classList.remove('border-transparent');
                  btn.classList.add('border-medical-500', 'shadow-md', 'transform', 'scale-110');
             }
        });
    }

    // Dynamic Selects
    const locations = {
        estado: document.getElementById('estado_id'),
        ciudad: document.getElementById('ciudad_id'),
        municipio: document.getElementById('municipio_id'),
        parroquia: document.getElementById('parroquia_id')
    };

    locations.estado.addEventListener('change', function() {
        if(!this.value) return;
        
        // Reset and Load Cities
        resetSelect(locations.ciudad);
        loadData(`{{ url('ubicacion/get-ciudades') }}/${this.value}`, locations.ciudad);

        // Reset and Load Municipios
        resetSelect(locations.municipio);
        loadData(`{{ url('ubicacion/get-municipios') }}/${this.value}`, locations.municipio);

        resetSelect(locations.parroquia, 'Seleccione Municipio primero...');
    });

    locations.municipio.addEventListener('change', function() {
        if(!this.value) return;
        
        resetSelect(locations.parroquia);
        loadData(`{{ url('ubicacion/get-parroquias') }}/${this.value}`, locations.parroquia);
    });

    function resetSelect(element, placeholder = 'Seleccione...') {
        element.innerHTML = `<option value="">${placeholder}</option>`;
        element.disabled = true;
    }

    function loadData(url, element) {
        fetch(url)
            .then(res => res.json())
            .then(data => {
                element.disabled = false;
                const keyNameName = Object.keys(data[0]).find(k => !k.startsWith('id_'));
                const keyIdName = Object.keys(data[0]).find(k => k.startsWith('id_'));
                
                data.forEach(item => {
                    element.innerHTML += `<option value="${item[keyIdName]}">${item[keyNameName]}</option>`;
                });
            });
    }
</script>
<style>
    @keyframes slide-in-up {
        0% { transform: translateY(20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-in-up {
        animation: slide-in-up 0.5s ease-out forwards;
    }
    .animate-fade-in-down {
         animation: fadeInDown 0.8s ease-out;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@endsection
