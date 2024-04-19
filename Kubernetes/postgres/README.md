# POSTGRES

- Se positionner -> `cd ~/git/github/Tools/Kubernetes/postgres`
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
kubectl delete Secret postgres-secret
kubectl delete configmap postgres-configmap
eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml
```

Script de config a exécuter post-déploiement -> `kubectl exec $(kubectl get pods | grep postgres- | cut -d" " -f1) -- sh -c "/tmp/cpyconfig.sh"`


Console de l'image -> `kubectl exec -it $(kubectl get pods | grep postgres- | cut -d" " -f1) -- /bin/bash`

```bash
su - postgres -c "psql -c 'SELECT usename FROM pg_catalog.pg_user'"
su - postgres -c "psql -c 'SELECT datname FROM pg_database WHERE datistemplate=false'"
su - postgres -c "psql -c 'show data_directory'"
su - postgres -c "psql -c 'select * from pg_extension'"
```

Accès via *container* à la bdd -> `kubectl exec -it $(kubectl get pods | grep postgres- | cut -d" " -f1) -- psql -h localhost -U postgres`
Accès depuis le host à la bdd -> `psql -h 192.168.49.2 -p 30432 -U postgres -d postgres`
Accès depuis le host à la bdd -> `psql -h 192.168.49.2 -p 30432 -U gizaoui -d gizaoui`

Test

```sql
CREATE TABLE IF NOT EXISTS t1 ( c1 INT, c2 VARCHAR(10) );
```