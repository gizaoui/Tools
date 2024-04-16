# INGRESS


### 4.1. NGINX

https://gitlab.com/xavki/presentations-kubernetes/-/blob/master/54-hello-world/slides.md?ref_type=heads


- Se positionner -> `cd ~/git/github/Tools/Kubernetes/persistance-volume`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml`
- Log -> `kubectl logs deployment/postgres-deploy`
- Liste pods -> `kubectl get pods` (`kubectl exec -it nginx-deploy-xxxxxxx -- bin/bash`)
- Liste service -> `kubectl get service`
- Liste configmap -> `kubectl get configmap`
- Mot de passe -> `kubectl get Secret`
- Liste volume -> `kubectl get PersistentVolume`
- Liste volume claim -> `kubectl get PersistentVolumeClaim`
- INTERNAL-IP - EXTERNAL-IP -> `kubectl get nodes -o wide`
- Param. de contruction de la requête -> `kubectl describe ingress nginx-ingress`
- Test [Ingeress](https://blog.knoldus.com/how-to-create-ingress-rules-in-kubernetes-using-minikube/#what-is-ingress) (simule la màj de */etc/hosts* ) -> `curl http://hello-worldapp.com/ --resolve hello-worldapp.com:80:192.168.49.2`

- Supression

```bash
kubectl delete deployment postgres-deploy
kubectl delete service postgres-service
kubectl delete configmap postgres-configmap
kubectl delete Secret postgres-secret
kubectl patch pvc postgres-pv-claim -p '{"metadata":{"finalizers":null}}'
kubectl delete PersistentVolumeClaim postgres-pv-claim
kubectl delete PersistentVolume postgres-pv-volume
eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml
```


Console de l'image -> `kubectl exec -it $(kubectl get pods | grep postgres- | cut -d" " -f1) -- /bin/bash`

Client postgres (mypassword) -> `kubectl exec -it $(kubectl get pods | grep postgres- | cut -d" " -f1) -- psql -h localhost -U root -p 5432 --password mydb`

- Autorisation accès extérieur (exécuter une fois les service démarrés)

```bash
kubectl exec $(kubectl get pods | grep postgres- | cut -d" " -f1) -- bash -c 'su - postgres -c "sed -i \"s/127\.0\.0\.1\/32/0\.0\.0\.0\/0/g\" /var/lib/postgresql/data/pg_hba.conf" && su - postgres -c "sed -i \"s/127\.0\.0\.1\/32/0\.0\.0\.0\/0/g\" /var/lib/postgresql/data/pg_hba.conf" && su - postgres -c "sed -i \"s/1\/128/0\/0/g\" /var/lib/postgresql/data/pg_hba.conf" && /etc/init.d/postgresql reload'
```

Accès à la base de données de l'extérieur du container (mypassword)  -> `psql -h 192.168.49.2 -p 30432 -U root -d mydb`