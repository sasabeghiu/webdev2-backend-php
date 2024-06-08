# Use the official MySQL image from the Docker Hub
FROM mysql:latest

# Set environment variables for MySQL
ENV MYSQL_ROOT_PASSWORD=secret123
ENV MYSQL_DATABASE=developmentdb
ENV MYSQL_USER=developer
ENV MYSQL_PASSWORD=secret123

# Copy initialization scripts
COPY developmentdb.sql /docker-entrypoint-initdb.d/

# Expose the default MySQL port
EXPOSE 3306
