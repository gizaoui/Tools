## MASQUE SOUS RESEAU

## Masque de sous réseau :
Peut-être défini par le CIDR (Classless Inter-Domain Routing) en l'associant à l'IP du réseau (172.128.10.5/18) 
255.255.192.0 -> `echo "obase=2;255;255;192;0" | bc` -> 11111111.11111111.11000000.00000000

## Partie basse sous réseau
Partie droite du masque n'affichant que des zéros : 
000000.00000000 -> 14 bits (CIDR=32-14=18) -> 0.0.2⁶.2⁸ -> 0.0.63.255 -> 00000000.00000000.00111111.11111111
Nombre d'IP disponibles : (64 x 256) - 2 = 16382

## Adresse IP du réseau :
172.128.10.5 -> `echo "obase=2;172;128;10;5" | bc` -> 11000000.10101000.00000000.00000101
La partie basse du masque sous réseau renvoit un CIDR à 18 -> On note 172.128.10.5/18

## Adresses du réseau :
Adresse IP & Masque de sous réseau
11000000.10101000.00000000.00000101 (172.128.10.5) &
11111111.11111111.11000000.00000000 (255.255.192.0) =
10101100.10000000.00000000.00000000 (172.128.0.0)

## Adresse de broadcast 
Adresses du réseau | Partie basse sous réseau :
10101100.10000000.00000000.00000000 (172.128.0.0) |
00000000.00000000.00111111.11111111 (0.0.63.255) =
10101100.10000000.00111111.11111111 (172.128.63.255)

## Plage d'IP :
Adresse réseau +1 / gateway (172.128.0.1) -> Adresse de broadcast -1 (172.128.63.254)
