# start.sh
#!/bin/sh

echo "Starting deployment script..."

# Attendre que la base de données soit prête
echo "Waiting for database..."
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -d "$DB_DATABASE" -c '\q'; do
  echo "Postgres is unavailable - sleeping"
  sleep 1
done

echo "Postgres is up - executing migrations"

# Exécuter les migrations de la base de données
php artisan migrate --force

# Démarrer le serveur Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}