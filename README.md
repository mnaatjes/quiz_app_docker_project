# 1.0 Configuration

* **LastUpdate:** 08-18-2025

## 1.1 Docker-Compose

```yml
version: '3.8'

services:
  web:
    build:
      context: ./apache
    ports:
      - "8087:80"
    volumes:
      - ./quiz_app:/var/www/html
    container_name: quiz_app_apache
    depends_on:
      - php
      - mysql

  php:
    build:
      context: ./php
    volumes:
      - ./quiz_app:/var/www/html
    container_name: quiz_app_php
    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    container_name: quiz_app_mysql
    environment:
      MYSQL_ROOT_PASSWORD: mysecretpassword
      MYSQL_DATABASE: test
    ports:
      - "3308:3306"
    volumes:
      - mysql_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: quiz_app_phpmyadmin
    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: mysecretpassword
    ports:
      - "8080:80"
    depends_on:
      - mysql

volumes:
  mysql_data:
```

## 1.2 php/Dockerfile

```bash
FROM php:8.1-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
```

## 1.3 apache/Dockerfile

```bash
FROM httpd:2.4-alpine

# Copy the vhost.conf file into the container
COPY vhost.conf /usr/local/apache2/conf/extra/vhost.conf

# Include the custom virtual host configuration in the main Apache config
RUN echo "Include conf/extra/vhost.conf" >> /usr/local/apache2/conf/httpd.conf

# Enable mod_rewrite and mod_proxy_fcgi for PHP-FPM
RUN sed -i 's/^#LoadModule\ rewrite_module/LoadModule\ rewrite_module/' /usr/local/apache2/conf/httpd.conf \
    && sed -i 's/^#LoadModule\ proxy_module/LoadModule\ proxy_module/' /usr/local/apache2/conf/httpd.conf \
    && sed -i 's/^#LoadModule\ proxy_fcgi_module/LoadModule\ proxy_fcgi_module/' /usr/local/apache2/conf/httpd.conf
```

## 1.4 apache/vhost.conf

```bash
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>
    <FilesMatch \.php$>
        SetHandler "proxy:fcgi://php:9000"
    </FilesMatch>
    ErrorLog /dev/stderr
    CustomLog /dev/stdout combined
</VirtualHost>
```

