#!/bin/bash
set -e

rm -f /app/.env

echo "==> Generating .env from environment variables..."

php -r "
\$lines = [
    'APP_NAME=' . (getenv('APP_NAME') ?: 'AgrogranjaBovinos'),
    'APP_ENV=' . (getenv('APP_ENV') ?: 'production'),
    'APP_KEY=' . (getenv('APP_KEY') ?: ''),
    'APP_DEBUG=' . (getenv('APP_DEBUG') ?: 'false'),
    'APP_URL=' . str_replace('http://', 'https://', (getenv('APP_URL') ?: 'http://localhost')),
'ASSET_URL=' . str_replace('http://', 'https://', (getenv('APP_URL') ?: 'http://localhost')),
    '',
    'LOG_CHANNEL=' . (getenv('LOG_CHANNEL') ?: 'stack'),
    'LOG_LEVEL=' . (getenv('LOG_LEVEL') ?: 'debug'),
    '',
    'DB_CONNECTION=mysql',
    'DB_HOST=' . (getenv('DB_HOST') ?: '127.0.0.1'),
    'DB_PORT=' . (getenv('DB_PORT') ?: '3306'),
    'DB_DATABASE=' . (getenv('DB_DATABASE') ?: 'railway'),
    'DB_USERNAME=' . (getenv('DB_USERNAME') ?: 'root'),
    'DB_PASSWORD=' . (getenv('DB_PASSWORD') ?: ''),
    '',
    'CACHE_DRIVER=' . (getenv('CACHE_DRIVER') ?: 'array'),
    'SESSION_DRIVER=' . (getenv('SESSION_DRIVER') ?: 'cookie'),
    'SESSION_LIFETIME=' . (getenv('SESSION_LIFETIME') ?: '120'),
    'QUEUE_CONNECTION=' . (getenv('QUEUE_CONNECTION') ?: 'sync'),
    'FILESYSTEM_DISK=' . (getenv('FILESYSTEM_DISK') ?: 'local'),
    'CLOUDINARY_CLOUD_NAME=' . (getenv('CLOUDINARY_CLOUD_NAME') ?: ''),
    'CLOUDINARY_API_KEY=' . (getenv('CLOUDINARY_API_KEY') ?: ''),
    'CLOUDINARY_API_SECRET=' . (getenv('CLOUDINARY_API_SECRET') ?: ''),
    'CLOUDINARY_URL=' . (getenv('CLOUDINARY_URL') ?: ''),
];
file_put_contents('/app/.env', implode(PHP_EOL, \$lines) . PHP_EOL);
echo 'DB_HOST=' . (getenv('DB_HOST') ?: 'NOT SET') . PHP_EOL;
echo 'DB_DATABASE=' . (getenv('DB_DATABASE') ?: 'NOT SET') . PHP_EOL;
"
echo "==> Generating .env from environment variables..."

echo "==> Running migrations..."
php artisan config:clear
php artisan migrate --force


echo "==> Starting server on port ${PORT:-8080}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}