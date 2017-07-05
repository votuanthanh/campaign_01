# campaign_01

## Setup
- Clone the repo
- Add permission to folder `sudo chmod 777 -R storage`, `sudo chmod 777 -R bootstrap/cache`
- From the current project diractory run `docker-compose build` `docker-compose up`
- Login docker container web `docker exec -it campaign-web bash`
- `cp .env.example .env`
- `composer install --no-scripts`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- Install node modules : `npm install`
- Run webpack : `npm run watch`

## Configs
**Creating A Password Grant Client**
- `php artisan passport:install`
- `php artisan passport:client --personal`

Config API_CLIENT_SECRET and API_CLIENT_id in .env 

## Testing
**Prepare database**
- php artisan migrate --database=mysql_test
- php artisan db:seed --database=mysql_test

**Run**
- `$ ./vendor/bin/phpunit`
