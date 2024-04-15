# MULTI-CONTAINER


## 1. Construction de l'image

L'application python et à la base de données *Redis* sont situées sur le même *pod* (réseau)

```python
redis=Redis(host="0.0.0.0", db=0, socket_connect_timeout=2, socket_timeout=2)
```

- Se positionner -> `~/git/github/Tools/Kubernetes/python/multi-container`
- Suppression de l'image -> `minikube image rm myflask-multi-cont`
- Build -> `minikube image build -t myflask-multi-cont .`
- Build docker -> `eval $(minikube -p minikube docker-env) && docker build -t myflask-multi-cont . 2>&1 | tee build.log`
- Liste des images -> `minikube image ls --format table`
- Suppression des images *none* `minikube image ls --format table | grep docker.io\/library\/\<none\> | cut -d"|" -f4 | xargs minikube image rm`

### 4.2. Flask + Redis

- Se positionner -> `~/git/github/Tools/Kubernetes/python/multi-container`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s_myflask_multi_container.yaml`
- Running -> `kubectl get pods`
- Liste déploiement -> `kubectl get deployments`
- Liste service -> `kubectl get services`
- Log -> `kubectl logs deployment/multi-container-project` 
- Accès à l'url http://192.168.49.2:30008 -> `minikube service multi-container-service --url` 
- Test -> `curl $(minikube service multi-container-service --url)`

- Méthode en ligne de commande -> `kubectl run myflask-multi-container-project --image=myflask:latest --image-pull-policy=Never`



  
## 5. DIVERS

- -> `docker image save -o myflask.tar myflask:latest`
- -> `minikube image load myflask.tar`
- -> `minikube image list`

kubectl get nodes -o wide
- [Service](https://kubernetes.io/docs/concepts/services-networking/service/)
    -[Node port](https://kubernetes.io/docs/concepts/services-networking/service/#type-nodeport)

- `docker login`
- `kubectl create secret generic regcred --from-file=.dockerconfigjson=/home/gizaoui/.docker/config.json --type=kubernetes.io/dockerconfigjson`
- `kubectl create secret docker-registry reggizaoui --docker-server=https://hub.docker.com --docker-username=gizaoui --docker-password=xxxxx --docker-email=gizaoui@gmail.com`
- `kubectl get secret reggizaoui --output=yaml`

- `minikube profile list`

