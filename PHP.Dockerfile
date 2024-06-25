# PHP.Dockerfile
FROM php:7.4-fpm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y git unzip libzip-dev
RUN docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nginx installation
RUN apt-get update && apt-get install -y nginx

# Copy application files
COPY ./app /app
WORKDIR /app

# Copy Nginx configuration
COPY ./nginx.conf /etc/nginx/nginx.conf

# Expose ports
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service php7.4-fpm start && nginx -g 'daemon off;'
