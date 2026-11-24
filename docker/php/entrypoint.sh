#!/bin/sh
set -eu

cd /var/www/html

mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

echo "Waiting for MySQL at ${DB_HOST:-mysql}:${DB_PORT:-3306}..."

attempt=0
until php -r '
    try {
        new PDO(
            sprintf("mysql:host=%s;port=%s", getenv("DB_HOST"), getenv("DB_PORT")),
            getenv("DB_USERNAME"),
            getenv("DB_PASSWORD"),
            [PDO::ATTR_TIMEOUT => 2],
        );
    } catch (Throwable $e) {
        exit(1);
    }
' 2>/dev/null; do
    attempt=$((attempt + 1))

    if [ "$attempt" -ge 30 ]; then
        echo "MySQL is not reachable, giving up." >&2
        exit 1
    fi

    sleep 2
done

php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction
php artisan optimize --no-interaction

exec "$@"
