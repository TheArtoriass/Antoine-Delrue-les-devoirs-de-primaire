<?php
@ob_start();
include 'utils.php';
include '../db.php'; // Connexion à la base de données

$nomFichier = $_GET['nomFichier'];
$prenom = $_GET['prenomRes'];

log_adresse_ip("logs/log.txt", "supprime_resultat.php - " . $nomFichier);

// Trouver l'ID de l'exercice en cherchant dans le dossier historique/
$historique_files = scandir('./historique/');
$id_exercice = null;

foreach ($historique_files as $historique_file) {
    if (pathinfo($historique_file, PATHINFO_EXTENSION) === 'txt') {
        $file_content = file_get_contents('./historique/' . $historique_file);
        
        if (trim($file_content) === './resultats/' . $nomFichier) {
            $id_exercice = pathinfo($historique_file, PATHINFO_FILENAME);
            break;
        }
    }
}

// Supprimer l'entrée en base de données si l'exercice est trouvé
if ($id_exercice !== null) {
    $query = "DELETE FROM exercises WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id_exercice]);

    // Supprimer également le fichier historique
    unlink('./historique/' . $id_exercice . '.txt');
}

// Déplacer le fichier dans le dossier de suppression
rename('./resultats/' . $nomFichier, './supprime/' . $nomFichier);
usleep(100000); // Pause de 0.1 seconde

// Redirection vers la page des résultats
header('Location: ./affiche_resultat.php?prenomRes=' . urlencode($prenom));
exit();
?>
