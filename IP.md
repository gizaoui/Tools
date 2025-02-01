## MASQUE SOUS RESEAU

## Adresse IP d'un device au format CIDR (Classless Inter-Domain Routing):
On associe l'IP d'une machine à un masque sous-réseau : 172.128.10.5/18

## Nombre d'IP disponibles (partie basse sous réseau)
La partie basse correspond au complément du masque sous réseau : 32-18 = 14 bits.
00000000.00000000.00111111.11111111 &nbsp;&#8640;&nbsp; 0.0.2⁶.2⁸ &nbsp;&#8640;&nbsp; 0.0.63.255
Nombre d'IP disponibles : (64 x 256) - 2 = 16382

## Adresse IP du réseau :
172.128.10.5 &nbsp;&#8640;&nbsp; `echo "obase=2;172;128;10;5" | bc | tr '\n' '.'` &nbsp;&#8640;&nbsp; 10101100.10000000.00001010.00000101
La partie basse du masque sous réseau renvoit un CIDR à 18 &nbsp;&#8640;&nbsp; On note 172.128.10.5/18

## Adresses du réseau :

*Adresse IP **&** Masque de sous réseau*

| Libellé | Opération | IP décimal | IP Binaire | Remarque |
|:-:|:-:|:-:|:-:|:-:|
| Adresse IP | | 172.128.10.5 | 10101100.10000000.00000000.00000101 | `echo "obase=2;172;128;10;5" \| bc \| tr '\n' '.'` |
| Masque sous-réseau | & | 2⁸-1.2⁸-1.2⁷+2⁶.0 <br> 255.255.192.0| 11111111.11111111.11000000.00000000 | 18 premiers bits |
| Adresses du réseau | = | 172.128.0.0 | 10101100.10000000.00000000.00000000 | echo $(echo 'obase=10;ibase=2;10101100'|bc)\.$(echo 'obase=10;ibase=2;10000000'|bc)\.$(echo '0')\.$(echo '0') |
                                                                          
## Adresse de broadcast

*Adresses du réseau **|** Partie basse sous réseau*

| Libellé | Opération | IP décimal | IP Binaire | Remarque |
|:-:|:-:|:-:|:-:|:-:|
| Adresses du réseau | | 172.128.0.0 | 10101100.10000000.00000000.00000000 | |
| Partie basse sous réseau | \| |  | 00000000.00000000.00111111.11111111 | Les 14 derniers bits (32-18) |
| Adresses du réseau | = | 172.128.63.255 | 10101100.10000000.00111111.11111111 ||


## Plage d'IP :
Adresse du réseau +1 (ou gateway +1) = 172.128.0.1 &nbsp;&#8640;&nbsp; Adresse de broadcast -1 = 172.128.63.254
