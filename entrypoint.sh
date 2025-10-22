#!/bin/sh

mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data

chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

exec "$@"