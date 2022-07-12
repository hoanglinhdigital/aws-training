FROM composer:2.0.7 as builder

WORKDIR /app
COPY . /app
RUN composer install

FROM php:7.4-fpm
RUN docker-php-ext-install pdo pdo_mysql
# Copy the current project
WORKDIR /var/www
COPY . /var/www
COPY --from=builder /app/vendor /var/www/vendor/

# Move all the current .env.example to .env
# All other env variable will be defined in docker-compose.yml
RUN cp /var/www/.env.example /var/www/.env

# This is hot fix for specific folder /var/www/storage
RUN chmod -R 777 /var/www/storage
RUN php artisan key:generate
CMD ["php-fpm"]
