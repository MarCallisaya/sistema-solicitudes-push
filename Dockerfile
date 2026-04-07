FROM php:8.1-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    unzip git curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

EXPOSE 10000

CMD php -S 0.0.0.0:10000 index.php