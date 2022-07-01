# Microservice Products

### If you use one host
```
cp .env.example .env
```

### Edit docker
```
Uncomment db if you use one host and :
cp docker/mysql-example/my.cnf docker/mysql/my.cnf
cp docker/mysql-example/setup.sql docker/mysql/setup.sql
```

```
cp docker-compose-example.yml docker-compose.yml
```

```
 mkdir docker/nginx/sites
 cp docker/nginx/sites-example/{auth.htpasswd,gateway.conf,products.conf,spa.conf,admin.conf} docker/nginx/sites
```

### Add ssl cert
```
 mkdir docker/nginx/ssl
 - move your cert in folder ssl
 - cp docker/nginx/nginx.conf.example docker/nginx/nginx.conf
 - and uncomment path for ssl sertificates on docker/nginx/sites/*.conf
```

### Installing with docker
```
docker-compose build
docker-compose up -d
```

### Configure microsevices
```
https://git.apricode.tech/easysell/crm-front-end/-/tree/dev-backend/gateway
https://git.apricode.tech/easysell/crm-front-end/-/tree/dev-backend/products
https://git.apricode.tech/easysell/crm-front-end/-/tree/dev/crm
https://git.apricode.tech/easysell/crm-front-end/-/tree/dev/admin
https://git.apricode.tech/easysell/crm-front-end/-/tree/dev/spa
```

### Connect to virtual docker server
```
docker-compose ps
docker-compose exec <container> bash
```
