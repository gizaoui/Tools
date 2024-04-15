# KUBERNETES


## INSTALL

### Virtual box

Noyau Linux -> `apt install linux-headers-$(uname -r)`
Intallation des dépendances -> `apt install libqt5help5 libqt5opengl5`
Installation de *Virtualbox* -> `dpkg -i virtualbox-7.0_7.0.14-161095~Debian~bookworm_amd64.deb`
Si l'installation des dépendances ne fonctionne pas -> `apt --fix-broken install`
Lancer la configuration -> `/sbin/vboxconfig`
Installer le plugin -> `/usr/bin/VBoxManage extpack install ./Oracle_VM_VirtualBox_Extension_Pack-7.0.14.vbox-extpack`



#### Vagrand

Permet la création d'image *Virtual box*

Installation de l'outil -> `apt install vagrant-libvirt libvirt-daemon-system`
Autorisation -> `usermod --append --groups libvirt gizaoui`

Importation de l'image -> `vagrant box add --insecure debian/bookworm64`
Lste des images téléchargées -> `vagrant box list`
Suppression de l'image téléchargée -> `vagrant box remove debian/bookworm64`
Création du fichier *Vagrantfile* -> `vagrant init debian/bookworm64`

-> `cd ~/git/github/Tools/Kubernetes/debian`
Création & configuration de l'environnement -> `vagrant up`
Liste l'état des environnement -> `vagrant global-status --prune`
Suppression d'un environnement -> `vagrant destroy -f kubmaster`
Acces *ssh* -> `vagrant ssh kubmaster`

`vagrant halt -f kubmaster`
`vagrant reload kubmaster`
`vagrant suspend` 


https://oracle.github.io/vagrant-projects/boxes/oraclelinux/9-btrfs.json



kubeadm init --apiserver-advertise-address=192.168.56.101 --node-name $HOSTNAME --pod-network-cidr=10.244.0.0/16

Range d'IP a attribuer au sein de son réseau : 10.244.0.0/16

Configuration des POD
Sur *kubmaster* & *kubnode* -> `sysctl net.bridge.bridge-nf-call-iptables=1`

Installation réseau -> `kubectl apply -f https://raw.githubusercontent.com/coreos/flannel/2140ac876ef134e0ed5af15c65e414cf26827915/Documentation/kube-flannel.yml`

`kubectl get pods --all-namespace`
`kubectl get nodes`


