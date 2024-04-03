# FLASK


## Construction de l'image
- Remove *containers* (except *minicube*) -> `docker ps -qa | xargs docker rm -f`
- Remove *images* (except *minicube*) -> `docker images -qa | xargs docker image rm -f`
- Build -> `docker build -t myflask . 2>&1 | tee build.log`

## Démarrage sans Redis (Dockerfile)

- Run -> `docker run --name c1 --publish 5000:5000 myflask:latest`
- Logs -> `docker logs c1`
- Start containers* -> `docker start c1`

## Démarrage avec Redis (docker-compose)

- Run -> `docker-compose up`



## MINICUBE

- Start/Stop *minikube* -> `minikube start` / `minikube stop`
- Interface administrateur -> `minikube dashboard`
- -> `minikube addons enable metrics-server`
- -> `kubectl get po -A`


- -> `kubectl get pods`
- -> `kubectl describe pods`
- -> `kubectl create -f ./k8s_redis.yaml`
- -> `kubectl delete pod redis`
- -> `kubectl create -f ./k8s_myflask.yaml`
- -> `kubectl delete pod myflask`

- -> `kubectl create -f ./k8s_nginx.yaml`
- -> `kubectl logs deployment/nginx-project` ou `kubectl logs -f deployment/nginx-project`
- -> `kubectl get deployments`
- -> `kubectl delete deployment nginx-project`
- -> `minikube service nginx-project --url`



https://kubernetes.io/docs/concepts/services-networking/service/
https://kubernetes.io/docs/concepts/services-networking/service/#type-nodeport
