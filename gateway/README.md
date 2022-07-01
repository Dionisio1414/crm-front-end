# Microservice Gateway

### If you use one host
```
Uncomment in envs/.env DB_HOST
```

### Connect to container Gateway
```
docker-compose exec gateway bash
```

### Migrations Database
```
php artisan migrate
php artisan migrate --path /database/migrations/core
```

### Install passport
```
php artisan passport:install
```

