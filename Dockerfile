# Dockerfile

# --- Etapa 1: Builder ---
# Imagen oficial de PHP con Composer para instalar dependencias de forma segura
FROM composer:2 as builder

WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# --- Etapa 2: Aplicación Final ---
# Imagen ligera de Alpine con PHP-FPM
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP que Laravel necesita
# pdo_pgsql para PostgreSQL, bcmath y gd.
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    postgresql-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_pgsql \
    zip \
    bcmath \
    gd

# Limpiar el cache de apk
RUN rm -rf /var/cache/apk/*

# Copiar la configuración de Nginx y Supervisor
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos de la aplicación desde la etapa de 'builder'
COPY --from=builder /app .

# Copiar el .env de producción
COPY .env.production .env

# Instalar dependencias de NPM y construir el frontend, instalar Node.js
RUN apk add --update nodejs npm
RUN npm install && npm run build

# Ajustar permisos para que el servidor web pueda escribir en los directorios de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 80 para Nginx
EXPOSE 80

# Comando para iniciar Nginx y PHP-FPM a través de Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]