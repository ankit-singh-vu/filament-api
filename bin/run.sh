#!/bin/bash

# Check if the vendor directory exists

APP_URL=$(grep APP_URL .env | cut -d '=' -f2-)
DOMAIN=$(echo "$APP_URL" | sed -r 's/^https?:\/\/([^\/]+).*/\1/')

cp /var/www/html/nginx/nginx.conf /nginx
sed -i "s/convesio.local/$DOMAIN/g" /nginx/nginx.conf

IP_ADD=$(hostname -i);

if ! grep -q "^$IP_ADD[[:space:]]*$DOMAIN$" /etc/hosts; then
    echo "$IP_ADD    $DOMAIN" >> /etc/hosts
    echo "$IP_ADD    api.$DOMAIN" >> /etc/hosts
    echo "$IP_ADD    convesio.local" >> /etc/hosts
else
    echo "$DOMAIN with IP $IP_ADD already exists in /etc/hosts"
fi

if [ ! -d "vendor" ]; then
  echo "Vendor directory does not exist. Installing dependencies..."
  composer install
else
  echo "Vendor directory exists. Skipping installation."
fi
composer update 
#run database migration


if [ ! -d "node_modules" ]; then
  echo "node_modules directory does not exist. Installing dependencies..."
  npm install 
else
  echo "node_modules directory exists. Skipping installation."
fi

rm -r /tmp/sshkeys/ && mkdir -p /tmp/sshkeys/ && chmod 0777 /tmp/sshkeys/

rm -r /tmp/work && mkdir -p /tmp/work && chmod 0777 /tmp/work/

chmod -R 0777 /tmp/work/

chmod -R 0777 /tmp/sshkeys/
php artisan migrate --force
npm run build 
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=ClusterLocation
php artisan db:seed --class=ClusterType
php artisan db:seed --class=Provider
php artisan db:seed --class=ScmNetwork
php artisan db:seed --class=SharedStorage
php artisan db:seed --class=Vmpackage


php artisan reverb:start --debug &
php artisan pulse:check &
php artisan queue:work &
php artisan queue:work &
php artisan queue:work &
npm run dev &

chmod 0777 -R /var/www/html/storage
# Run the original entrypoint command
start-container

chmod 0777 -R /var/www/html/storage

# Keep the container running (optional, adjust as needed)
tail -f /dev/null
