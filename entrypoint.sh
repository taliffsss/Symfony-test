#!/bin/bash
set -e

# Wait for MySQL service to be ready
wait-for-it.sh mysql:3306 --timeout=30

# Run Symfony migrations
php bin/console doctrine:migrations:migrate --no-interaction

# Start the Symfony application
php -S 0.0.0.0:8000 -t public
