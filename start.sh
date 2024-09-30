#!/bin/bash

echo "Démarrage du script..."

# Vérification de l'environnement
echo "Variables d'environnement:"
env

# Vérification du contenu du répertoire de l'application
echo "Contenu du répertoire de l'application:"
ls -la /var/www

echo "Contenu du répertoire public:"
ls -la /var/www/public

echo "Vérification des permissions:"
stat /var/www/public/index.php

# Vérification du fichier firebase
echo "Vérification du fichier firebase:"
ls -la /var/www/storage/firebase/

# Démarrage de PHP-FPM
echo "Démarrage de PHP-FPM..."
php-fpm