# Use the official PHP with Apache image
FROM php:8.1-apache

# Install necessary PHP extensions and MariaDB client
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    mariadb-client \
    && docker-php-ext-install zip pdo_mysql

# Copy all files from the root directory to the Apache document root
COPY . /var/www/html/

# Set correct permissions for Apache
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Apache is already configured as the default entrypoint in this image
ENTRYPOINT ["/var/www/html/db/db_setup.sh"]
