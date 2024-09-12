FROM php:8.3-cli-alpine

RUN apk update && apk add --no-cache \
  libpng-dev \
  libjpeg-turbo-dev \
  freetype-dev \
  postgresql-dev \
  git \
  unzip \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gd pdo pdo_pgsql \
  && apk del \
  && rm -rf /var/cache/apk/*

WORKDIR /app

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /app \
  && chmod -R 755 /app

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
