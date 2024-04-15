# DOCKER

Docker permet la création d'environnement *chrooté* perfectionnés.

Peut écraser les données dans l'image lors de la création du point de montage.
*bind* permet d’utiliser les liens symboliques  pour sortir de son propre répertoire.

- `mkdir -p /jail/{bin,lib,lib64}`
- `mount --bind /bin /jail/bin`
- `mount --bind /lib /jail/lib`
- `mount --bind /lib64 /jail/lib64`
- `chroot /jail/ /bin/bash`
- `umount /jail/bin /jail/lib /jail/lib64` 
- `\rm -fr /jail/`


## INSTALLATION


### Docker
- `curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /etc/apt/trusted.gpg.d/debian.gpg`
- `add-apt-repository -y "deb [arch=$(dpkg --print-architecture)] https://download.docker.com/linux/debian $(lsb_release -cs) stable"`
- `apt update -y`
- `apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin`
- `usermod -aG docker gizaoui`

# Minicube
- `cd /opt`
- `curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64`
- `chmod +x ./minikube-linux-amd64`
- `install ./minikube-linux-amd64 /usr/local/bin/minikube`
- `curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"`
- `chmod +x ./kubectl &&  mv ./kubectl /usr/local/bin`
- Test -> `kubectl version --client -o yaml`


## Principales commandes

- Arrêt de l'image -> `docker stop c1`
- Re-démarrage de l'image -> `docker start c1`
- Suppression des *container* actifs -> `docker ps -q | xargs docker rm -f`
- Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`
- Suppression de toutes les images -> `docker images -qa | xargs docker image rm -f`
- Liste des volumes -> `docker volume ls`

### Image NGINX
- Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`
- Le téléchargement & lancement d'une image via nommé -> `docker run --detach --name c1 --publish 8080:80 nginx:latest`
- Lancement d'une console d'acces au **container** -> `docker exec -ti c1 bash`
- Utilisation de l'IP configurée dans le *Vagrantfile* -> `curl 192.168.56.101:8080` ou `curl localhost:8080`

### Image nginx avec volume

#### Gestion des volumes

Liste des volumes -> `docker volume ls`

#### Méthode volume

- Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`
- Suppression de tous les volumes ->`docker volume ls | grep -v DRIVER | sed  's/^.*[ ]\+//g' | xargs docker volume rm`
- Création d'un volume -> `docker volume create mynginx`
- Lancement d'un *container* -> `docker run --detach --name c1 --publish 8080:80 --volume mynginx:/usr/share/mynginx/html/ nginx:latest`
- Point de montage -> `docker volume inspect mynginx`
- Lancement d'une console d'acces au *container* -> `docker exec -ti c1 bash`

#### Méthode type=volume

- Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`
- Suppression de tous les volumes ->`docker volume ls | grep -v DRIVER | sed  's/^.*[ ]\+//g' | xargs docker volume rm`
- Création d'un volume -> `docker volume create mynginx`
- Lancement du *container* -> `docker run --detach --name c1 --publish 8080:80 --mount type=volume,source=mynginx,destination=/usr/share/nginx/html/ nginx:latest`
- Point de montage -> `docker volume inspect mynginx`
- Lancement d'une console d'acces au *container* -> `docker exec -ti c1 bash`

#### Méthode type=bind

- Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`
- Créer les répertoires et fichier en mode **root** -> `rm -fr /mnt/data && mkdir -p /mnt/docker start c1data`
- Lancement du *container* -> `docker run --detach --name c1 --mount type=bind,source=/mnt/data,destination=/usr/share/nginx/html/ nginx:latest`
- Point de montage -> `docker inspect c1 | jq '.[]' | jq '.Mounts[]'`
- Lancement d'une console d'acces au *container* -> `docker exec -ti c1 bash`

### Image DEBIAN

- Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`
- Lancement du *container* -> `docker run --detach --name c1 debian:latest sleep infinity`
- Lancement d'une console d'acces au **container** -> `docker exec -ti c1 bash`
- *Commit* des éventuelles mise à jour réalisées dans le *container* -> `docker commit c1 debian:latest`


