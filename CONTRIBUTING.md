# Contribuer à Ina Zaoui Portfolio 📸

Merci de l'intérêt que vous portez au projet ! Pour garantir la qualité du code et la cohérence du projet, merci de suivre ces quelques directives.

## 🐛 Signaler un bug ou un problème
* **Issue** : Créez une Issue avec un titre clair : "Bug - Titre du bug"
* **Description** : Décrivez précisément le bug que vous avez observé : 
   - étapes pour recréer le bug 
   - réponse reçue 
   - précisez votre environnement (OS, navigateur...)
   - n'hésitez pas à ajouter des captures d'écran ou des logs si vous le jugez pertinent 

## 💡 Proposer de nouvelles fonctionnalités
* **Issue** : Créez une Issue avec le titre de la fonctionnalité proposée : "Evolution - Fonctionnalité proposée"
* **Description** : 
    - Détaillez le besoin et la fonctionnalité qui le résoudrait, 
    - proposez des solutions d'implémentation

## 🛠️ Workflow de développement

1. **Forkez** le projet ou créez une nouvelle branche (`feature/nom-de-la-fonctionnalite`).
2. Installez l'environnement de développement (voir le [README.md](./README.md)).
3. Codez votre fonctionnalité ou correction de bug.
4. Ajoutez les tests nécessaires à la nouvelle fonctionnalité

## 🎨 Standards de Code

Pour maintenir un code propre et lisible :
* **PHP** : Respectez les standards **PSR-12**.
* **Symfony** : Suivez les [bonnes pratiques officielles](https://symfony.com/doc/current/best_practices.html).
* **Commentaires** : Commentez votre code si nécessaire, en anglais.

## 🧪 Tests & Qualité

Avant de proposer une modification (Pull Request) :
1. Assurez-vous que tous les tests passent :
   ```bash
   php bin/phpunit
   ``` 
2. Vérifiez que votre code ne dégrade pas les performances (score Lighthouse ou nombre de requêtes Doctrine via le Profiler)
3. **Couverture de code** : Nous visons un maintien du taux de couverture actuel. Vous pouvez générer un rapport de coverage pour vérifier que vos modifications sont bien testées :
```bash
# Nécessite Xdebug ou PCOV
php bin/phpunit --coverage-html var/coverage
```
4. Une fois la commande terminée, ouvrez le fichier suivant dans votre navigateur pour consulter les détails : `var/coverage/index.html`

## 🚀 Soumettre vos modifications
Une fois votre branche prête, ouvrez une Pull Request (PR).

Expliquez clairement ce que votre PR apporte et référencez l'Issue concernée (ex: Fixes #12).

Un administrateur relira votre code avant de le fusionner.

---
Merci de contribuer à rendre le portfolio d'Ina Zaoui encore plus performant ! 🚀
