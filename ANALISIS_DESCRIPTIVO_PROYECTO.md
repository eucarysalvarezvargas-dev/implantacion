# ANÁLISIS DESCRIPTIVO COMPLETO DEL SISTEMA DE RESERVAS MÉDICAS

**Fecha de Análisis:** 21 de Enero de 2026  
**Proyecto:** SisreservaMedicasOring  
**Versión Framework:** Laravel 10.x

---

## ÍNDICE

1. [Descripción General del Proyecto](#1-descripción-general-del-proyecto)
2. [Stack Tecnológico](#2-stack-tecnológico)
3. [Estructura del Proyecto](#3-estructura-del-proyecto)
4. [Funciones Principales del Sistema](#4-funciones-principales-del-sistema)
5. [Controladores y Lógica de Negocio](#5-controladores-y-lógica-de-negocio)
6. [Modelos y Base de Datos](#6-modelos-y-base-de-datos)
7. [Sistema de Rutas y APIs](#7-sistema-de-rutas-y-apis)
8. [Flujo de Datos](#8-flujo-de-datos)
9. [Vulnerabilidades de Seguridad](#9-vulnerabilidades-de-seguridad)
10. [Mejoras Recomendadas](#10-mejoras-recomendadas)
11. [Herramientas de Desarrollo](#11-herramientas-de-desarrollo)
12. [Fallas Actuales del Proyecto](#12-fallas-actuales-del-proyecto)

---

## 1. DESCRIPCIÓN GENERAL DEL PROYECTO

El Sistema de Reservas Médicas es una aplicación web completa desarrollada en **Laravel 10** que permite la gestión integral de una clínica médica. El sistema maneja:

- **Gestión de Citas Médicas:** Reserva, cancelación y seguimiento de citas
- **Historia Clínica Electrónica:** Registro completo de evoluciones clínicas por paciente
- **Órdenes Médicas:** Recetas, exámenes de laboratorio, imagenología y referencias
- **Sistema de Facturación:** Con soporte multi-moneda (Bolívares/Dólares) y reparto de comisiones
- **Gestión de Usuarios:** Administradores, Médicos, Pacientes, Representantes Legales y Pacientes Especiales
- **Notificaciones:** Sistema de notificaciones por email y en tiempo real

### Roles del Sistema

| Rol | Descripción |
|-----|-------------|
| **Root** | Administrador con acceso total al sistema |
| **Admin Local** | Administrador con restricciones según consultorio |
| **Médico** | Profesional de salud que atiende pacientes |
| **Paciente** | Usuario final que agenda citas |
| **Representante** | Persona que gestiona citas para pacientes especiales (menores, incapacitados) |

---

## 2. STACK TECNOLÓGICO

### Backend
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | ^8.1 | Lenguaje principal |
| **Laravel** | ^10.10 | Framework MVC |
| **Laravel Sanctum** | ^3.3 | Autenticación API (preparado pero no activo) |
| **Laravel Reverb** | ^1.7 | WebSockets para notificaciones en tiempo real |
| **Guzzle HTTP** | ^7.2 | Cliente HTTP para APIs externas |

### Frontend
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **Vite** | ^5.4.21 | Bundler de assets |
| **TailwindCSS** | ^3.4.17 | Framework CSS |
| **Alpine.js** | - | Interactividad reactiva (inferido del código) |
| **Axios** | ^1.7.9 | Peticiones AJAX |
| **Pusher.js** | ^8.4.0 | Conexión WebSockets cliente |
| **Laravel Echo** | ^2.3.0 | Cliente WebSockets Laravel |

### Base de Datos
- **MySQL** (configurado en `.env`)
- 68 migraciones organizadas cronológicamente
- Sistema de ubicación geográfica (Estados → Municipios → Ciudades → Parroquias)

---

## 3. ESTRUCTURA DEL PROYECTO

```
SisreservaMedicasOring/
├── app/
│   ├── Http/
│   │   ├── Controllers/         # 21 controladores principales + subdirectorios
│   │   │   ├── Admin/           # Controladores específicos de admin
│   │   │   ├── Paciente/        # Controladores específicos de paciente
│   │   │   ├── AuthController.php
│   │   │   ├── CitaController.php (1800+ líneas - el más complejo)
│   │   │   ├── MedicoController.php
│   │   │   ├── PacienteController.php
│   │   │   ├── HistoriaClinicaController.php
│   │   │   ├── OrdenMedicaController.php
│   │   │   ├── FacturacionController.php
│   │   │   ├── PagoController.php
│   │   │   └── ConfiguracionController.php
│   │   └── Middleware/          # 12 middlewares
│   ├── Models/                  # 41 modelos Eloquent
│   ├── Helpers/helpers.php      # Funciones helper globales
│   └── Notifications/           # Notificaciones por email
├── database/
│   ├── migrations/              # 68 archivos de migración
│   └── seeders/                 # Datos iniciales
├── resources/
│   └── views/                   # Vistas Blade
│       ├── admin/               # 19 vistas de administración
│       ├── medico/              # 31 vistas para médicos
│       ├── paciente/            # 15 vistas para pacientes
│       ├── shared/              # 95 vistas compartidas (componentes reutilizables)
│       ├── layouts/             # 7 layouts principales
│       └── emails/              # 12 plantillas de correo
└── routes/
    ├── web.php                  # 504 líneas de rutas web
    └── api.php                  # Rutas API (actualmente comentadas)
```

---

## 4. FUNCIONES PRINCIPALES DEL SISTEMA

### 4.1 Gestión de Citas Médicas

**Flujo de creación de citas:**

1. **Selección de ubicación:** El paciente selecciona Estado
2. **Selección de consultorio:** Lista consultorios disponibles en ese estado
3. **Selección de especialidad:** Muestra especialidades disponibles en el consultorio
4. **Selección de médico:** Filtra médicos por especialidad y consultorio
5. **Selección de fecha/hora:** Muestra horarios disponibles considerando:
   - Horarios configurados del médico
   - Citas ya agendadas
   - Fechas indisponibles del médico
6. **Confirmación y pago:** La cita queda pendiente hasta confirmar pago

**Tipos de citas soportadas:**
- Cita propia (paciente registrado)
- Cita para terceros (mediante representante legal)
- Cita a domicilio (con dirección específica)

### 4.2 Historia Clínica Electrónica

**Componentes:**

| Componente | Descripción |
|------------|-------------|
| **Historia Base** | Datos generales: tipo de sangre, alergias, antecedentes familiares, hábitos |
| **Evolución Clínica** | Registro por cada cita: motivo consulta, examen físico, diagnóstico, tratamiento |
| **Solicitudes de Acceso** | Sistema de permisos para que otros médicos accedan a evoluciones |

**Sistema de permisos de acceso:**
- El médico propietario tiene acceso total a sus evoluciones
- Otros médicos deben solicitar acceso al PACIENTE
- El paciente aprueba/rechaza desde su panel
- Se registra auditoría de todos los accesos

### 4.3 Órdenes Médicas

**Tipos de órdenes:**

| Tipo | Modelo | Descripción |
|------|--------|-------------|
| **Receta** | `OrdenMedicamento` | Medicamentos con dosis, frecuencia, duración |
| **Laboratorio** | `OrdenExamen` | Exámenes clínicos (sangre, orina, etc.) |
| **Imagenología** | `OrdenImagen` | Rayos X, tomografías, resonancias |
| **Referencia** | `OrdenReferencia` | Interconsultas a otras especialidades |
| **Mixta** | Múltiples | Combinación de varios tipos |

### 4.4 Sistema de Facturación

**Características:**
- **Multi-moneda:** Soporte para Bolívares y Dólares
- **Tasa de cambio:** Sincronización automática desde API externa
- **Sistema de reparto:** División de ingresos entre médico, consultorio y administración
- **Liquidaciones:** Resumen de pagos pendientes por médico

### 4.5 Sistema de Notificaciones

**Canales:**
- Email (SMTP configurado con Mailtrap para desarrollo)
- Notificaciones en tiempo real (preparado con Pusher/Laravel Reverb)
- Notificaciones en base de datos (tabla `notificaciones`)

---

## 5. CONTROLADORES Y LÓGICA DE NEGOCIO

### 5.1 AuthController (542 líneas)

**Funciones principales:**

```php
// Autenticación
showLogin()              // Muestra formulario de login
login(Request $request)  // Procesa login con verificación de estado de usuario

// Registro
showRegister()           // Muestra formulario multi-paso
register(Request $request) // Crea usuario con perfil según rol

// Recuperación de contraseña
showRecovery()           // Formulario de recuperación
getSecurityQuestions()   // Obtiene preguntas de seguridad del usuario
verifySecurityAnswers()  // Verifica respuestas antes de enviar token
resetPassword()          // Cambia la contraseña

// Helpers internos
crearPerfilMedico()      // Crea registro en tabla médicos
crearPerfilPaciente()    // Crea registro en tabla pacientes
redirectByRole()         // Redirige al dashboard según rol
```

**Lógica de autenticación:**
```php
// La contraseña se almacena con DOBLE MD5 (¡VULNERABLE!)
$this->attributes['password'] = md5(md5($value));

// Verificación en login:
$inputHash = md5(md5($request->password));
if ($usuario->password !== $inputHash) {
    // Credenciales inválidas
}
```

### 5.2 CitaController (1834 líneas - MÁS COMPLEJO)

**Funciones CRUD:**
```php
index(Request $request)  // Lista citas con filtros múltiples
create()                 // Formulario de creación (diferente para paciente/admin)
store(Request $request)  // Almacena cita (proceso complejo de ~600 líneas)
show($id)                // Detalle de cita con relaciones
edit($id)                // Formulario de edición
update(Request $request, $id) // Actualiza cita
destroy($id)             // Elimina cita
```

**APIs AJAX (para selects dependientes):**
```php
getConsultoriosPorEstado($estadoId)           // Consultorios de un estado
getEspecialidadesPorConsultorio($consultorioId) // Especialidades de un consultorio
getConsultoriosPorEspecialidad($especialidadId) // Consultorios con esa especialidad
getMedicosPorEspecialidadConsultorio(Request $request) // Médicos filtrados
getHorariosDisponibles(Request $request)      // Slots de hora disponibles
```

**Funciones de gestión:**
```php
cambiarEstado(Request $request, $id)    // Cambia estado de cita
solicitarCancelacion(Request $request, $id) // Solicita cancelar cita
buscarPaciente(Request $request)        // Búsqueda AJAX de pacientes
verificarCorreo(Request $request)       // Verifica si correo existe
verificarDocumento(Request $request)    // Verifica si documento existe
events(Request $request)                // API para FullCalendar
```

**Lógica del método `store()` (líneas 299-890):**

1. **Validación de datos:** Reglas dinámicas según tipo de cita
2. **Verificación de paciente:**
   - Si es cita propia: usa paciente autenticado
   - Si es terceros: verifica/crea representante y paciente especial
3. **Verificación de disponibilidad:**
   - Consulta horarios del médico
   - Verifica citas existentes en ese horario
   - Verifica fechas indisponibles
4. **Creación de registros:**
   - Crea/actualiza Usuario si es nuevo
   - Crea/actualiza Paciente/PacienteEspecial
   - Crea/actualiza Representante si aplica
   - Crea relación en tabla pivot `representante_paciente_especial`
   - Crea registro de Cita
   - Crea FacturaPaciente
5. **Notificaciones:** Envía email de confirmación

### 5.3 MedicoController (969 líneas)

**Funciones principales:**
```php
dashboard()                    // Panel del médico con estadísticas
index(Request $request)        // Lista médicos con filtros
store(Request $request)        // Crea médico con usuario asociado
horarios($id)                  // Vista de configuración de horarios
guardarHorario(Request $request, $id) // Guarda horarios por día/consultorio
agenda(Request $request)       // Vista de agenda semanal
storeFechaIndisponible()       // Marca fechas de vacaciones/indisponibilidad
```

**Lógica de horarios (líneas 346-625):**

Los horarios se guardan en la tabla `medico_consultorio` con estructura:
```php
[
    'medico_id' => $medicoId,
    'consultorio_id' => $consultorioId,
    'especialidad_id' => $especialidadId,
    'dia_semana' => 'lunes',  // lunes, martes, etc.
    'hora_inicio' => '08:00:00',
    'hora_fin' => '12:00:00',
    'activo' => true
]
```

### 5.4 HistoriaClinicaController (1170 líneas)

**Historia Base:**
```php
indexBase(Request $request)     // Lista pacientes con historia base
showBase($pacienteId)           // Muestra historia base
createBase($pacienteId)         // Formulario de creación
storeBase(Request $request, $pacienteId)  // Guarda historia base
editBase($pacienteId)           // Formulario de edición
updateBase(Request $request, $pacienteId) // Actualiza historia
```

**Evoluciones Clínicas:**
```php
indexEvoluciones($pacienteId)   // Lista evoluciones de un paciente
indexGeneral(Request $request)  // Lista general para el médico
createEvolucion($citaId)        // Formulario para registrar evolución post-cita
storeEvolucion(Request $request, $citaId) // Guarda evolución
showEvolucion($citaId)          // Muestra detalle de evolución
```

**Sistema de permisos:**
```php
solicitarAcceso(Request $request, $pacienteId) // Médico solicita acceso
listarSolicitudesPaciente()     // Paciente ve solicitudes pendientes
aprobarSolicitud($solicitudId)  // Paciente aprueba acceso
rechazarSolicitud($solicitudId) // Paciente rechaza acceso
tieneAccesoAprobado($medicoId, $pacienteId, $medicoPropietarioId) // Verificación
```

### 5.5 OrdenMedicaController (1081 líneas)

**CRUD básico:**
```php
index()           // Lista órdenes del médico autenticado
create()          // Formulario de creación
store()           // Guarda orden simple
storeConItems()   // Guarda orden con múltiples items (recetas, exámenes, etc.)
show($id)         // Detalle de orden
```

**Por tipo de orden:**
```php
recetas()         // Filtra solo recetas
laboratorios()    // Filtra solo laboratorios
imagenologias()   // Filtra solo imagenología
referencias()     // Filtra solo referencias
```

**Funciones adicionales:**
```php
imprimir($id)              // Genera vista para impresión
exportarPorPeriodo()       // Exporta órdenes de un rango de fechas
registrarResultados($id)   // Registra resultados de exámenes
estadisticas()             // Estadísticas de órdenes
```

**Método `storeConItems()` (líneas 866-1079):**

Este método procesa JSON con estructura:
```json
{
    "cita_id": 123,
    "tipo_orden": "Mixta",
    "items": [
        {
            "tipo": "Receta",
            "medicamento": "Ibuprofeno",
            "dosis": "400mg",
            "frecuencia": "Cada 8 horas"
        },
        {
            "tipo": "Laboratorio",
            "examenes": ["Hemograma", "Glucosa"]
        }
    ]
}
```

### 5.6 FacturacionController (401 líneas)

**Funciones principales:**
```php
index()                       // Lista facturas
store(Request $request)       // Crea factura
crearFacturacionAvanzada()    // Sistema de reparto de comisiones
generarNumeroControl()        // Genera número secuencial de factura
crearDetallesFactura()        // Crea líneas de detalle
crearTotalesFactura()         // Calcula totales con conversión de moneda
crearLiquidacion()            // Genera liquidación para pago a médico
resumenLiquidaciones()        // Vista de liquidaciones pendientes
```

**Sistema de Reparto (líneas 181-220):**

```php
// Configuración típica de reparto:
// - 60% para el médico
// - 30% para el consultorio
// - 10% para administración

$configReparto = ConfiguracionReparto::where('activo', true)->first();
// Se crean tres líneas de detalle con los porcentajes
```

### 5.7 PagoController (847 líneas)

**Funciones de administrador:**
```php
index(Request $request)     // Lista pagos con filtros
store(Request $request)     // Registra pago (admin)
confirmarPago($id)          // Confirma pago y activa facturación avanzada
rechazarPago($id)           // Rechaza pago
```

**Funciones de paciente:**
```php
misPagos()                      // Lista pagos del paciente
mostrarRegistroPago($citaId)    // Formulario de registro de pago
registrarPagoPaciente(Request $request) // Paciente sube comprobante
```

**Proceso de confirmación (líneas 242-324):**

1. Verifica que el pago exista y esté pendiente
2. Cambia estado a 'confirmado'
3. Actualiza estado de la cita a 'pagada'
4. Ejecuta facturación avanzada (crea cabecera, detalles, totales)
5. Envía notificación al paciente

### 5.8 ConfiguracionController (625 líneas)

**Secciones de configuración:**
```php
general()           // Nombre de clínica, logo, información básica
reparto()           // Porcentajes de reparto de comisiones
tasas()             // Tasa de cambio USD/VES
metodosPago()       // Métodos de pago aceptados
correo()            // Configuración SMTP
backup()            // Generación de respaldos
mantenimiento()     // Limpieza de cache, optimización
logs()              // Visualización de logs
servidor()          // Información del servidor
estadisticas()      // Estadísticas generales del sistema
```

---

## 6. MODELOS Y BASE DE DATOS

### 6.1 Modelos Principales (41 total)

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `Usuario` | usuarios | Credenciales de acceso |
| `Administrador` | administradores | Perfil de admin (Root/Local) |
| `Medico` | medicos | Perfil de médico |
| `Paciente` | pacientes | Perfil de paciente |
| `Representante` | representantes | Representante legal |
| `PacienteEspecial` | pacientes_especiales | Menores o incapacitados |
| `Cita` | citas | Citas médicas |
| `Consultorio` | consultorios | Instalaciones físicas |
| `Especialidad` | especialidades | Especialidades médicas |
| `MedicoConsultorio` | medico_consultorio | Horarios de médico por consultorio |
| `HistoriaClinicaBase` | historia_clinica_base | Historia base del paciente |
| `EvolucionClinica` | evolucion_clinica | Evoluciones por cita |
| `OrdenMedica` | ordenes_medicas | Órdenes médicas |
| `OrdenMedicamento` | orden_medicamentos | Detalle de recetas |
| `OrdenExamen` | orden_examenes | Detalle de laboratorios |
| `OrdenImagen` | orden_imagenes | Detalle de imagenología |
| `OrdenReferencia` | orden_referencias | Detalle de referencias |
| `FacturaPaciente` | facturas_pacientes | Factura principal |
| `FacturaCabecera` | factura_cabecera | Cabecera del reparto |
| `FacturaDetalle` | factura_detalles | Líneas de reparto |
| `FacturaTotal` | factura_totales | Totales en múltiples monedas |
| `Pago` | pagos | Registro de pagos |
| `TasaDolar` | tasas_dolar | Histórico de tasas de cambio |
| `Notificacion` | notificaciones | Notificaciones del sistema |

### 6.2 Relaciones Principales

```
Usuario (1) ─────────── (0..1) Administrador
        │
        ├──────────── (0..1) Medico ────── (N) Especialidad
        │                      │
        │                      └───── (N) Consultorio (vía medico_consultorio)
        │
        └──────────── (0..1) Paciente ───── (N) Cita ───── (N) EvolucionClinica
                                │                │
                                │                └── (N) OrdenMedica
                                │
                                └── (1) HistoriaClinicaBase

Representante (N) ────────── (N) PacienteEspecial (tabla pivot)
                                      │
                                      └── (N) Cita
```

### 6.3 Tablas Pivot

| Tabla | Conecta | Campos Adicionales |
|-------|---------|-------------------|
| `medico_especialidad` | Médico ↔ Especialidad | tarifa, activo |
| `medico_consultorio` | Médico ↔ Consultorio | dia_semana, hora_inicio, hora_fin, especialidad_id |
| `especialidad_consultorio` | Especialidad ↔ Consultorio | tarifa_base |
| `representante_paciente_especial` | Representante ↔ PacienteEspecial | tipo_responsabilidad, activo |

---

## 7. SISTEMA DE RUTAS Y APIs

### 7.1 Rutas Web (504 líneas en `web.php`)

**Rutas Públicas:**
```php
GET  /                        # Página principal
GET  /login                   # Formulario de login
POST /login                   # Procesar login
GET  /register                # Formulario de registro
POST /register                # Procesar registro
GET  /recovery                # Recuperación de contraseña
POST /recovery/get-questions  # AJAX: obtener preguntas de seguridad
POST /recovery/verify-answers # AJAX: verificar respuestas
```

**Rutas AJAX para Citas (sin autenticación):**
```php
GET /ajax/citas/consultorios-por-estado/{estadoId}
GET /ajax/citas/especialidades-por-consultorio/{consultorioId}
GET /ajax/citas/medicos
GET /ajax/citas/horarios-disponibles
GET /ajax/citas/verificar-documento
```

**Rutas de Paciente (autenticado):**
```php
GET  /paciente/dashboard
GET  /paciente/citas                 # Lista de citas
GET  /paciente/citas/create          # Crear cita
POST /paciente/citas                 # Guardar cita
GET  /paciente/historial             # Historia clínica
GET  /paciente/pagos                 # Pagos realizados
GET  /paciente/ordenes               # Órdenes médicas recibidas
```

**Rutas de Médico (autenticado):**
```php
GET  /medico/dashboard
GET  /medico/agenda                  # Agenda semanal
GET  /medicos/{id}/horarios          # Configurar horarios
POST /medicos/{id}/guardar-horario   # Guardar horarios
```

**Rutas de Administrador:**
```php
# CRUD completo para:
- /admin/usuarios
- /admin/administradores
- /medicos
- /pacientes
- /citas
- /consultorios
- /especialidades
- /pagos
- /facturacion
- /ordenes-medicas
- /configuracion/*
```

### 7.2 API REST (Actualmente DESHABILITADA)

El archivo `routes/api.php` contiene rutas comentadas que estaban diseñadas para:
- Autenticación con Sanctum
- CRUD de citas vía API
- Endpoints para médicos y pacientes

---

## 8. FLUJO DE DATOS

### 8.1 Flujo de Creación de Cita (Inyección de Datos)

```
┌─────────────────┐     ┌─────────────────────┐     ┌─────────────────┐
│   Vista Blade   │────▶│    CitaController   │────▶│  Base de Datos  │
│  (create.blade) │     │      store()        │     │                 │
└────────┬────────┘     └─────────┬───────────┘     └────────┬────────┘
         │                        │                          │
    Formulario                Validación              Transacción:
    con Alpine.js             $request->validate()    - usuarios
         │                        │                   - pacientes
    AJAX calls                DB::beginTransaction()  - citas
    (getHorarios,                 │                   - facturas_pacientes
     getMedicos)              try/catch               - notificaciones
                                  │
                              DB::commit() o rollback()
```

### 8.2 Flujo de Filtrado de Datos (Lectura)

**En controladores:**
```php
// Ejemplo: CitaController@index
public function index(Request $request)
{
    $query = Cita::query();
    
    // Filtros dinámicos
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    if ($request->filled('fecha_desde')) {
        $query->whereDate('fecha_cita', '>=', $request->fecha_desde);
    }
    if ($request->filled('medico_id')) {
        $query->where('medico_id', $request->medico_id);
    }
    
    // Eager loading para evitar N+1
    $citas = $query->with(['paciente.usuario', 'medico.usuario', 'consultorio'])
                   ->orderBy('fecha_cita', 'desc')
                   ->paginate(15);
    
    return view('citas.index', compact('citas'));
}
```

### 8.3 Flujo de Carga de Datos en Vistas

**Controlador → Vista:**
```php
// MedicoController@horarios
public function horarios($id)
{
    $medico = Medico::with(['especialidades', 'consultorios'])->findOrFail($id);
    $consultorios = Consultorio::all();
    $horarios = MedicoConsultorio::where('medico_id', $id)->get();
    
    return view('shared.medicos.horarios', compact('medico', 'consultorios', 'horarios'));
}
```

**En Vista Blade:**
```blade
<!-- horarios.blade.php -->
@foreach($horarios as $horario)
    <tr>
        <td>{{ $horario->dia_semana }}</td>
        <td>{{ $horario->hora_inicio }}</td>
        <td>{{ $horario->hora_fin }}</td>
        <td>{{ $horario->consultorio->nombre }}</td>
    </tr>
@endforeach

<!-- Datos para JavaScript (Alpine.js) -->
<script>
    window.rawHorarios = @json($horarios);
</script>
```

### 8.4 Flujo de Guardado de Datos

**Ejemplo: Guardar Historia Clínica Base:**
```php
public function storeBase(Request $request, $pacienteId)
{
    $validated = $request->validate([
        'tipo_sangre' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-,No especificado',
        'alergias' => 'nullable|string',
        'antecedentes_familiares' => 'nullable|string',
        // ... más campos
    ]);
    
    $historia = HistoriaClinicaBase::create([
        'paciente_id' => $pacienteId,
        'tipo_sangre' => $validated['tipo_sangre'],
        'alergias' => $validated['alergias'],
        // ... más campos
    ]);
    
    // Crear auditoría
    AuditoriaHistoriaBase::create([
        'historia_clinica_base_id' => $historia->id,
        'usuario_id' => auth()->id(),
        'accion' => 'creacion',
        'datos_nuevos' => json_encode($historia->toArray())
    ]);
    
    return redirect()->route('historia-clinica.base.show', $pacienteId)
                     ->with('success', 'Historia clínica creada correctamente.');
}
```

---

## 9. VULNERABILIDADES DE SEGURIDAD

### 9.1 CRÍTICO: Uso de MD5 para Contraseñas

**Ubicación:** `app/Models/Usuario.php` línea 33-36

```php
public function setPasswordAttribute($value)
{
    $this->attributes['password'] = md5(md5($value));
}
```

**Problema:**
- MD5 es un algoritmo de hash **criptográficamente roto**
- Existen tablas rainbow que pueden descifrar MD5 en segundos
- El doble MD5 no añade seguridad significativa

**Riesgo:** Si un atacante obtiene acceso a la base de datos, puede descifrar las contraseñas de TODOS los usuarios en cuestión de horas.

**Solución:**
```php
// Usar bcrypt de Laravel (nativo)
public function setPasswordAttribute($value)
{
    $this->attributes['password'] = bcrypt($value);
}

// O usar Hash facade
use Illuminate\Support\Facades\Hash;
$this->attributes['password'] = Hash::make($value);
```

### 9.2 CRÍTICO: APP_DEBUG=true en Producción

**Ubicación:** `.env` línea 4

```
APP_DEBUG=true
```

**Problema:**
- Expone stacktraces completos con rutas de archivos
- Muestra variables de entorno sensibles
- Revela estructura interna de la aplicación

**Solución:**
```
APP_DEBUG=false
```

### 9.3 ALTO: Base de Datos sin Contraseña

**Ubicación:** `.env` líneas 11-16

```
DB_HOST=127.0.0.1
DB_USERNAME=root
DB_PASSWORD=
```

**Problema:**
- Acceso sin autenticación a la base de datos
- Cualquier proceso en el servidor puede acceder

**Solución:**
- Crear usuario MySQL específico para la aplicación
- Asignar contraseña fuerte
- Limitar permisos solo a la base de datos necesaria

### 9.4 MEDIO: Rutas AJAX sin Protección CSRF

**Ubicación:** `routes/web.php` líneas 69-78

```php
Route::prefix('ajax/citas')->group(function () {
    Route::get('/consultorios-por-estado/{estadoId}', ...);
    Route::get('/horarios-disponibles', ...);
    // Estas rutas son públicas
});
```

**Problema:**
- Aunque son GET (no modifican datos), exponen información
- Podrían usarse para enumerar médicos, consultorios, disponibilidad

**Solución:**
- Implementar rate limiting
- Considerar si algunas deben requerir autenticación

### 9.5 MEDIO: Falta de Validación de Archivos

**Ubicación:** `PagoController.php` - subida de comprobantes

**Problema potencial:**
- Si no se valida tipo de archivo, podrían subirse scripts maliciosos
- Falta validación de tamaño máximo

**Solución:**
```php
$request->validate([
    'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
]);
```

### 9.6 BAJO: Token de Recuperación Débil

**Ubicación:** `AuthController.php`

**Problema:**
- Los tokens de recuperación podrían no expirar
- No hay límite de intentos

**Solución:**
- Implementar expiración de 1 hora
- Limitar a 3 intentos por IP

### 9.7 INFORMATIVO: API Deshabilitada pero Presente

**Ubicación:** `routes/api.php`

Aunque está comentada, el código revela la estructura de una API REST que podría activarse.

---

## 10. MEJORAS RECOMENDADAS

### 10.1 Seguridad (Prioridad Alta)

| # | Mejora | Dificultad | Impacto |
|---|--------|------------|---------|
| 1 | Migrar de MD5 a bcrypt | Media | Crítico |
| 2 | Cambiar APP_DEBUG=false | Baja | Alto |
| 3 | Configurar contraseña MySQL | Baja | Alto |
| 4 | Implementar rate limiting | Media | Medio |
| 5 | Agregar validación de archivos | Baja | Medio |
| 6 | Implementar 2FA | Alta | Alto |

### 10.2 Arquitectura (Prioridad Media)

| # | Mejora | Descripción |
|---|--------|-------------|
| 1 | Separar lógica de negocio | Mover lógica de controladores a Services |
| 2 | Implementar Repository Pattern | Para modelos complejos como Cita |
| 3 | Agregar capa de caché | Redis para consultas frecuentes |
| 4 | Implementar Jobs para emails | Usar queues en vez de envío síncrono |

### 10.3 Rendimiento (Prioridad Media)

| # | Mejora | Descripción |
|---|--------|-------------|
| 1 | Optimizar consultas N+1 | Agregar eager loading faltante |
| 2 | Indexar columnas de búsqueda | estado, fecha_cita, medico_id en citas |
| 3 | Implementar paginación | En listados grandes |
| 4 | Comprimir respuestas | Habilitar gzip |

### 10.4 Funcionalidad (Prioridad Baja)

| # | Mejora | Descripción |
|---|--------|-------------|
| 1 | Activar API REST | Completar y habilitar rutas API |
| 2 | Implementar WebSockets | Notificaciones en tiempo real |
| 3 | Agregar reportes PDF | Exportar historias, facturas |
| 4 | Implementar backup automático | Schedule de backups diarios |

---

## 11. HERRAMIENTAS DE DESARROLLO

### 11.1 Comandos NPM

| Comando | Descripción |
|---------|-------------|
| `npm run dev` | Inicia servidor de desarrollo Vite con hot reload |
| `npm run build` | Compila assets para producción |
| `npm run preview` | Previsualiza build de producción |

### 11.2 Comandos Artisan

| Comando | Descripción |
|---------|-------------|
| `php artisan serve` | Inicia servidor de desarrollo Laravel |
| `php artisan migrate` | Ejecuta migraciones pendientes |
| `php artisan migrate:fresh --seed` | Reinicia BD y ejecuta seeders |
| `php artisan db:seed` | Ejecuta seeders |
| `php artisan cache:clear` | Limpia caché de aplicación |
| `php artisan config:clear` | Limpia caché de configuración |
| `php artisan route:list` | Lista todas las rutas |
| `php artisan tinker` | Consola interactiva PHP |

### 11.3 Configuración de Desarrollo

**Laragon:** El proyecto está configurado para ejecutarse en Laragon con:
- URL: `http://localhost/sisreservamedicasoring/public`
- PHP 8.1+
- MySQL
- Composer

**Para iniciar desarrollo:**
```bash
# Terminal 1: Backend
php artisan serve

# Terminal 2: Frontend
npm run dev
```

---

## 12. FALLAS ACTUALES DEL PROYECTO

### 12.1 Fallas Identificadas

| # | Falla | Ubicación | Descripción |
|---|-------|-----------|-------------|
| 1 | API comentada | `routes/api.php` | Toda la API REST está deshabilitada |
| 2 | Mailtrap para emails | `.env` | Emails no se envían realmente |
| 3 | Pusher sin configurar | `.env` | Variables de Pusher vacías |
| 4 | Archivos de debug | Raíz | `debug_mail.php`, `debug_design.php` en producción |
| 5 | Archivos de errores | Raíz | `erroresVistaAdmin`, `erroresVistaMedico` visibles |

### 12.2 Código Muerto

- Archivo `diagnostico_manual.php` en raíz
- Archivo `{'Create` (nombre corrupto) en raíz
- Ruta temporal `/fix-payment-methods` en `web.php`

### 12.3 Deuda Técnica

1. **CitaController demasiado grande:** 1800+ líneas, debería dividirse
2. **Lógica duplicada:** Código de ubicación repetido en múltiples controladores
3. **Validaciones inconsistentes:** Algunas en controlador, otras en Request
4. **Falta de tests:** No se encontraron tests automatizados

---

## RESUMEN EJECUTIVO

El Sistema de Reservas Médicas es una aplicación **funcional y completa** que cubre las necesidades básicas de gestión de una clínica. Sin embargo, presenta **vulnerabilidades de seguridad críticas** que deben abordarse antes de cualquier despliegue en producción:

1. **Cambiar el hash de contraseñas de MD5 a bcrypt** (crítico)
2. **Deshabilitar modo debug** (crítico)
3. **Configurar contraseña de base de datos** (alto)
4. **Limpiar archivos de desarrollo** (medio)

La arquitectura es sólida pero podría beneficiarse de refactorización en controladores grandes y separación de responsabilidades en Services.

---

*Documento generado automáticamente por análisis de código.*
