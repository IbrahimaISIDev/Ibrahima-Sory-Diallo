FROM php:8.3-fpm

# Installation des dépendances système et des extensions PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    nginx \
    # Installation de l'extension MongoDB
    libcurl4-openssl-dev \
    libssl-dev \
    pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql pdo_pgsql zip opcache

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www

# Copie des fichiers du projet
COPY . /var/www

# Installation des dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Configuration des permissions
RUN mkdir -p /var/www/storage/logs /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# Configuration de Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
#COPY nginx/default.conf /etc/nginx/sites-available/default
#RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copie env.example .env et génération de clé
# COPY .env.example .env
# RUN php artisan key:generate

# Nettoyage
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Exposition du port
EXPOSE 9005

# Copie et configuration du script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Commande de démarrage
CMD ["sh", "/usr/local/bin/start.sh"]
# ou si tu veux garder l'option artisan
# CMD php artisan serve --host=0.0.0.0 --port=9005