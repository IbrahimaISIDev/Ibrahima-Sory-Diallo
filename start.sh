#!/bin/sh

echo "Starting deployment script..."

# Attendre que la base de données soit prête
echo "Waiting for database..."
wait-for-it ${DB_HOST}:${DB_PORT} -t 60

# Démarrer PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# Exécuter les migrations de la base de données
echo "Running database migrations..."
php artisan migrate --force

# Démarrer le serveur Laravel
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}