# Use the official PHP image
FROM php:latest

# Set working directory in the container
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Copy the Symfony project files to the container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony dependencies
RUN composer install

# Expose port 8000
EXPOSE 8000

# Run Symfony using PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
