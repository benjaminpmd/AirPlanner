# AirPlanner

AirPlanner est un outil destiné à la gestion d'aéroclubs, plus particulièrement dans la gestion de la réservations des appareils. Ce projet est réalisé dans le cadre de deux Unités d'enseignement (Base de données et Réseaux), de la licence informatique à CY Cergy Paris Université. Ce projet s'articule autours de un site web et une partie réseau.

---

## Réseau

On retrouve dans ce projet une partie réseau. Il est composé d'un client en C et un serveur en Python. Les échanges sont définis à l'aide d'un protocole basé sur du TCP.

### Serveur

Le serveur est chargé de traiter les requêtes effectuées par les clients, il communique avec la base de données et retourne les informations aux clients.

#### Installation

> L'usage du serveur requière `Python 3.8` ou supérieur.

Le serveur programmé en Python requiert l'installation des librairies suivantes :

- [psycopg2](https://www.psycopg.org/)
- [python-dotenv](https://github.com/theskumar/python-dotenv)

Afin de mettre en place le serveur deux solutions sont disponibles :

- (Linux/MacOS) L'utilisation du script fourni : `./setup.sh` (Ce script requière l'installation de `python3-pip` et `python3-venv`)
- (Windows/Linux/MacOS) L'utilisation de pip `pip install -r requirements.txt`

#### Utilisation

Une fois les librairies installées, le serveur peut être lancé de différentes manières :

- (Linux/MacOS) En utilisant le script : `./setup.sh run`
- (Linux/MacOS) En executant le script principal : `python3 src/main.py`
- (Windows) En executant le script principal : `python src/main.py`

> Attention, ce script utilise des variables d’environnement, afin qu'elles soient détectables par le script, vous devez lancer le serveur depuis le répertoire ou se trouve le fichier `.env`

Si vous avez utilisé la commande `./setup.sh` pour mettre en place le serveur, vous devrez utiliser `./setup.sh run` ou bien activer l'environnement Python crée par le script pour lancer le serveur.

### Client

Le client réseau communique avec le serveur, pour se faire, il est nécessaire d'indiquer l'adresse IP et le du serveur au lancement du client. 

#### Installation

> La compilation du client requière la présence d'un compilateur C.

La compilation du client peut se faire de deux manières :

- (Linux/MacOS) En utilisant la commande `make`
- (Windows) En utilisant un compilateur C

#### Utilisation

L'execution du client réseau pse fera de différentes façons selon l'OS utilisé :

- (Linux/MacOS) L'utilisation de la commande `make run`
- (Windows) L'execution du fichier compilé

---

## Site Web

Le site web disponible à [cette adresse](https://airplanner.benjaminpmd.fr/) est réalisé en `HTML 5`, `CSS 3`, `PHP7.4`. Il a pour but de gérer les réservations des vols, ainsi que la disponibilité des appareils, notamment au travers d'une page réservée aux mécaniciens du club.

### Installation

> L'installation de librairie requiert `NodeJS 16` ou supérieur et `npm 8` ou supérieur.

Le site Web utilise la librairie CSS suivante : [tailwindcss](https://tailwindcss.com/), afin d'installer cette librairie, deux solutions sont proposées

### Utilisation

Certains fichiers de configuration ne servent qu'au développement. Pour obtenir une version du site prête au déploiement, il existe deux solutions :

- (Linux/MacOS) L'utilisation du script `./setup.sh build`
- (Windows) L'utilisation du script `./setup.bat build`

Un version du site prête au déploiement est alors disponible dans le répertoire `dist`.

Le site web ayant besoin de se connecter à une base de donnée, les éléments de connexions sont stockés dans des variables d’environnement, de même que les éléments de connexion pour l'envoi de mail.
