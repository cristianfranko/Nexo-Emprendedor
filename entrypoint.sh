#!/bin/sh

# 1. Crear directorios de framework que Laravel necesita
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data

# 2. Crear el directorio temporal para los audios del chatbot
mkdir -p storage/app/temp

# 3. Crear directorios temporales de Nginx
mkdir -p /var/lib/nginx/tmp/client_body

# 4. Asignar permisos a TODAS las carpetas necesarias
chown -R www-data:www-data /var/lib/nginx
chown -R www-data:www-data storage bootstrap/cache

# 5. Crear el enlace simbólico del storage
php artisan storage:link

# 6. Ejecutar optimizaciones
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Ejecutar migraciones
php artisan migrate --force

# 8. Iniciar los servicios
exec "$@"