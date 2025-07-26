# php-fpm/Dockerfile
FROM php:8.2-fpm

# Install mysqli extension
# docker-php-ext-install is a helper script provided by the official PHP images
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli