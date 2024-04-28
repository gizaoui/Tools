# DOCKER

[TOC]


## Image *Redis*

Le client *python* peut exploiter la base de données *Redis* de trois façons :
- Directement de la machine hôte
- A travers le réseau de la machine hôte (*localhost*)
- A travers un réseau *Docker* (*Bridge*)

### Exploitation direct du client *Redis*

- Importation & création de l'image *Redis* -> `docker run --detach --publish 6379:6379 --name myredis redis`
- Lancer le client python  -> `python3 app.py`
- Tester -> `curl http://127.0.0.1:5000`

### Exploitation conteneurisé du client *Redis* via le réseau de la machine hôte

- Importation & création de l'image *Redis* -> `docker run --detach --publish 6379:6379 --name myredis redis`
- Création de l'image *pyredis* du client python

```bash
cat > Dockerfile <<EOF && docker build -t pyredis . 2>&1 | tee build.log
FROM python:3.10-slim
WORKDIR /app
COPY . /app
RUN pip install -r requirements.txt
# Ouverture port 5000
EXPOSE 5000
# Le container 'myredis' est accessible de la machine hôte
ENV REDISHOST 0.0.0.0
ENV NAME Gilles
CMD ["python", "app.py"]
EOF
```

- Lancement du client python -> `docker run --detach --name mypyredis --network host pyredis`
- Tester -> `curl http://127.0.0.1:5000`

### Exploitation conteneurisé du client *Redis* via le réseau *Docker* (*Bridge*)

- Création du réseau nommé *redisnet* -> `docker network create -d bridge redisnet` 
- Lacement de *Redis* avec accès au réseau *redisnet* non accessible de l'extérieur du *container * -> `docker run --detach --name myredis --network redisnet redis`
- Création de l'image *pyredis* du client python

```bash
cat > Dockerfile <<EOF && docker build -t pyredis . 2>&1 | tee build.log
FROM python:3.10-slim
WORKDIR /app
COPY . /app
RUN pip install -r requirements.txt
# Ouverture port 5000
EXPOSE 5000
# l'IP du container 'myredis' est renvoyé par la commande 'docker inspect myredis' (172.20.0.2)
ENV REDISHOST myredis
ENV NAME Gilles
CMD ["python", "app.py"]
EOF
```

- Lancement de l'image *pyredis* avec accès au réseau *redisnet* -> `docker run --detach --name mypyredis --publish 5000:5000 --network redisnet pyredis`
- Tester -> `curl http://127.0.0.1:5000`


### Docker file

- Création de l'image *pyredis* du client python

```bash
cat > Dockerfile <<EOF && docker build -t pyredis . 2>&1 | tee build.log
FROM python:3.10-slim
WORKDIR /app
COPY . /app
RUN pip install -r requirements.txt
# Ouverture port 5000
EXPOSE 5000
# l'IP du container 'myredis' est renvoyé par la commande 'docker inspect myredis' (172.20.0.2)
ENV REDISHOST myredis
ENV NAME Gilles
CMD ["python", "app.py"]
EOF
```

- Configuration & lancement du *docker-compose*

```bash
cat > docker-compose.yaml <<EOF && docker-compose up
version: '3.9'
services:
  # docker run --detach --name myredis --network redisnet redis
  myredis:
    image: redis:latest
    networks:
      - redisnet
  # docker run --detach --name mypyredis --publish 5000:5000 --network redisnet pyredis
  mypyredis:
    image: pyredis
    depends_on : 
      - myredis
    ports:
      - '5000:5000'
    networks:
      - redisnet
networks:
  redisnet:
EOF
```

## Liens

- https://kinsta.com/fr/blog/redis-cli/
- https://cours.brosseau.ovh/tp/docker/deployer-docker-sur-un-serveur.html