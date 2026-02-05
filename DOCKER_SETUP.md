# 🐳 Configuración Docker para ReservaMedica

## 📋 Requisitos Previos

- Docker Desktop instalado (Windows/Mac) o Docker Engine (Linux)
- Docker Compose (generalmente incluido con Docker Desktop)
- Al menos 4GB de RAM disponibles
- Al menos 10GB de espacio libre en disco

## 🚀 Ejecución Rápida

### 1. Clonar el Proyecto (si no está clonado)
```bash
git clone <URL_DEL_REPOSITORIO>
cd ReservaMedica
```

### 2. Configurar Variables de Entorno
```bash
# Copiar archivo de entorno Docker
cp .env.docker .env

# Generar clave de aplicación
php artisan key:generate
```

### 3. Construir y Ejecutar Contenedores
```bash
# Construir imágenes y levantar servicios
docker-compose up -d --build

# Verificar que los contenedores estén corriendo
docker-compose ps
```

### 4. Instalar Dependencias y Migrar Base de Datos
```bash
# Entrar al contenedor de la aplicación
docker-compose exec app bash

# Instalar dependencias de Composer
composer install

# Migrar la base de datos
php artisan migrate

# Sembrar la base de datos (opcional)
php artisan db:seed

# Optimizar la aplicación
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Salir del contenedor
exit
```

### 5. Acceder a la Aplicación

- **Aplicación Web**: http://localhost:8080
- **PhpMyAdmin**: http://localhost:8081
  - Servidor: `mysql`
  - Usuario: `root`
  - Contraseña: `root_password`

## 📁 Estructura de Archivos Docker

```
ReservaMedica/
├── Dockerfile                    # Configuración del contenedor PHP
├── docker-compose.yml           # Orquestación de servicios
├── .dockerignore               # Archivos ignorados en Docker
├── .env.docker                 # Variables de entorno para Docker
├── docker/
│   ├── nginx/
│   │   └── default.conf        # Configuración de Nginx
│   └── mysql/
│       └── my.cnf             # Configuración de MySQL
└── DOCKER_SETUP.md            # Este archivo
```

## 🔧 Servicios Configurados

### 1. **app** (Laravel + PHP-FPM)
- **Imagen**: PHP 8.1-FPM
- **Extensiones**: MySQL, GD, Zip, Bcmath, Mbstring, etc.
- **Volumen**: Código fuente montado
- **Red**: `reservamedica_network`

### 2. **nginx** (Servidor Web)
- **Imagen**: Nginx Alpine
- **Puerto**: 8080 (host) → 80 (contenedor)
- **Configuración**: Optimizada para Laravel
- **Dependencias**: app

### 3. **mysql** (Base de Datos)
- **Imagen**: MySQL 8.0
- **Puerto**: 3306 (host) → 3306 (contenedor)
- **Base de datos**: `reservamedica`
- **Volumen**: Datos persistentes
- **Credenciales**:
  - Root: `root_password`
  - Usuario: `reservamedica_user`
  - Contraseña: `reservamedica_password`

### 4. **redis** (Caché y Sesiones)
- **Imagen**: Redis 7 Alpine
- **Puerto**: 6379 (host) → 6379 (contenedor)
- **Volumen**: Datos persistentes

### 5. **phpmyadmin** (Administración MySQL)
- **Imagen**: PhpMyAdmin
- **Puerto**: 8081 (host) → 80 (contenedor)
- **Dependencias**: mysql

## 🛠️ Comandos Útiles

### Gestión de Contenedores
```bash
# Iniciar todos los servicios
docker-compose up -d

# Detener todos los servicios
docker-compose down

# Reconstruir y levantar servicios
docker-compose up -d --build

# Ver logs de un servicio específico
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql

# Entrar al contenedor de la aplicación
docker-compose exec app bash

# Ejecutar comandos artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan tinker
docker-compose exec app php artisan queue:work
```

### Mantenimiento
```bash
# Limpiar imágenes no utilizadas
docker image prune -f

# Limpiar volúmenes no utilizados
docker volume prune -f

# Reiniciar un servicio específico
docker-compose restart app

# Actualizar dependencias
docker-compose exec app composer update
```

### Base de Datos
```bash
# Hacer backup de la base de datos
docker-compose exec mysql mysqldump -u root -proot_password reservamedica > backup.sql

# Restaurar base de datos
docker-compose exec -T mysql mysql -u root -proot_password reservamedica < backup.sql

# Acceder a MySQL directamente
docker-compose exec mysql mysql -u root -proot_password reservamedica
```

## 🔍 Troubleshooting

### Problemas Comunes

#### 1. Error de Conexión a Base de Datos
```bash
# Verificar que MySQL esté corriendo
docker-compose ps mysql

# Reiniciar MySQL
docker-compose restart mysql

# Ver logs de MySQL
docker-compose logs mysql
```

#### 2. Error de Permisos
```bash
# Corregir permisos de storage
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

#### 3. Error de Memoria
```bash
# Aumentar límite de memoria de PHP
docker-compose exec app php -d memory_limit=512M artisan migrate
```

#### 4. Puerto Ocupado
```bash
# Cambiar puerto en docker-compose.yml
ports:
  - "8081:80"  # Cambiar a otro puerto si 8080 está ocupado
```

### Verificación de Estado
```bash
# Verificar todos los servicios
docker-compose ps

# Verificar uso de recursos
docker stats

# Verificar espacio en disco
docker system df
```

## 📊 Monitoreo y Logs

### Logs en Tiempo Real
```bash
# Ver todos los logs
docker-compose logs -f

# Ver logs específicos
docker-compose logs -f app nginx mysql redis
```

### Monitoreo de Recursos
```bash
# Estadísticas de contenedores
docker stats

# Información detallada de contenedores
docker inspect reservamedica_app
```

## 🔄 Flujo de Desarrollo

### 1. Desarrollo Local
```bash
# Levantar servicios
docker-compose up -d

# Hacer cambios en el código
# Los cambios se reflejan automáticamente (volumen montado)

# Si necesitas ejecutar comandos
docker-compose exec app php artisan migrate:fresh --seed
```

### 2. Producción
```bash
# Usar variables de entorno de producción
cp .env.production .env

# Construir imagen optimizada
docker-compose -f docker-compose.prod.yml up -d --build
```

## 🚨 Consideraciones de Seguridad

### Para Producción
1. Cambiar contraseñas por defecto
2. Usar HTTPS (configurar certificados SSL)
3. No exponer MySQL a internet
4. Usar variables de entorno seguras
5. Configurar firewall adecuadamente

### Variables Críticas a Cambiar
```bash
# En docker-compose.yml
MYSQL_ROOT_PASSWORD=tu_contraseña_segura
MYSQL_PASSWORD=tu_contraseña_segura

# En .env
APP_KEY=generar_nueva_clave
APP_DEBUG=false
```

## 📚 Referencias Adicionales

- [Documentación Oficial Docker](https://docs.docker.com/)
- [Documentación Docker Compose](https://docs.docker.com/compose/)
- [Documentación Laravel](https://laravel.com/docs/)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)

---

## 🎯 Resumen Rápido

```bash
# 1. Configurar entorno
cp .env.docker .env

# 2. Levantar servicios
docker-compose up -d --build

# 3. Configurar aplicación
docker-compose exec app bash
composer install
php artisan migrate
php artisan key:generate
exit

# 4. Acceder
# http://localhost:8080
# http://localhost:8081 (PhpMyAdmin)
```

¡Listo! Tu aplicación ReservaMedica está corriendo en Docker.