#### Dockerfile

C'est une alternative au *docker commit*.


### POSGRESQL

```
docker ps -qa | grep -v -e 82fd674cf194 -e 7184040bf066 | xargs docker rm -f;
docker volume ls | grep -v DRIVER | sed  's/^.*[ ]\+//g' | xargs docker volume rm;
docker images -qa | xargs docker image rm -f;
docker system prune -af

cd /home/gizaoui/git/github/Tools/Docker/postgres
docker ps -qa | grep -v -e 82fd674cf194 -e 7184040bf066 | xargs docker rm -f;
cat > Dockerfile <<EOF
FROM debian:latest
RUN apt-get update -y
RUN apt-get -y install tzdata locales gnupg lsb-release ca-certificates curl wget less vim mlocate tree apache2 libapache2-mod-php \
# php-{common,pgsql,xml,xmlrpc,curl,gd,imagick,cli,dev,imap,mbstring,opcache,soap,zip,intl}
RUN echo 'fr_FR.UTF-8 UTF-8' >> /etc/locale.gen && locale-gen
RUN echo "deb https://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list
RUN wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -
RUN apt-get update -y && apt-get clean
RUN apt-get install -y postgresql postgresql-client postgresql-contrib && \
su - postgres -c "sed -i \"s/#[ ]*listen_addresses.*/listen_addresses='*'/g\" /etc/postgresql/16/main/postgresql.conf" && \
su - postgres -c "sed -i \"s/127\.0\.0\.1\/32/0\.0\.0\.0\/0/g\" /etc/postgresql/16/main/pg_hba.conf" && \
su - postgres -c "sed -i \"s/1\/128/0\/0/g\" /etc/postgresql/16/main/pg_hba.conf"
RUN rm -f /var/www/html/* && cat > /var/www/html/index.php <<EOFPHP
<?php
  phpinfo();
?>
EOFPHP
# usermod -aG postgres gizaoui
RUN useradd -u 1000 -G postgres -m -d /home/gizaoui gizaoui -s /bin/bash && usermod -g postgres gizaoui
RUN updatedb 
RUN cat >> /root/.bashrc <<EOFBACHRC
export LS_OPTIONS='--color=auto'
eval "$(dircolors)"
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -l'
alias l='ls $LS_OPTIONS -lA'
alias rm='rm -i'
alias cp='cp -i'
alias mv='mv -i'
alias h='history'
EOFBACHRC
EXPOSE 80 5432
VOLUME /var/lib/postgresql/16/main
CMD service apache2 start > /dev/null 2>&1 && \
service postgresql start > /dev/null 2>&1 && \
su - postgres -c "psql -c \"ALTER USER postgres WITH PASSWORD 'postgres'\"" && \
su - postgres -c "psql -c 'CREATE EXTENSION IF NOT EXISTS \"pgcrypto\"'" && \
su - postgres -c "psql -c 'CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\"'" && \
sh -c "sleep infinity"
EOF
docker build -t debian-apache:v1.0 . 2>&1 | tee build.log
```
- Lancement du *container* -> `docker run --detach --name c1 --publish 8081:80 --publish 5432:5432 debian-apache:v1.0`
- Logs -> `docker logs c1`
- Point de montage -> `docker inspect c1 | jq '.[]' | jq '.Mounts[]'` ou `docker volume inspect c1`
- Lancement d'une console d'acces au *container* -> `docker exec -ti c1 bash`
- Sur le host  -> `psql -h localhost -p 5432 -U gizaoui -d gzi_db`
- Sur le host  -> `psql -h localhost -p 5432 -U postgres -d postgres`
- `docker start c1`
- `docker stop c1`

psql -c "SELECT 'SELECT 1';\gexec"

----------------------------------

## TODO :


```
cat > Dockerfile <<EOF
FROM debian:latest
RUN useradd -u 1000 -m -d /home/gizaoui gizaoui
EOF
```

