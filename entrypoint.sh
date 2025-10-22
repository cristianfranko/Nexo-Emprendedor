#!/bin/sh

# entrypoint.sh

# 1. Crear las carpetas de framework que Laravel necesita pero que no están en Git.
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data

# 2. Asegurar los permisos correctos en todas las carpetas necesarias en cada inicio.
chown -R www-data:www-data storage bootstrap/cache

# 3. Ejecutar las optimizaciones de producción de Laravel.
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Ejecutar las migraciones de la base de datos.
php artisan migrate --force

# 5. Iniciar Supervisor para gestionar Nginx y PHP-FPM.
exec "$@"