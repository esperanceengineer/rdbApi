# Prise en main de l'api du RDB

## Pré-réquis:
### - php >= 8.2
### - SGBD

## Préparer le kit de développement
### 1. Installation
Exécuter les commandes suivantes :

    composer i --ignore-platform-reqs

Créer un fichier .env, voici un example .env.example à modifier si besoin

### 2. Génerer la clé de l'application pour le chiffrement
Exécuter la commande suivante :
    php artisan key:generate

## Préparer la base de données
Configuration de la base des données
    Créer la base des données et connecter avec laravel via la variable DB_DATABASE dans .env 
    Affecter la valeur de DB_USERNAME dans .env
    Affecter la valeur de DB_PASSWORD dans .env
Exécuter la commande ci-dessous pour la migration
    php artisan migrate

Le repertoire ```database/seeders``` contient l'ensemble des données qui seront importées dans après exécution de la commande ci-dessous.
    php artisan db:seed

## Exécuter le projet
    php artisan serve