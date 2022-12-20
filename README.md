

## About

builded above laravel 9 framework
  
## System Requirement

- Web Server (Apache or Nginx)
- PHP (min ver 8.0)
- MySQL Database
- composer

## How To Install

- Clone this repository
- copy .env.example to .env
- edit .env file and setting database connection. make sure the database is already created
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
- run "composer install"
- run "php artisan migrate --seed"
- run "php artisan key:generate"
- run "php artisan serve"
- access on [http://localhost:8000](http://localhost:8000)