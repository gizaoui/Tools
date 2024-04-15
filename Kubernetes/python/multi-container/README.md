# MULTI-CONTAINER

https://kubernetes.io/fr/docs/reference/kubectl/cheatsheet/

## 1. Construction de l'image

L'application python et à la base de données *Redis* sont situées sur le même *pod* (réseau)

```python
redis=Redis(host="0.0.0.0", db=0, socket_connect_timeout=2, socket_timeout=2)
```

- Se positionner -> `cd ~/git/github/Tools/Kubernetes/python/multi-container`
- Suppression de l'image -> `minikube image rm myflask-multi-cont`
- Build -> `minikube image build -t myflask-multi-cont .`
- Build docker -> `eval $(minikube -p minikube docker-env) && docker build -t myflask-multi-cont . 2>&1 | tee build.log`
- Liste des images -> `minikube image ls --format table`
- Suppression des images *none* `minikube image ls --format table | grep docker.io\/library\/\<none\> | cut -d"|" -f4 | xargs minikube image rm`

### 4.2. Flask + Redis

- Se positionner -> `cd ~/git/github/Tools/Kubernetes/python/multi-container`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml`
- Running -> `kubectl get pods`
- Liste déploiement -> `kubectl get deployments`
- Liste service -> `kubectl get services`
- Log -> `kubectl logs deployment/multi-container-project` 
- Accès à l'url http://192.168.49.2:30008 -> `minikube service multi-container-service --url` 
- Test -> `curl $(minikube service multi-container-service --url)`


- Delete all

```bash
kubectl delete deployment multi-container-deploy
kubectl delete service multi-container-service
```


