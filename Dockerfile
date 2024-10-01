# Dockerfile
FROM php:8.3-fpm

# Installation des dépendances et extensions PHP
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip gd mbstring exif pcntl bcmath

# Installation de l'extension MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation de wait-for-it
ADD https://github.com/vishnubob/wait-for-it/raw/master/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

WORKDIR /var/www

COPY . .

RUN chown -R www-data:www-data /var/www

RUN composer install --no-dev --optimize-autoloader

COPY .env.example .env
RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

EXPOSE 9000

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

CMD ["/usr/local/bin/docker-entrypoint.sh"]