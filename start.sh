#!/bin/sh

# Démarrer PHP-FPM
php-fpm &

# Démarrer Nginx
nginx -g 'daemon off;'