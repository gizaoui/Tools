# FLASK


## 1. Construction de l'image

- Remove *containers* (except *minicube*) -> `docker ps -qa | xargs docker rm -f`
- Remove *images* (except *minicube*) -> `docker images -qa | xargs docker image rm -f`
- Build -> `docker build -t myflask . 2>&1 | tee build.log`

## 2. Démarrage sans Redis (Dockerfile)

- Run -> `docker run --name c1 --publish 5000:5000 myflask:latest`
- Logs -> `docker logs c1`
- Start containers* -> `docker start c1`

## 3. Démarrage avec Redis (docker-compose)

- Run -> `docker-compose up`


## 4. MINICUBE

- Start/Stop *minikube* -> `minikube start` / `minikube stop`
- Interface administrateur -> `minikube dashboard`
- -> `minikube addons enable metrics-server`
- -> `kubectl get po -A`


### 4.1. NGINX

- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s_nginx.yaml`
- Log -> `kubectl logs deployment/nginx-project` ou `kubectl logs -f deployment/nginx-project`
- Liste service -> `kubectl get deployments`
- Liste ingress -> `kubectl get ingress`
- INTERNAL-IP - EXTERNAL-IP -> `kubectl get nodes -o wide`
- Test -> `curl $(minikube service nginx-project --url)`
- Test (simule la màj de */etc/hosts* ) -> `curl http://hello-worldapp.com/ --resolve hello-worldapp.com:80:192.168.49.2`
- Suppression déploiements -> `kubectl delete deployment nginx-project`
- Suppression services -> `kubectl delete service nginx-project`
- Suppression ingress -> `kubectl delete ingress nginx-project`



### 4.2. Flask + Redis (multi-pods)

- Déploiement -> `eval $(minikube -p minikube docker-env) && kkubectl get ingressubectl create -f ./k8s_myflask.yaml`
- Log -> `kubectl logs deployment/myflask-project` ou `kubectl logs -f deployment/myflask-project`
- Liste déploiement -> `kubectl get deployments`
- Liste service -> `kubectl get services`
- Accès à l'url http://192.168.49.2:30008 -> `minikube service myflask-project --url` 
- Test -> `curl $(minikube service myflask-project --url)`
- Suppression déploiements -> `kubectl delete deployment myflask-project redis-project`
- Suppression services -> `kubectl delete service myflask-project redkubectl get nodes -o wideis-project`

- Méthode en ligne de commande -> `kubectl run myflask --image=myflask:latest --image-pull-policy=Never`


### 4.3. Flask + Redis (multi-container)

  
## 5. DIVERS

- -> `docker image save -o myflask.tar myflask:latest`
- -> `minikube image load myflask.tar`
- -> `minikube image list`


- [Service](https://kubernetes.io/docs/concepts/services-networking/service/)
    -[Node port](https://kubernetes.io/docs/concepts/services-networking/service/#type-nodeport)

- `docker login`
- `kubectl create secret generic regcred --from-file=.dockerconfigjson=/home/gizaoui/.docker/config.json --type=kubernetes.io/dockerconfigjson`
- `kubectl create secret docker-registry reggizaoui --docker-server=https://hub.docker.com --docker-username=gizaoui --docker-password=xxxxx --docker-email=gizaoui@gmail.com`
- `kubectl get secret reggizaoui --output=yaml`

