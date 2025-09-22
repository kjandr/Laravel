1. Installiere Ubuntu 24.04
2. Docker installieren und 'proxy' Netzwerk anlegen
3. NPM starten
4. Laravel von GIT holen 

git clone https://github.com/kjandr/Laravel.git laravel
cd laravel
docker compose up -d --build
docker exec -it laravel_app composer install
docker exec -it laravel_node bash
npm install -g npm@11.6.0
npm run
exit
cp src/.env.example src/.env
docker exec -it laravel_app php artisan key:generate
docker exec -it laravel_app php artisan migrate

docker exec -it laravel_app bash
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
touch /var/www/database/database.sqlite
chown www-data:www-data /var/www/database/database.sqlite
chmod 664 /var/www/database/database.sqlite
docker compose -f laravel/docker-compose.yml down
docker compose -f laravel/docker-compose.yml up -d --build

