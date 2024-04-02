# FLASK


## Construction de l'image
- Remove *containers* -> `docker ps -qa | xargs docker rm -f`
- Remove *images* -> `docker images -qa | xargs docker image rm -f`
- Build -> `docker build -t myflask:v1.0 . 2>&1 | tee build.log`

## Démarrage sans Redis (Dockerfile)

- Run -> `docker run --name c1 --publish 5000:5000 myflask:v1.0`
- Logs -> `docker logs c1`
- Start containers* -> `docker start c1`

## Démarrage avec Redis (docker-compose)

- Run -> `docker-compose up`