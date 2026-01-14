# ECF - Tests

## Partie 1 – Installation du projet

Clone du projet et installation des dépendances.  

```bash
composer install
```

Configuration de la bdd après ajout du fichier env.local

```bash
php bin/console d:d:c
php bin/console d:m:m
php bin/console d:f:l
symfony serve
```

Installation d'une dépendance pour réinitialiser la base de données entre chaque test 

```bash
composer require --dev dama/doctrine-test-bundle
```

puis configuration de la base test après ajout du fichier env.test.local

```bash
php bin/console --env=test d:d:c
php bin/console --env=test d:m:m
php bin/console --env=test d:f:l
```

## Partie 2 – Tests automatisés

```bash
php bin/console make:test
php bin/phpunit 
```
