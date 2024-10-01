#!/bin/sh
start.sh
# Démarre Nginx
nginx
nginx -t

# Démarre PHP-FPM
php-fpm