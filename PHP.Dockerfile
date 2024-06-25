# Use an official PHP runtime as a parent image
FROM php:fpm

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Install additional dependencies
RUN apt-get update && apt-get install -y git unzip libzip-dev
RUN docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory in the container
WORKDIR /app

# Copy application files to the container
COPY . /app

# Expose port 8000 to the Docker environment
EXPOSE 8000

# Command to run PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
