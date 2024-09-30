#!/bin/sh

# Attendre que la base de données soit prête
wait-for-it postgres:5432 -t 60

# Démarrer PHP-FPM
php-fpm -D

# Exécuter les migrations de la base de données
php artisan migrate --force

# Démarrer le serveur Laravel
php artisan serve --port=$PORT