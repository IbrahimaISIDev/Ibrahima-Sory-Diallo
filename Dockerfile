# Utiliser l'image PHP officielle avec extensions
FROM php:8.3-fpm

# Installer des dépendances et les extensions PHP nécessaires
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
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd mbstring exif pcntl bcmath

# Installer l'extension MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer wait-for-it
ADD https://github.com/vishnubob/wait-for-it/raw/master/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/wait-for-it

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers du projet dans le conteneur
COPY . .

# Configurer les permissions sur le répertoire de travail
RUN chown -R www-data:www-data /var/www

# Installer les dépendances du projet
RUN composer install --no-dev --optimize-autoloader

# Copier le fichier d'environnement et générer la clé
COPY .env.example .env
RUN php artisan key:generate

# Configurer les permissions sur le stockage et le cache
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Exposer le port
EXPOSE 9000

# Copier le script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Commande pour démarrer l'application
CMD ["/usr/local/bin/start.sh"]