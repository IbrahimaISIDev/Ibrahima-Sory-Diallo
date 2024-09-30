# start.sh
#!/bin/bash

echo "Démarrage du script..."

# Vérification de l'environnement
echo "Variables d'environnement:"
env

# Démarrage de PHP-FPM
echo "Démarrage de PHP-FPM..."
php-fpm -D
sleep 2

# Vérification de PHP-FPM
echo "Vérification de PHP-FPM..."
if ! ps aux | grep -q "[p]hp-fpm"; then
    echo "ERREUR: PHP-FPM n'a pas démarré correctement"
    exit 1
else
    echo "PHP-FPM est en cours d'exécution"
fi

# Vérification des fichiers et permissions Laravel
echo "Vérification des fichiers et permissions Laravel..."
ls -la /var/www
ls -la /var/www/storage
ls -la /var/www/bootstrap/cache

# Génération de la clé d'application si nécessaire
if [ -z "$APP_KEY" ]; then
    echo "Génération de la clé d'application..."
    php artisan key:generate --force
fi

# Exécution des migrations si nécessaire
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Exécution des migrations..."
    php artisan migrate --force
fi

# Démarrage de Nginx
echo "Démarrage de Nginx..."
nginx -g 'daemon off;'