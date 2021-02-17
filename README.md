# tp_symfony

## Équipe
* Léa DE AMORIN
* Eden BERGEL
* Hanna BERGEL
* Samir CHALAL
* Teddy BOIRIN
* Carlo BERNI
* Jeremy SCHIAPPAPIETRE

## Setup
* Installer les dépendances
```bash
`composer i && npm i`
```
* Configurer votre .env
* Configurer votre base de données   
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
* Lancer le serveur Symfony
```bash
symfony server:start
```
* Lancer le serveur Webpack
```bash
npm run dev-server
```



## Partie 4
La fonction finCatSpecial permet de retourner les catégories spéciales d’une bière en particulier avec son ID, car elle met en relation 2 tables (Beer et Category).


## Schéma UML
![Schema](https://github.com/leadmrn/tp_symfony/blob/master/assets/schema.png)
