#!/bin/sh

# entrypoint.sh 

# Asegurar los permisos correctos en cada inicio
chown -R www-data:www-data storage bootstrap/cache

# Ejecutar las optimizaciones de producción de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar las migraciones de la base de datos
php artisan migrate --force

# Iniciar Supervisor para gestionar Nginx y PHP-FPM
exec "$@"