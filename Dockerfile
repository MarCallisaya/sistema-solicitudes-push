FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pgsql pdo pdo_pgsql zip

RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80