Création de l'image -> `docker build -t myimage:v1.0 .`
Liste des images -> `docker images`
En mode root -> `mkdir /myvolume`
Lancement du *container* -> `docker run --detach --name c1 --volume /myvolume:/data myimage:v1.0 sleep infinity`
Lancement d'une console d'acces au *container* -> `docker exec -ti --user postgres c1 bash`



Point de montage en local :

```
user # docker inspect c1 | jq '.[]' | jq '.Mounts[]'
{
  "Type": "bind",
  "Source": "/myvolume",
  "Destination": "/data",
  "Mode": "",
  "RW": true,
  "Propagation": "rprivate"
}
```

Lancement d'une console d'acces au **container** -> `docker exec -ti c1 bash`

Création d'un fichier sur le partage du *container* :

```
root@eec6c803f9ee:~# touch /data/test.txt
root@eec6c803f9ee:~# ll /data/
-rw-r--r-- 1 root root 0 Mar 21 16:03 test.txt
```

Les droits du fichier sont en *root* du répertoire de partage de l'hôte :

```
root # ls -l /myvolume/
total 0
-rw-r--r-- 1 root root 0 21 mars  17:03 test.txt
```

Suppression de tous les *container* -> `docker ps -qa | xargs docker rm -f`

Lancement du *container* avec le *user* gizaoui -> `docker run --detach --name c1 --volume /myvolume:/data --user gizaoui myimage:v1.0 sleep infinity`

Changement de propriétaire de répertoire de partage de l'hôte
```
root # chown gizaoui: -R /myvolume
root # ls -dl /myvolume
drwxr-xr-x 2 gizaoui gizaoui 4096 21 mars  17:03 /myvolume
```

Lancement d'une console de **container** (--u gizaoui optionel) -> `docker exec -ti -u gizaoui c1 bash`

Création d'un second fichier sur le partage du *container* :

```
gizaoui@20e705ec0bba:/$ touch /data/test2.txt 
gizaoui@20e705ec0bba:/$ ls -l /data/
total 0
-rw-r--r-- 1 gizaoui gizaoui 0 Mar 21 16:17 test2.txt
```

Cette fois le propriétaire du fichier du partage de l'hôte n'est plus *root* :

```
user # ls -l /myvolume/
total 0
-rw-r--r-- 1 user user 0 21 mars  17:17 test2.txt
```

Suppression le l'image -> `docker image rm -f 5a9ffcef880d`


## RESEAU

*docker0* est le bridge par défaut (gateway: 172.17.0.1):

```
docker0: flags=4099<UP,BROADCAST,MULTICAST>  mtu 1500
        inet 172.17.0.1  netmask 255.255.0.0  broadcast 172.17.255.255
        ether 02:42:2b:6a:1e:6e  txqueuelen 0  (Ethernet)
```

Liste de réseau docker :

```
user # docker network ls
NETWORK ID     NAME      DRIVER    SCOPE
22a0a952ad1e   bridge    bridge    local
0d0a0cc141f3   host      host      local
47eb6c128ee0   none      null      local
```

Récupération d'info sur le *bridge* -> `docker inspect 22a0a952ad1e`

Configuration du *hostname* -> `docker run -ti --rm --name c1 debian:latest bash`
Packages de gestion réseau -> `apt update && apt install -y iputils-ping net-tools`

Affichage de la configuration réseau du *container* :

```
root@41ab6e63d5c6:/# ifconfig eth0
eth0: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 172.17.0.2  netmask 255.255.0.0  broadcast 172.17.255.255
        ether 02:42:ac:11:00:02  txqueuelen 0  (Ethernet)
```

*ping* de la gateway

```
root@41ab6e63d5c6:/# ping 172.17.0.1
PING 172.17.0.1 (172.17.0.1) 56(84) bytes of data.
64 bytes from 172.17.0.1: icmp_seq=1 ttl=64 time=0.090 ms
```

*ping* d'une adresse externe :

```
root@41ab6e63d5c6:/# ping google.com
PING google.com (216.58.214.174) 56(84) bytes of data.
64 bytes from mad01s26-in-f174.1e100.net (216.58.214.174): icmp_seq=1 ttl=115 time=347 ms
```

