[![.github/workflows/ci.yml](https://github.com/Fred-Cypher/P15-InaZaoui/actions/workflows/ci.yml/badge.svg)](https://github.com/Fred-Cypher/P15-InaZaoui/actions/workflows/ci.yml)

# Ina Zaoui - Portfolio photographe 📷 
___
## 📝 Description générale :
Projet du parcours "Développeur d'application PHP Symfony" d'OpenClassrooms.

**Objectif** : Refactorisation d'un site existant pour optimiser les performances (réduction drastique des requêtes SQL) et assurer sa maintenabilité (Passage sous Symfony 7 / PHP 8.1+).

Le site permet à la photographe Ina Zaoui de présenter ses albums et de mettre en avant de jeunes talents.

## 🌍 Fonctionnalités du site : 
* **Galeries dynamiques** : Affichage des medias enregistrés en base de données.
* **Espace Administration** : Gestion des utilisateurs par l'administrateur (Ina Zaoui).
* **Espace Contributeur** : Gestion des medias par les utilisateurs propriétaires et l'administrateur.

## 🛠️ Installation :
* **Cloner le projet**.
* **Installer les dépendances :** Dans un terminal, placez-vous dans le dossier, utilisez la commande ```composer install```.
* **Configurer l'environnement :** Dupliquez le fichier .env et renommer la copie en .env.local et adaptez la variable : DATABASE_URL.
* **Initialiser la base de données :** Utilisez les commandes ```php bin/console doctrine:database:create```, puis ```php bin/console doctrine:migrations:migrate```.
* **Charger des données de démonstration :** Le projet inclut un jeu de données complet via **DoctrineFixtures**, pour remplir votre base de données locale avec des utilisateurs test et des galeries d'images dynamiques (via Picsum), lancez la commande ```php bin/console doctrine:fixtures:load```. ⚠️ Cette commande nécessite une connexion internet pour récupérer les images de démonstration.
___
## 🔐 Identifiants de test
Une fois les fixtures chargées, vous pouvez utiliser les comptes suivants :
* Admin : admin@test.com / password
* Invité actif : active@test.com / password
---
## 💻 Utilisation :
* **Accès au site :** Lancez le serveur local avec ```symfony serve```.
* **Espace Administration :**  
    - Connectez-vous avec les identifiants de test (admin@test.com ou active@test.com)
    - Cliquez sur le lien "Panneau d'administration" présent dans le footer pour les utilisateurs connectés
___
## 🧪 Environnement de tests : 
* **Configurer l'environnement :** Adaptez le DATABASE_URL dans le fichier .env.test.
* **Initialiser la base de données :** Créez une base de données de tests avec les commandes suivantes : ```php bin/console doctrine:database:create --env=test``` et ```php bin/console doctrine:migrations:migrate --env=test```
* **Charger des données de démonstrations :** ```php bin/console doctrine:fixtures:load --env=test```, ⚠️ comme pour la base de données au-dessus, cette commande nécessite une connexion internet pour récupérer les images
* **Exécution des tests :** Lancez la suite de tests avec la commande ```php bin/phpunit```
---
## 📊 Qualité du code
Le rapport de couverture de code est disponible dans le dépôt :
- Chemin : `var/coverage/index.html`
- Taux de couverture actuel : > 80%
---
## 🔍 Outils / logiciels nécessaires
* PostgreSQL
* IDE : VSC, PhpStorm...
---

📌 Stack technique :  PHP8.1, Symfony7, PostgreSQL
