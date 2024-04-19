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
kubectl delete configmap postgres-configmap
kubectl delete Secret postgres-secret
kubectl patch pvc postgres-pv-claim -p '{"metadata":{"finalizers":null}}'
kubectl delete PersistentVolumeClaim postgres-pv-claim
kubectl delete PersistentVolume postgres-pv-volume
eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml
```


Console de l'image -> `kubectl exec -it $(kubectl get pods | grep postgres- | cut -d" " -f1) -- /bin/bash`


```bash
su - postgres -c "psql -c 'DROP DATABASE mydb'" && \
su - postgres -c "psql -c \"CREATE USER gizaoui WITH ENCRYPTED PASSWORD 'gizaoui' NOSUPERUSER CREATEDB CREATEROLE INHERIT LOGIN\"" && \
su - postgres -c "mkdir -p /var/lib/postgresql/tblspc/gizaoui_ts" && \
su - postgres -c "psql -c \"CREATE TABLESPACE gizaoui_ts OWNER gizaoui LOCATION '/var/lib/postgresql/tblspc/gizaoui_ts'\"" && \
su - postgres -c "psql -c \"CREATE DATABASE gizaoui WITH ENCODING = 'UTF8' OWNER = gizaoui TABLESPACE = gizaoui_ts CONNECTION LIMIT = -1\"" && \
su - postgres -c "psql -c \"ALTER USER gizaoui WITH PASSWORD 'gizaoui'\""
su - postgres -c "psql -c 'CREATE EXTENSION IF NOT EXISTS \"pgcrypto\"'" && \
su - postgres -c "psql -c 'CREATE EXTENSION IF NOT EXISTS  \"plpgsql\"'" && \
su - postgres -c "psql -c 'CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\"'" && \
su - postgres -c "cp /var/lib/postgresql/data/pg_hba.conf /var/lib/postgresql/data/pg_hba.conf.bak" && \
su - postgres -c "cp /var/lib/postgresql/data/postgresql.conf /var/lib/postgresql/data/postgresql.conf.bak" && \
su - postgres -c "sed -i 's/trust/scram-sha-256/g' /var/lib/postgresql/data/pg_hba.conf" && \
su - postgres -c "sed -i 's/trust/scram-sha-256/g' /var/lib/postgresql/data/pg_hba.conf" && \
su - postgres -c "sed -i 's/#[ ]*password_encryption.*/password_encryption = scram-sha-256/g' /var/lib/postgresql/data/postgresql.conf" && \
su - postgres -c "/usr/lib/postgresql/14/bin/pg_ctl -D /var/lib/postgresql/data/ reload"
```

```bash
su - postgres -c "psql -c 'SELECT usename FROM pg_catalog.pg_user'"
su - postgres -c "psql -c 'SELECT datname FROM pg_database WHERE datistemplate=false'"
su - postgres -c "psql -c 'show data_directory'"
su - postgres -c "psql -c 'select * from pg_extension'"
```

Accès via *container* à la bdd -> `kubectl exec -it $(kubectl get pods | grep postgres- | cut -d" " -f1) -- psql -h localhost -U postgres`
Accès direct à la bdd -> `psql -h 192.168.49.2 -p 30432 -U postgres -d postgres`
Accès direct à la bdd -> `psql -h 192.168.49.2 -p 30432 -U gizaoui -d gizaoui`

CREATE TABLE IF NOT EXISTS t1 ( c1 INT, c2 VARCHAR(10) );