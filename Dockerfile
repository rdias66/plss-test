FROM php:8.3

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
  openssl \
  zip \
  unzip \
  git \
  libpq-dev \
  libonig-dev \
  && docker-php-ext-install pdo pdo_pgsql mbstring

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy existing application files
COPY . /app

# Install PHP dependencies
RUN composer install

# Expose the application port
EXPOSE 80

# Command to run your application (for example, `artisan serve`)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
