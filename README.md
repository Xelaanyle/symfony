# Symfony

Ce repo contient une application de gestion de formation.
Il s'agit d'un projet pédagogique pour la promo 11


## Prérequis

- Linux, MacOS ou Windows
- Bash
- PHP 8
- Composer
- Symfony-cli
- MariaDB 10
- Docker (optionnel)

## Installation

```
git clone https://github.com/xelaanyle/symfony
cd symfony
composer install
```

Créez une base de données et un utilisateur dédié pour cette base de données.

## Configuration

Créer un fichier `.env.local` à la racine du projet : 

```
APP_ENV=dev
APP_DEBUG=true
APP_SECRET=961e8f78f21f2fec123456ea2a6f2a3f
DATABASE_URL="mysql://symfony:*password*@127.0.0.1:3306/symfony?serverVersion=mariadb-10.6.12&charset=utf8mb4"
```

Pensez à changer la variable `APP_SECRET` et les codes d'accès dans la variable `DATABASE_URL`.

**ATTENTION : `APP_SECRET` doit être une chaîne de caractère de 32 caractères en hexadecimal.**

## Migration et fixtures

Pour que l'application soit utilisable, vous devez créez le schéma de base de données et charger les données de test :

```
bin/dofilo.sh
```

## Utilisation

Lancez le serveur web de développement :

```
symfony serve
```

Puis ouvrez la page suivante : [https://localhost:8000](https://localhost:8000)

## Mention légales

Sous projet est sous licence MIT.

La licence est disponible ici [MIT LICENCE](LICENCE).
