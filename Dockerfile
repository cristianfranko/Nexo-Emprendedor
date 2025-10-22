# --- Etapa 1: Builder ---
# Instala dependencias de PHP
FROM composer:2 as builder
WORKDIR /app
COPY . .

RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs

# --- Etapa 2: Node Builder ---
# Instala dependencias de Node y construye el frontend
FROM node:18-alpine as node_builder
WORKDIR /app
COPY . .
COPY --from=builder /app/vendor /app/vendor
RUN npm install
RUN npm run build

# --- Etapa 3: Aplicación Final ---
# Imagen final ligera con PHP y Nginx
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    postgresql-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql pdo_mysql zip bcmath gd

# Limpiar cache de apk
RUN rm -rf /var/cache/apk/*

# Copiar configuraciones
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos de la aplicación desde las etapas anteriores
COPY --from=builder /app .
COPY --from=node_builder /app/public/build ./public/build

# Copiar el script de entrada y darle permisos de ejecución
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 80

# Definir el punto de entrada y el comando por defecto
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]