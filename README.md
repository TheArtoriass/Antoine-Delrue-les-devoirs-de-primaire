# les-devoirs-de-primaire Antoine Delrue
Site permettant aux enfants en primaire de faire des exercices de maths/français.

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
   - Importez le fichier `bdd.sql`.

6. **Mettre à jour les informations de connexion**  
   - Modifiez le fichier `db.php` avec vos identifiants MySQL.

7. **Lancer l’application**  
   - Accédez à [http://localhost/](http://localhost/) dans votre navigateur.

## Installation Docker

1. Cloner le dépôt en local

```bash
git clone https://github.com/TheArtoriass/Antoine-Delrue-les-devoirs-de-primaire.git
cd Antoine-Delrue-les-devoirs-de-primaire
```

2. Vérifier que le fichier bdd.sql est bien placé dans le dossier `db/init/`
   (Il sera importé automatiquement au premier lancement du conteneur MySQL)

3. Construire et lancer les conteneurs Docker
```bash
docker-compose up --build
```

4. Attendre que les services soient prêts (cela peut prendre quelques secondes au premier lancement)

5. Accéder à l’application :
   [http://localhost:8000/](http://localhost:8000/)

6. Pour arrêter les conteneurs :
```bash
docker-compose down
```

---

# Utilisation :

Rendez-vous sur la page d'accueil en tant qu'enfant puis sélectionnez l'exercice à réaliser. Pour visualiser ses exercices, allez sur le profil ou dans le thème et consultez "Voir les résultats".

Pour les parents/enseignants, pour voir les résultats d'un enfant, rendez-vous sur la page d'accueil, entrez dans l'exercice pour lequel vous voulez les résultats puis cliquez sur "Voir les résultats" ou dans le profil, cliquez sur le nom de l'enfant et recliquez sur les résultats pour plus de détails. Vous pouvez, dans les deux rôles, supprimer ou ajouter des enfants.

## Compte fictif pour visiter

| Prénom     | Nom      | Email                          | Mot de passe   | Rôle       | Enfants          |
|------------|----------|-------------------------------|----------------|------------|------------------|
| Enfant     | exemple  | enfant.exemple@hotmail.com     | sae_enfant     | Enfant     | -                |
| Parent     | exemple  | parent.exemple@gmail.com       | sae_parent     | Parent     | Enfant exemple   |
| Enseignant | exemple  | enseignant.exemple@hotmail.fr  | sae_enseignant | Enseignant | Enfant exemple   |

--- 
# Partie réalisée

1. **Créer un système de connexion avec profil (10 points)** : inclut l'inscription, la connexion et la sauvegarde des différents exercices réalisés avec visualisation de statistiques sur son profil.
   - **Enfant** : Peut faire des exercices.
   - **Enseignant** : Peut voir les résultats de leurs élèves.
   - **Parent** : Peut voir les résultats de leurs enfants.

1.5. **Ajout de rôle aux utilisateurs (10 points)** : ajout des rôles enfant, enseignant et parent. Les parents peuvent voir les résultats de leurs enfants. Les enseignants peuvent voir les résultats de leurs élèves. Les enfants peuvent faire des exercices.

2. **Améliorer le système de logs (3 points)**
   - Dossier pour le jour
   - Moteur de recherche enregistré
   - Méthode GET ou POST
   - Date et heure actuelles (new DateTime())
   - Temps passé sur la page précédente

3. **Utiliser une base de données (3 points)**
   - Les utilisateurs, le score des exercices et les relations sont stockés en base de données.

4. **Améliorer le système d'affichage des résultats (2 points)**
   - On peut également voir les résultats pour chaque thème.

Autre : Header, commentaires dans la plupart des nouveaux fichiers, document pour l'aide à l'installation, pas d'exercices en non connecté, retirer les exercices pour les enseignants et parents, ajout/suppression d'enfants, suppression des exercices, etc.



