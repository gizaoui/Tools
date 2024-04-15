# MULTI-PODS

https://kubernetes.io/fr/docs/reference/kubectl/cheatsheet/


## 1. Construction de l'image

L'application python et à la base de données *Redis* sont situées sur le même *pod* (réseau)

```python
redis=Redis(host="redis-project", db=0, socket_connect_timeout=2, socket_timeout=2)
```

- Se positionner -> `cd ~/git/github/Tools/Kubernetes/multi-pod`
- Suppression de l'image -> `minikube image rm myflask-multi-pod`
- Build -> `minikube image build -t myflask-multi-pod .`
- Liste des images -> `minikube image ls --format table`
- Suppression des images *none* `minikube image ls --format table | grep docker.io\/library\/\<none\> | cut -d"|" -f4 | xargs minikube image rm`


### 2. Flask + Redis (multi-pods)

- Se positionner -> `cd ~/git/github/Tools/Kubernetes/multi-pod`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml`
- Log -> `kubectl logs deployment/myflask-project`
- Liste déploiement -> `kubectl get deployments`
- Liste service -> `kubectl get services`
- Test -> `curl $(minikube service myflask-multi-pod-service --url)`


- Delete all

```bash
kubectl delete deployment myflask-multi-pod-deploy redis-deploy
kubectl delete service myflask-multi-pod-service redis-service
```