### Publication de port

Mapping un port de la machine hôte avec celle du *container*.


Lancement en mode *detach* et nommé c1 -> `docker run --detach --name c1 --publish 8080:80 nginx:latest`

Le mapping est défini sur toutes les interfaces du port 8080 sur le port 80:

```
user # docker ps
CONTAINER ID   IMAGE         ...   PORTS                                   NAMES
cfeeb55b8f4f   nginx:latest  ...   0.0.0.0:8080->80/tcp, :::8080->80/tcp   c1
```

Visualisation du contenu de la pase web
```
user # curl localhost:8080
<!DOCTYPE html>
<html>
<head>
<title>Welcome to nginx!</title> ...
```

Lancement en mode *detach* et nommé c1 -> `docker run --detach --name c1 -P nginx:latest`

Le mapping est défini sur toutes les interfaces d'un port aléatoire sur le port 80 :

```
user # docker ps
CONTAINER ID   IMAGE         ...   PORTS                                   NAMES
cfeeb55b8f4f   nginx:latest  ...   0.0.0.0:8080->80/tcp, :::32768->80/tcp   c1
```

### Exposition de port

Lancement en mode *detach* et nommé c1 -> `docker run --detach --name c1 --expose 8080 nginx:latest`

Accès au port 8080 sur le *container*.

```
user # docker ps
CONTAINER ID   IMAGE          ...     PORTS              NAMES
7e716cd3dc24   nginx:latest   ...     80/tcp, 8080/tcp   c1
```

## PERSONALISATION DU BRIDGE *docker0*

```
cat > /etc/docker/daemon.json <<EOF
{
  "bip": "10.10.0.1/24"
}
EOF
```

Redémarrage du service -> `systemctl restart docker`

Modification de la gateway

```
user # ifconfig docker0
docker0: flags=4099<UP,BROADCAST,MULTICAST>  mtu 1500
        inet 10.10.0.1  netmask 255.255.255.0  broadcast 10.10.0.255

```



Lancement d'un *container* -> `docker run --detach --name c1 debian:latest sleep infinity`
Lancement d'une console de **container** -> `docker exec -ti c1 bash`
Packages de gestion réseau -> `apt update && apt install -y iputils-ping net-tools`

IP du *container* en 10.10.0.x

```
root@1a0732f97e51:/# ifconfig eth0
eth0: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 10.10.0.2  netmask 255.255.255.0  broadcast 10.10.0.255
```


Création d'un réseau docker -> `docker network create toto`

Création d'un nouveau bridge

```
user # ifconfig
br-22bb0697267d: flags=4099<UP,BROADCAST,MULTICAST>  mtu 1500
        inet 172.17.0.1  netmask 255.255.0.0  broadcast 172.17.255.255
```

Visualisation du bridge -> `docker network create toto`


Visualisation du fichier de configuration d'un *brige* -> `docker network inspect bridge`

```
...
        "Options": {
            "com.docker.network.bridge.default_bridge": "true",
            "com.docker.network.bridge.enable_icc": "true",
            "com.docker.network.bridge.enable_ip_masquerade": "true",
            "com.docker.network.bridge.host_binding_ipv4": "0.0.0.0",
            "com.docker.network.bridge.name": "docker0",
            "com.docker.network.driver.mtu": "1500"
        }, ...
```

Visualisation du bridge nommé -> `docker network create -o com.docker.network.bridge.name="titi" titi`

Configuration du bridge *titi*

```
user # docker network inspect titi
[
    {
        "Name": "titi",
        ...
        "Options": {
            "com.docker.network.bridge.name": "titi"
        }, ...
```

On observe la même chôde au niveau de la configuration réseau

``` 
gizaoui@192.168.1.169:~ $ ifconfig titi
titi: flags=4099<UP,BROADCAST,MULTICAST>  mtu 1500
        inet 172.18.0.1  netmask 255.255.0.0  broadcast 172.18.255.255
```

