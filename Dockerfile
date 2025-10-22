# --- Etapa 1: Builder ---
FROM composer:2 as builder
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs

# --- Etapa 2: Node Builder ---
FROM node:18-alpine as node_builder
WORKDIR /app
COPY . .
COPY --from=builder /app/vendor /app/vendor
RUN npm install
RUN npm run build

# --- Etapa 3: Aplicación Final ---
FROM php:8.2-fpm-alpine

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

RUN rm -rf /var/cache/apk/*

COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

COPY --from=builder /app .
COPY --from=node_builder /app/public/build ./public/build

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]