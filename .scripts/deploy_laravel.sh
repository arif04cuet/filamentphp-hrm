#!/bin/bash

export COMPOSER_HOME="$HOME/.config/composer";
set -e

echo "Deployment started .."

# Enter maintenance mode or return true
# if already is in maintenance mode
#(php artisan down) || true

# Pull the latest version of the app
#git pull origin main

# Install composer dependencies
composer install --ignore-platform-reqs --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan cache:clear
php artisan config:clear

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

#install packagess
#npm install
# Compile npm assets
#npm run prod

# Run database migrations
php artisan migrate --force

#####
#php artisan storage:link

#add write permission to cache files

chown www-data:www-data storage -R
chown www-data:www-data bootstrap/cache -R
chmod -R g+w ./bootstrap/cache
chmod -R g+w ./storage
chmod -R ug+s ./bootstrap/cache
chmod -R ug+s ./storage

# Exit maintenance modes
#php artisan up

# run queue worker
#php artisan queue:restart
#php artisan queue:work

#done
echo "Deployment finished!"