Suppression des bridge
```
user # docker network ls
NETWORK ID     NAME      DRIVER    SCOPE
07d5b8e246dc   bridge    bridge    local
0d0a0cc141f3   host      host      local
47eb6c128ee0   none      null      local
935b81d45573   titi      bridge    local
22bb0697267d   toto      bridge    local

user # docker network rm 935b81d45573
935b81d45573

user # docker network rm 22bb0697267d
22bb0697267d
```

### Espace de noms réseau

Permet de cloisonner un ensemble applicatif relatif aux réseaux.

En root :

- Ajout d'un espace de nom -> `ip netns add mynet`
- Liste des espace de nom -> `ip netns ls`

On constate une interface loopback à *DOWN*

```
root# ip netns exec mynet  ip a
1: lo: <LOOPBACK> mtu 65536 qdisc noop state DOWN group default qlen 1000
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
```

Activation de l'interface de loopback ->`ip netns exec mynet  ip link set lo up`

On constate une interface loopback à *UP*

```
root # ip netns exec mynet  ip a
1: lo: <LOOPBACK,UP,LOWER_UP> mtu 65536 qdisc noqueue state UNKNOWN group default qlen 1000
    link/loopback 00:00:00:00:00:00 brd 00:00:00:00:00:00
    inet 127.0.0.1/8 scope host lo
       valid_lft forever preferred_lft forever
    inet6 ::1/128 scope host 
       valid_lft forever preferred_lft forever
```

Lancement d'un serveur http en python -> `ip netns exec mynet python3 -m http.server 8080`

Interogation du serveur dans le namespace :
```
root # ip netns exec mynet curl localhost:8080
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Directory listing for /</title> ...
```

# CHROOT / JAIL

- *schroot* : exécution de commandes dans un environnement fermé d'exécution
- *debootstrap* : Installation d'un système Debian de base

Recherche du package *debootstrap* -> `apt-cache search schroot`
Recherche du package *debootstrap* -> `apt-cache search debootstrap`


-> `mkdir -p /SECURITY/JAIL`



# 11. CREATION D'UN BRIDGE /!\ DOCKER NE DOIT PAS ETRE INSTALLE 

## Création des namespaces
ip netns add x1
ip netns add x2

## Création des vethernet (cables) & interfaces
ip link add xeth1 type veth peer name xpeer1
ip link add xeth2 type veth peer name xpeer2

## Ajout des interfaces au namespaces
ip link set xeth1 netns x1
ip link set xeth2 netns x2

## Activation des vethernet
ip link set xeth1 up
ip link set xeth2 up

### Liste des IP dans chacun des namespaces
ip --netns x1 a
ip --netns x2 a

## Activation des interfaces dans les namespaces
ip netns exec x1  ip link set lo up
ip netns exec x2  ip link set lo up
ip netns exec x1  ip link set xpeer1 up
ip netns exec x2  ip link set xpeer2 up

## Ajout des ip pour chaque interface
ip netns exec x1  ip addr add 10.11.0.10/16 dev xeth1
ip netns exec x1  ip addr add 10.11.0.20/16 dev xeth2

## Création et activation du bridge
ip link add xavki0 type bridge
ip link set xavki0 up

## Ajout des vethernet au bridge
ip link set xeth1 master xavki0
ip link set xeth2 master xavki0

## Ajout de l'ip du bridge
ip addr add 10.11.0.1/16 dev xavki0

## Ajout des routes à l'intérieur des namespace pour passer par le bridge
ip netns exec x1  ip route add default via 10.11.0.1
ip netns exec x2  ip route add default via 10.11.0.1

## Accès externe
https://www.malekal.com/utiliser-masquerade-iptables-nat/
echo 1 > /proc/sys/net/ipv4/ip_forward
iptables -t nat -A POSTROUTING -s 10.11.0.1/16 ! -o xavki0 -j MASQUERADE


# 12. DOCKER COMMIT

