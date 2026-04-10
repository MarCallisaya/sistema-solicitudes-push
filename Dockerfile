FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pgsql pdo pdo_pgsql zip

# Activar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir carpeta de trabajo
WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . /var/www/html

# Instalar dependencias PHP (IMPORTANTE 🔥)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Configuración de Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Exponer puerto
EXPOSE 80