<div class="card-premium rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-gray-700 animate-in fade-in slide-in-from-left duration-500 delay-200 opacity-50 pointer-events-none transition-all relative overflow-hidden" id="fecha_hora_card">
    
    <!-- Decorative background -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-400 to-blue-500"></div>
    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-sky-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-3 relative z-10">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 shadow-sm">
            <i class="bi bi-calendar-check text-xl"></i>
        </div>
        <div>
            <span class="block text-sm font-normal text-slate-500 dark:text-gray-400">Paso 2</span>
            Fecha y Hora
        </div>
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 animate-in zoom-in-95 duration-500 relative z-10">
        <!-- Selector de Fecha -->
        <div class="space-y-4">
            <div class="relative group">
                <input type="date" name="fecha_cita" id="fecha_cita" 
                       class="peer w-full px-6 py-4 rounded-2xl border-2 border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-slate-800 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm text-lg font-medium transition-all cursor-pointer hover:border-sky-200 dark:hover:border-gray-500 pt-7 pb-2" 
                       min="{{ date('Y-m-d') }}" 
                       onchange="caragarHorasDisponibles(this.value)">
                
                <label class="absolute text-xs font-bold text-slate-400 dark:text-gray-400 uppercase top-2 left-6 tracking-wider peer-focus:text-sky-500">
                    Seleccionar Fecha
                </label>
            </div>
            
            <div class="p-4 rounded-2xl bg-sky-50 dark:bg-sky-900/10 border border-sky-100 dark:border-sky-800/30 flex gap-4 items-start">
                <div class="shrink-0 text-sky-500 mt-0.5">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <p class="text-sm text-slate-600 dark:text-gray-300 leading-relaxed">
                    Seleccione un día en el calendario. Los días con disponibilidad se mostrarán activos.
                </p>
            </div>

            <!-- Leyenda de colores -->
            <div class="flex items-center justify-center gap-6 text-sm mt-4 bg-white dark:bg-gray-800/50 p-3 rounded-xl border border-slate-100 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></div>
                    <span class="text-slate-600 dark:text-gray-400 font-medium">Disponible</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-slate-300 dark:bg-gray-600"></div>
                    <span class="text-slate-500 dark:text-gray-500">Ocupado</span>
                </div>
            </div>
        </div>

        <!-- Selector de Hora (Slots) -->
        <div class="space-y-4 relative">
            <div class="flex justify-between items-center mb-2">
                <label class="text-sm font-bold text-slate-700 dark:text-gray-300 uppercase flex items-center gap-2">
                    <i class="bi bi-clock text-sky-500"></i>
                    Horarios
                </label>
                <div id="loader-horas" class="hidden text-sky-500 text-sm font-medium animate-pulse flex items-center gap-2">
                    <div class="animate-spin h-4 w-4 border-2 border-sky-500 border-t-transparent rounded-full"></div>
                    Buscando...
                </div>
            </div>
            
            <div class="bg-slate-50 dark:bg-gray-800/50 rounded-2xl p-4 border border-slate-200 dark:border-gray-700 min-h-[200px]">
                <!-- Estado Inicial -->
                <div id="sin-fecha-msg" class="flex flex-col items-center justify-center h-full text-slate-400 text-center py-8">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                        <i class="bi bi-calendar-event text-3xl opacity-50"></i>
                    </div>
                    <p class="font-medium">Seleccione una fecha</p>
                    <p class="text-xs mt-1">Para ver los turnos disponibles</p>
                </div>

                <!-- Contenedor de Slots -->
                <div id="contenedor-horas" class="grid grid-cols-2 sm:grid-cols-3 gap-3 hidden max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                    <!-- Se rellena vía JS -->
                </div>
            </div>
            
            <input type="hidden" name="hora_cita" id="hora_cita" required>
            <span class="error-message text-rose-500 text-xs mt-1 hidden font-medium bg-rose-50 dark:bg-rose-900/20 p-2 rounded-lg" id="hora_cita_error">
                <i class="bi bi-exclamation-triangle mr-1"></i> Debe seleccionar una hora
            </span>
        </div>
    </div>
</div>
