# start.sh
#!/bin/sh

# Démarrer PHP-FPM
php-fpm

# Vérifier si PHP-FPM est en cours d'exécution
if ! ps aux | grep -q "[p]hp-fpm"; then
    echo "PHP-FPM n'a pas démarré correctement"
    exit 1
fi

# Démarrer Nginx
nginx -g 'daemon off;'