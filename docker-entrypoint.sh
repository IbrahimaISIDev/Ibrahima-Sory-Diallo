# docker-entrypoint.sh
#!/bin/sh

echo "Waiting for database..."
wait-for-it ${DB_HOST}:${DB_PORT} -t 60

echo "Running database migrations..."
php artisan migrate --force

echo "Starting PHP-FPM..."
php-fpm