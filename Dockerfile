FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pgsql pdo pdo_pgsql zip

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80