Lancement du *container* -> `docker run --detach --name c1 debian:latest sleep infinity`
Lancement d'une console de **container** -> `docker exec -ti c1 bash`
Packages de gestion réseau -> `apt update && apt install -y vim`
Enrigitrer l'image -> `docker commit c1 myimage:v1.0.0`

Lancement du *container* "commiter" -> `docker run --detach --name c2 myimage:v1.0.0`

Le "sleep infinity" a également été commité : 

```
gizaoui@192.168.1.169:~ $ docker ps
CONTAINER ID   IMAGE            COMMAND            CREATED          STATUS          PORTS     NAMES
49391afc66f0   myimage:v1.0.0   "sleep infinity"   19 seconds ago   Up 17 seconds             c2
88aa32843621   debian:latest    "sleep infinity"   11 minutes ago   Up 11 minutes             c1
```


Liste des images :

```
gizaoui@192.168.1.169:~ $ docker images
REPOSITORY   TAG       IMAGE ID       CREATED          SIZE
myimage      v1.0.0    e4f7d3c8197b   12 minutes ago   178MB
nginx        latest    e4720093a3c1   5 weeks ago      187MB
debian       latest    52f537fe0336   5 weeks ago      117MB
```

Suppression de l'image -> `docker image rm -f e4f7d3c8197b`


# 13. DOCKERFILE

```
cat > app.py <<EOF
FROM python:slim-bullseye
from flask import Flask
app = Flask(__name__)
@app.route('/')
def home():
    return "Hello Xavki !!"
EOF
```

```
cat > requirements.txt <<EOF
Flask==2.2.3
EOF
```

```
cat > Dockerfile <<EOF
FROM python:slim-bullseye
LABEL version=v1.0.0
LABEL app=xavki-app
ENV FLASK_APP=app.py
ENV FLASK_ENV=dev
# Equivalent à "cd /app"
WORKDIR /app
# Copy des fichiers situés au même niveau que le Dockerfile dans /app
COPY . .
RUN pip3 install -r requirements.txt
COPY index.html .
# Le servuer Flask écoute sur le port 5000
EXPOSE 5000
# Lancement du serveur Flask
CMD ["flask", "run", "--host=0.0.0.0"]
EOF
```

Création de l'image -> `docker build -t myflaskimg:v1.0 .`
Liste des images -> `docker images`

Lancement du *container* -> `docker run --detach --name c1 myflaskimg:v1.0`
Affichage des logs -> `docker logs c1`
Requête http -> `curl http://10.10.0.2:5000`

Lancement d'une console de **container** -> `docker exec -ti c1 bash`


# 14. LAYER

Un LAYER correspond aux intructions créées dans un build.
Optimisation dans la construction des images afin de réduire la taille.

Mauvaise pratique :

```
cat > Dockerfile1 <<EOF
FROM docker.io/debian:bullseye-slim
# "RUN" sur plusieurs lignes
RUN apt update -qq 
RUN apt install -qq -y wget 
RUN apt clean 
RUN rm -rf /var/lib/apt/lists/*
# Téléchargement d'un fichier de 10Mo
RUN wget http://xcal1.vodafone.co.uk/10MB.zip
# Suppression du fichier de 10 Mo
RUN rm -f 10MB.zip
EOF
```

Création de l'image -> `docker build -t myimage:v1.0.1 -f Dokerfile1 .`
Taille de l'image -> `docker image ls`

Bonne pratique :

```
cat > Dockerfile2 <<EOF
FROM docker.io/debian:bullseye-slim
# -qq No output except for errors
RUN apt update -qq && apt install -qq -y wget && apt clean && rm -rf /var/lib/apt/lists/*
RUN wget http://xcal1.vodafone.co.uk/10MB.zip && rm -f 10MB.zip
EOF
```

Création de l'image -> `docker build -t myimage:v1.0.1 -f Dokerfile2 .`
Taille de l'image -> `docker image ls`



```
# et pour les secrets...
FROM docker.io/debian:bullseye-slim
RUN echo "monsecret" > xavki.txt
RUN rm xavki.txt
EOF
```

On visualise le mot de passe -> `docker history myimage:v1.0.1`





