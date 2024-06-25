# Use an official PHP runtime as a parent image
FROM php:fpm

# Install PDO MySQL extension (if needed)
RUN docker-php-ext-install pdo pdo_mysql

# Install additional dependencies (adjust as necessary)
RUN apt-get update && apt-get install -y git unzip libzip-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /app

# Copy the entire application to the container
COPY . .

# Expose port 8000 (or the port your PHP server listens on)
EXPOSE 8000

# Command to run PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
