# Use the official PHP Apache image as the base
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libmcrypt-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Copy Composer from official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Laravel app to the container
COPY . /var/www/html

# Set proper permissions for the app (excluding storage)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expose port 80 to access the app
EXPOSE 80

# Start Apache in the foreground (required for the container to keep running)
CMD ["apache2-foreground"]
