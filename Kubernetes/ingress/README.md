# INGRESS


### 4.1. NGINX

https://gitlab.com/xavki/presentations-kubernetes/-/blob/master/54-hello-world/slides.md?ref_type=heads


- Se positionner -> `cd ~/git/github/Tools/Kubernetes/ingress`
- Déploiement -> `eval $(minikube -p minikube docker-env) && kubectl create -f ./k8s.yaml`
- Log -> `kubectl logs deployment/nginx-deploy`
- Liste pods -> `kubectl get pods` (`kubectl exec -it nginx-deploy-xxxxxxx -- bin/bash`)
- Liste service -> `kubectl get service`
- Liste ingress -> `kubectl get ingress`
- INTERNAL-IP - EXTERNAL-IP -> `kubectl get nodes -o wide`
- Param. de contruction de la requête -> `kubectl describe ingress nginx-ingress`
- Test [Ingeress](https://blog.knoldus.com/how-to-create-ingress-rules-in-kubernetes-using-minikube/#what-is-ingress) (simule la màj de */etc/hosts* ) -> `curl http://hello-worldapp.com/ --resolve hello-worldapp.com:80:192.168.49.2`

- Supression

```bash
kubectl delete deployment nginx-deploy
kubectl delete service nginx-service
kubectl delete ingress nginx-ingress
```

