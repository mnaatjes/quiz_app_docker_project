# php-fpm/Dockerfile
FROM php:8.2-fpm

# Install mysqli extension
# docker-php-ext-install is a helper script provided by the official PHP images
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# Install bcmath extension
RUN docker-php-ext-install bcmath

# Install GMP extension dependencies and then the extension
RUN apt-get update && \
    apt-get install -y libgmp-dev && \
    rm -rf /var/lib/apt/lists/*


# Install GMP extension
# docker-php-ext-install automatically handles dependencies for common extensions
RUN docker-php-ext-install gmp
