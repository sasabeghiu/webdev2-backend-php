# PHP.Dockerfile
FROM php:7.4-fpm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy application files
COPY ./app /app
WORKDIR /app

# Expose port
EXPOSE 80

# Command to run the PHP server
CMD ["php-fpm"]
