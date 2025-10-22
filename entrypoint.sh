#!/bin/sh

# entrypoint.sh

# Ejecutar las optimizaciones de producción de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar las migraciones de la base de datos
php artisan migrate --force

# Iniciar Supervisor para gestionar Nginx y PHP-FPM
# exec "$@" permite que supervisord se convierta en el proceso principal (PID 1)
exec "$@"