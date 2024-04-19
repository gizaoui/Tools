# NIGINX-PHP

## 1. Construction de l'image

### 1.1 Mise à jour des fichiers *phppgadmin*

#### 1.1.1 config.inc.php

Mettre à jour le fichier *config.inc.php*

```php
$conf['servers'][0]['host'] = 'postgres-service';
$conf['servers'][0]['port'] = 5432;
$conf['extra_login_security'] = false;
```

#### 1.1.2 decorator.inc.php

Corriger les **éventuels** bugs en ajoutant les tags *AllowDynamicProperties* au fichier *decorator.inc.php* 

```php
#[AllowDynamicProperties]
class FieldDecorator extends Decorator { ...

#[AllowDynamicProperties]
class UrlDecorator extends Decorator { ...
```

#### 1.1.3 adodb.inc.php

Corriger les **éventuels** bugs en ajoutant les tags *ReturnTypeWillChange* au fichier  *adodb.inc.php*
```php
class ADODB_Iterator implements Iterator {...
	// #[\ReturnTypeWillChange] 
    function rewind() { ...
	// #[\ReturnTypeWillChange] 
	function valid() { ...
	// #[\ReturnTypeWillChange] 
    function key() { ...
	// #[\ReturnTypeWillChange] 
    function current() { ...
	// #[\ReturnTypeWillChange] 
    function next() { ...

class ADORecordSet_empty implements IteratorAggregate { ...
	// #[\ReturnTypeWillChange] 
	function getIterator() { ...
	// #[\ReturnTypeWillChange] 
    function rewind() { ...
	// #[\ReturnTypeWillChange] 
	function valid() { ...
	// #[\ReturnTypeWillChange] 
    function key() { ...
	// #[\ReturnTypeWillChange] 
    function current() { ...
	// #[\ReturnTypeWillChange] 
    function next() { ...

class ADORecordSet implements IteratorAggregate { ...
    // #[\ReturnTypeWillChange] 
    function getIterator() 
```

### 1.2 Contruction de l'image Dockerfile

- Se positionner -> `cd ~/git/github/Tools/Kubernetes/nginx`
- Suppression de l'image -> `minikube image rm php-fpm`
- Build docker -> `eval $(minikube -p minikube docker-env) && docker build -t php-fpm . 2>&1 | tee build.log`
- Build -> `minikube image build -t php-fpm .`
- Liste des images -> `minikube image ls --format table`
- Suppression des images *none* `minikube image ls --format table | grep docker.io\/library\/\<none\> | cut -d"|" -f4 | xargs minikube image rm`



### KUBECTL


- Se positionner -> `cd ~/git/github/Tools/Kubernetes/nginx`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml`
- Log -> `kubectl logs deployment/phpfpm-nginx-deploy`
- Liste service -> `kubectl get deployment`
- Liste service -> `kubectl get service`
- Liste configmap -> `kubectl get configmap`
- Liste volume -> `kubectl get PersistentVolume`
- Liste volume claim -> `kubectl get PersistentVolumeClaim`
- INTERNAL-IP - EXTERNAL-IP -> `kubectl get nodes -o wide`
- Test [Ingeress](https://blog.knoldus.com/how-to-create-ingress-rules-in-kubernetes-using-minikube/#what-is-ingress) (simule la màj de */etc/hosts* ) -> `curl http://hello-worldapp.com/ --resolve hello-worldapp.com:80:192.168.49.2`
- Test -> `curl $(minikube service phpfpm-nginx-service --url)`

- Supression


```bash
kubectl delete deployment phpfpm-nginx-deploy
kubectl delete service phpfpm-nginx-service
kubectl delete configmap phpfpm-nginx-configmap
eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml
```


Console de l'image -> `kubectl exec -it $(kubectl get pods | grep phpfpm-nginx-deploy | cut -d" " -f1) -- /bin/bash`

- Accès direct http://localhost:9090 -> `kubectl port-forward $(kubectl get pods | grep phpfpm-nginx-deploy | cut -d" " -f1) 9090:80`

- http://192.168.49.2:30091
- http://192.168.49.2:30091/index.php
- http://192.168.49.2:30091/phpinfo.php
- http://192.168.49.2:30091/testdb.php
- http://192.168.49.2:30091/phppgadmin

