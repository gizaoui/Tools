# NIGINX-PHP

## 1. Construction de l'image


- Se positionner -> `cd ~/git/github/Tools/Kubernetes/ngnix`
- Suppression de l'image -> `minikube image rm php-fpm`
- Build docker -> `eval $(minikube -p minikube docker-env) && docker build -t php-fpm . 2>&1 | tee build.log`
- Build -> `minikube image build -t php-fpm .`
- Liste des images -> `minikube image ls --format table`
- Suppression des images *none* `minikube image ls --format table | grep docker.io\/library\/\<none\> | cut -d"|" -f4 | xargs minikube image rm`


### KUBECTL


- Se positionner -> `cd ~/git/github/Tools/Kubernetes/ngnix`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml`
- Log -> `kubectl logs deployment/phpfpm-nginx-deploy`
- Liste service -> `kubectl get service`
- Liste configmap -> `kubectl get configmap`
- INTERNAL-IP - EXTERNAL-IP -> `kubectl get nodes -o wide`
- Param. de contruction de la requête -> `kubectl describe ingress nginx-ingress`
- Test [Ingeress](https://blog.knoldus.com/how-to-create-ingress-rules-in-kubernetes-using-minikube/#what-is-ingress) (simule la màj de */etc/hosts* ) -> `curl http://hello-worldapp.com/ --resolve hello-worldapp.com:80:192.168.49.2`
- Test -> `curl $(minikube service phpfpm-nginx-service --url)`

- Supression

```bash
kubectl delete deployment phpfpm-nginx-deploy
kubectl delete service phpfpm-nginx-service
kubectl delete configmap nginx-config
eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml
```

Console de l'image -> `kubectl exec -it $(kubectl get pods | grep phpfpm-nginx-deploy- | cut -d" " -f1) -- /bin/bash`


http://localhost:9090 -> `kubectl port-forward $(kubectl get pods | grep phpfpm-nginx-deploy- | cut -d" " -f1) 9090:80`


http://192.168.49.2:30090/phpinfo.php
