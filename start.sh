#!/bin/sh

# Démarrer PHP-FPM
php-fpm -D

# Exécuter les migrations de la base de données
php artisan migrate --force

# Démarrer le serveur Laravel
php artisan serve --host=0.0.0.0 --port=9000