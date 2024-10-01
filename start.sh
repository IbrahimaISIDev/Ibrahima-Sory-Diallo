# start.sh
#!/bin/sh

echo "Starting deployment script..."

# Attendre que la base de données soit prête
echo "Waiting for database..."
until nc -z -v -w30 $DB_HOST $DB_PORT
do
  echo "Waiting for database connection..."
  sleep 5
done

# Exécuter les migrations de la base de données
echo "Running database migrations..."
php artisan migrate --force

# Démarrer le serveur Laravel
echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}