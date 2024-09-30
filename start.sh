#!/bin/sh

# Démarrer PHP-FPM en arrière-plan
php-fpm &

# Démarrer Nginx au premier plan
nginx -g 'daemon off;'
