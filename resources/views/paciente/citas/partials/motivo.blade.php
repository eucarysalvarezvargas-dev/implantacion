<div class="card-premium rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-gray-700 animate-in fade-in slide-in-from-left duration-500 delay-400 opacity-50 pointer-events-none transition-all relative overflow-hidden" id="motivo_card">
    
    <!-- Decorative background -->
    <div class="absolute right-0 bottom-0 w-64 h-64 bg-rose-500/5 rounded-full blur-3xl pointer-events-none translate-y-1/2 translate-x-1/2"></div>

    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-3 relative z-10">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 shadow-sm">
            <i class="bi bi-chat-quote-fill text-xl"></i>
        </div>
        <div>
            <span class="block text-sm font-normal text-slate-500 dark:text-gray-400">Paso 4</span>
            Motivo de Consulta
        </div>
    </h3>

    <div class="relative z-10">
        <div class="relative group">
            <textarea name="motivo" id="motivo" rows="5" 
                      class="block px-6 pb-4 pt-8 w-full text-base text-slate-900 dark:text-white bg-slate-50 dark:bg-gray-800/50 rounded-2xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-rose-500 focus:bg-white dark:focus:bg-gray-800 peer transition-all resize-none shadow-inner"
                      placeholder=" "
                      maxlength="255"
                      oninput="document.getElementById('motivo_counter').textContent = this.value.length + '/255'"></textarea>
            
            <label for="motivo" 
                   class="absolute text-sm font-medium text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-90 top-5 z-10 origin-[0] left-6 peer-focus:text-rose-600 peer-focus:dark:text-rose-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-4">
                Describa brevemente sus síntomas <span class="text-rose-500">*</span>
            </label>
            
            <div class="absolute bottom-4 right-4 flex items-center gap-3 pointer-events-none">
                <span class="text-xs font-medium text-slate-400 bg-slate-200 dark:bg-gray-700 px-2 py-1 rounded-md transition-colors group-focus-within:text-rose-500 group-focus-within:bg-rose-50 dark:group-focus-within:bg-rose-900/20" id="motivo_counter">0/255</span>
                <i class="bi bi-pencil-fill text-slate-300 group-focus-within:text-rose-500 transition-colors"></i>
            </div>
        </div>
        
        <p class="text-xs text-slate-400 mt-2 ml-2">
            <i class="bi bi-info-circle mr-1"></i>
            Sea breve y claro. Esta información ayudará al médico a prepararse.
        </p>
        
        <span class="error-message text-rose-500 text-xs mt-2 hidden font-medium flex items-center gap-1" id="motivo_error">
            <i class="bi bi-exclamation-circle"></i> <span>Este campo es obligatorio</span>
        </span>
    </div>
</div>
