# les-devoirs-de-primaire
Site permettant aux enfants en primaire de faire des exercices de maths/français et peut être plus par la suite.

## ⚙️ Installation
1.  **Cloner le dépôt en local** ou **Téléchargez le code**
   ```bash
    https://github.com/TheArtoriass/Antoine-Delrue-les-devoirs-de-primaire.git
   ```

2. **Configurer le serveur web**  
   - Utilisez un serveur local comme **WAMP**, **MAMP**... pour héberger le projet.  
   - Configurez le serveur vers le répertoire du projet.

3. **Démarrer votre serveur**
   - Lancez le serveur (par exemple, **MAMP** ou **XAMPP**) pour pouvoir accéder à l’application via votre navigateur.

4. **Après le transfert (Si besoin)**
    - dans les répertoires addition, conjugaison_phrase, conjugaison_verbe, dictee, multiplication et soustraction, changez les droits en 777 pour les sous-répertoires logs, resultats et supprime

5. **Importer la base de données**  
   - Accédez à [phpMyAdmin](http://localhost/phpMyAdmin/).  
   - Importez le fichier `bdd.sql` pour créer la base de données et la table des utilisateurs.

6. **Mettre à jour les informations de connexion**  
   - Modifiez le fichier `db.php` avec vos identifiants MySQL.

7. **Lancer l’application**  
   - Accédez à [http://localhost/](http://localhost/) dans votre navigateur.
---

# Utilisation :
Rendez-vous sur la page d'accueil puis sélectionnez l'exercice à réaliser. La configuration des exercices (changement du temps pour les conjugaisons, des bornes des nombres pour les exercices de math, etc.).

Pour voir les résultats d'un enfant, rendez-vous sur la page d'accueil, entrez dans l'exercice pour lequel vous voulez les résultats puis, dans la barre d'adresse, modifiez le index.php par affiche_resultat.php.

### Système de connexion et rôles :
Le site inclut un système de connexion et de création de comptes avec visualisation de statistiques sur le profil. Il y a trois rôles différents :
- **Enfant** : Peut faire des exercices.
- **Enseignant** : Peut voir les résultats de leurs élèves.
- **Parent** : Peut voir les résultats de leurs enfants.

### Système de logs :
Un meilleur système de logs est en place pour suivre les activités des utilisateurs.

### Base de données :
Les utilisateurs, les exercices et les relations sont stockés en base de données.

### Résultats par thème :
On peut également voir les résultats pour chaque thème.

--- 
# TODO
La refonte graphique n'est pas demandée. Si vous voulez modifier le design, vous pouvez mais conservez le style d'origine et ne partez pas sur le design du site des impots !

1 - Créer un système de connexion avec profil (10 points) : inclut l'inscription, la connexion et la sauvegarde des différents exercices réalisés avec visualisation de stats sur son profil.

1.5 - Ajout de rôle aux utilisateurs (10 points) : ajout des rôles enfant, enseignant et parent. Les parents peuvent voir les résultats de leurs enfants. Les enseignants peuvent voir les résultats de leurs élèves. Les enfants peuvent faire des exercices. Dans l'idéal, il faudrait que les enseignants puissent configurer (voir point 5) les exercices pour les enfants.

2 - Améliorer le système de logs (3 points) : Voir les répertoires logs de chaque exercice. 

3 - Utiliser une base de données (3 points) : peut facilement être combiné avec le système de connexion (point 1 et 1.5).

4 - Améliorer le système d'affichage des résultats (2 points) : Peut être naturellement combiné avec le point 1 (stats sur profil).

7 - Documentation complète du projet (3 points) : commentaire dans le code, manuel utilisateur, manuel du développeur, document pour l'aide à l'installation, etc.

Autre : Header, ...

# Comment rendre son travail

Voir Cours Moodle SAES6 maintenance
