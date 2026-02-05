<div id="datos-paciente-especial" class="card-premium rounded-3xl p-6 lg:p-8 border border-slate-200 dark:border-gray-700 hidden animate-in fade-in slide-in-from-left duration-300 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

    <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-8 flex items-center gap-3 relative z-10">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 shadow-sm">
            <i class="bi bi-person-heart text-xl"></i>
        </div>
        <div>
            <span class="block text-sm font-normal text-slate-500 dark:text-gray-400">Paso 1.1</span>
            Datos del Paciente
        </div>
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
        <!-- Primer Nombre -->
        <div class="relative group">
            <input type="text" name="pac_primer_nombre" id="pac_primer_nombre" 
                   class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all pl-11" 
                   placeholder=" " 
                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')" />
            
            <i class="bi bi-person absolute left-4 top-4 text-slate-400 peer-focus:text-orange-500 transition-colors text-lg"></i>
            
            <label for="pac_primer_nombre" 
                   class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-11 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                Primer Nombre <span class="text-rose-500">*</span>
            </label>
            <span class="error-message text-rose-500 text-xs mt-1.5 hidden ml-1 font-medium bg-rose-50 dark:bg-rose-900/30 px-2 py-1 rounded-md" id="pac_primer_nombre_error"></span>
        </div>

        <!-- Segundo Nombre -->
        <div class="relative group">
            <input type="text" name="pac_segundo_nombre" id="pac_segundo_nombre" 
                   class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all pl-4" 
                   placeholder=" " 
                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')" />
            
            <label for="pac_segundo_nombre" 
                   class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                Segundo Nombre
            </label>
        </div>

        <!-- Primer Apellido -->
        <div class="relative group">
            <input type="text" name="pac_primer_apellido" id="pac_primer_apellido" 
                   class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all pl-4" 
                   placeholder=" " 
                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')" />
            
            <label for="pac_primer_apellido" 
                   class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                Primer Apellido <span class="text-rose-500">*</span>
            </label>
            <span class="error-message text-rose-500 text-xs mt-1.5 hidden ml-1 font-medium bg-rose-50 dark:bg-rose-900/30 px-2 py-1 rounded-md" id="pac_primer_apellido_error"></span>
        </div>

        <!-- Segundo Apellido -->
        <div class="relative group">
            <input type="text" name="pac_segundo_apellido" id="pac_segundo_apellido" 
                   class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all pl-4" 
                   placeholder=" " 
                   oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '')" />
            
            <label for="pac_segundo_apellido" 
                   class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                Segundo Apellido
            </label>
        </div>

        <!-- Identificación -->
        <div class="relative group lg:col-span-2">
            <div class="flex">
                <select name="pac_tipo_documento" id="pac_tipo_documento" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-slate-900 bg-slate-100 border-2 border-slate-200 rounded-s-xl hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-slate-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600 cursor-pointer">
                    <option value="V">V</option>
                    <option value="E">E</option>
                    <option value="P">P</option>
                    <option value="J">J</option>
                </select>
                <div class="relative w-full">
                    <input type="text" name="pac_numero_documento" id="pac_numero_documento" 
                           class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-e-xl border-2 border-l-0 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all" 
                           placeholder=" " 
                           maxlength="12"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                    
                    <label for="pac_numero_documento" 
                           class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                        Cédula / Documento (Opcional)
                    </label>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-1 ml-1 pl-1">Dejar en blanco si el paciente no posee documento</p>
        </div>

        <!-- Teléfono -->
        <div class="relative group lg:col-span-2">
             <div class="flex">
                <select name="pac_prefijo_tlf" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-slate-900 bg-slate-100 border-2 border-slate-200 rounded-s-xl hover:bg-slate-200 focus:ring-4 focus:outline-none focus:ring-slate-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600 cursor-pointer">
                    <option value="+58">+58</option>
                    <option value="+57">+57</option>
                    <option value="+1">+1</option>
                </select>
                <div class="relative w-full">
                    <input type="tel" name="pac_numero_tlf" id="pac_numero_tlf" 
                           class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-e-xl border-2 border-l-0 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all" 
                           placeholder=" " 
                           maxlength="10"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                    <label for="pac_numero_tlf" 
                           class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                        Número Telefónico
                    </label>
                </div>
            </div>
            
        </div>

        <!-- Fecha Nacimiento -->
        <div class="relative group">
            <input type="date" name="pac_fecha_nac" id="pac_fecha_nac" 
                   class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all" 
                   placeholder=" " 
                   max="{{ date('Y-m-d') }}" />
                   
            <label for="pac_fecha_nac" 
                   class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 bg-white dark:bg-gray-800 px-1">
                Fecha Nacimiento <span class="text-rose-500">*</span>
            </label>
            <span class="error-message text-rose-500 text-xs mt-1.5 hidden ml-1 font-medium bg-rose-50 dark:bg-rose-900/30 px-2 py-1 rounded-md" id="pac_fecha_nac_error"></span>
        </div>

        <!-- Género -->
        <div class="relative group">
            <select name="pac_genero" id="pac_genero" 
                    class="block px-4 pb-2.5 pt-5 w-full text-base text-slate-900 dark:text-white bg-transparent rounded-xl border-2 border-slate-200 dark:border-gray-600 appearance-none focus:outline-none focus:ring-0 focus:border-orange-500 peer transition-all cursor-pointer">
                <option value="" disabled selected class="text-slate-500">Seleccionar...</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
            <label for="pac_genero" 
                   class="absolute text-sm text-slate-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-orange-600 peer-focus:dark:text-orange-500 bg-white dark:bg-gray-800 px-1">
                Género <span class="text-rose-500">*</span>
            </label>
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <i class="bi bi-chevron-down text-slate-400"></i>
            </div>
            <span class="error-message text-rose-500 text-xs mt-1.5 hidden ml-1 font-medium bg-rose-50 dark:bg-rose-900/30 px-2 py-1 rounded-md" id="pac_genero_error"></span>
        </div>
    </div>
</div>
