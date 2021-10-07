#!/bin/sh
# This is a really basic copy paste from the original docs, I recommend using the docs instead of this
cd /var/www/manager
sudo php artisan down
sudo git stash
sudo git pull
sudo chmod -R 755 /var/www/manager
sudo php artisan migrate --seed --force
sudo php artisan view:clear
sudo php artisan config:clear
sudo composer install --no-dev --optimize-autoloader
npm install express express-ws ws axios
sudo chown -R www-data:www-data /var/www/manager/*
sudo php artisan queue:restart
sudo php artisan up
clear
echo "Successfully updated"