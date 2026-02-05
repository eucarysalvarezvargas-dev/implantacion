# Usar imagen base de PHP con las extensiones necesarias
FROM php:8.1-fpm

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos de la aplicación
COPY . .

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Copiar archivo de configuración de entorno
COPY .env.example .env

# Generar clave de aplicación
RUN php artisan key:generate

# Optimizar la aplicación para producción
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Exponer puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]
