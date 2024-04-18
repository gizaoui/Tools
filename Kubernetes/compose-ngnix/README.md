 https://percona.community/blog/2023/03/17/how-to-develop-a-simple-web-application-using-docker-nginx-php-and-percona-server-for-mongodb/

```php
$conf['servers'][0]['host'] = '192.168.49.2';
$conf['servers'][0]['port'] = 30432;
$conf['extra_login_security'] = false;
```

```bash
docker rm -f $(docker ps | grep php-fpm | sed 's/ \+/ /g' | cut -d" " -f1) && \
docker image rm -f $(docker images | grep php-fpm | sed 's/ \+/ /g' | cut -d" " -f3) && \
docker build -t php-fpm . && \
docker-compose up -d

docker exec -ti $(docker ps | grep php-fpm | sed 's/ \+/ /g' | cut -d" " -f1) bash
```
