<div class="card-premium rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-gray-700 animate-in fade-in slide-in-from-left duration-500 delay-300 opacity-50 pointer-events-none transition-all relative overflow-hidden" id="tipo_consulta_card">
    
    <!-- Decorative background -->
    <div class="absolute -top-10 left-1/2 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none -translate-x-1/2"></div>

    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-3 relative z-10">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 shadow-sm">
            <i class="bi bi-clipboard-pulse text-xl"></i>
        </div>
        <div>
            <span class="block text-sm font-normal text-slate-500 dark:text-gray-400">Paso 3</span>
            Tipo de Consulta
        </div>
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10">
        <!-- Consulta General / Primera Vez -->
        <label class="cursor-pointer relative group">
            <input type="radio" name="tipo_consulta" value="Primera Vez" class="peer sr-only" onchange="actualizarResumenTipo(this.value)">
            
            <div class="h-full p-6 rounded-3xl border-2 border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-800/50 
                        hover:border-indigo-400 dark:hover:border-indigo-400 hover:shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1
                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50/50 dark:peer-checked:bg-indigo-900/20 peer-checked:shadow-indigo-500/20
                        transition-all duration-300 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 p-4 opacity-0 peer-checked:opacity-100 transition-opacity">
                    <div class="w-6 h-6 rounded-full bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-300">
                        <i class="bi bi-check text-sm font-bold"></i>
                    </div>
                </div>

                <div class="flex flex-col items-center text-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                        <i class="bi bi-person-plus-fill text-3xl"></i>
                    </div>
                    
                    <div>
                        <span class="block font-bold text-lg text-slate-800 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Primera Vez</span>
                        <p class="text-sm text-slate-500 dark:text-gray-400 leading-relaxed px-2">
                            Apertura de historia clínica y evaluación médica inicial completa.
                        </p>
                    </div>
                </div>
            </div>
        </label>

        <!-- Control / Seguimiento -->
        <label class="cursor-pointer relative group">
            <input type="radio" name="tipo_consulta" value="Control" class="peer sr-only" onchange="actualizarResumenTipo(this.value)">
            
            <div class="h-full p-6 rounded-3xl border-2 border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-800/50 
                        hover:border-cyan-400 dark:hover:border-cyan-400 hover:shadow-lg hover:shadow-cyan-500/10 hover:-translate-y-1
                        peer-checked:border-cyan-500 peer-checked:bg-cyan-50/50 dark:peer-checked:bg-cyan-900/20 peer-checked:shadow-cyan-500/20
                        transition-all duration-300 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 p-4 opacity-0 peer-checked:opacity-100 transition-opacity">
                    <div class="w-6 h-6 rounded-full bg-cyan-500 text-white flex items-center justify-center shadow-lg shadow-cyan-300">
                        <i class="bi bi-check text-sm font-bold"></i>
                    </div>
                </div>

                <div class="flex flex-col items-center text-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-cyan-100 dark:bg-cyan-900/40 text-cyan-600 dark:text-cyan-400 flex items-center justify-center group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-inner">
                        <i class="bi bi-journal-medical text-3xl"></i>
                    </div>
                    
                    <div>
                        <span class="block font-bold text-lg text-slate-800 dark:text-white mb-2 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Control</span>
                        <p class="text-sm text-slate-500 dark:text-gray-400 leading-relaxed px-2">
                            Seguimiento de tratamiento, revisión de exámenes o evolución.
                        </p>
                    </div>
                </div>
            </div>
        </label>
    </div>
</div>
