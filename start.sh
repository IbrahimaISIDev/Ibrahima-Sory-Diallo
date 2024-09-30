#!/bin/sh

# Start Nginx
service nginx start

# Start PHP-FPM
php-fpm -D

# Keep the container running
tail -f /dev/null
