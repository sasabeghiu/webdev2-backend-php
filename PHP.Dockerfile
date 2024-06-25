FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y git unzip libzip-dev
RUN docker-php-ext-install zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application files
COPY . /app

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
