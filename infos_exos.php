<?php
// infos_exos.php

include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si le paramètre historique est bien présent
if (!isset($_GET['historique'])) {
    echo "Fichier historique non spécifié.";
    exit;
}

$historique_path = urldecode($_GET['historique']); // Récupération et décodage du chemin

// Vérifier si le fichier historique existe
if (!file_exists($historique_path)) {
    echo "Le fichier historique demandé n'existe pas.";
    exit;
}

// Lire le contenu du fichier historique
$historique_content = file_get_contents($historique_path);

// Trouver le chemin du fichier de résultats
$result_path = str_replace('./resultats/', './addition/resultats/', $historique_content);

// Vérifier si le fichier de résultats existe
if (!file_exists($result_path)) {
    echo "Le fichier de résultats associé n'existe pas.";
    exit;
}

// Lire le contenu du fichier de résultats
$result_content = file_get_contents($result_path);

// Séparer les lignes pour affichage
$lines = explode("\n", trim($result_content));
$score = array_pop($lines); // Dernière ligne = score

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique de l'exercice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .result-container {
            display: inline-block;
            text-align: left;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
        .incorrect {
            color: red;
            font-weight: bold;
        }
        .score {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 15px;
        }
        .back-button {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h1>Historique de l'exercice</h1>

    <div class="result-container">
        <h2>Résultats :</h2>
        <?php foreach ($lines as $line): ?>
            <?php
            if (strpos($line, '********') !== false) {
                // Erreur : Séparer les valeurs
                $parts = explode(' = ', str_replace('********', '', $line));
                $question = trim($parts[0]);
                $answer = isset($parts[1]) ? trim($parts[1]) : '?';
                echo "<p class='incorrect'>$question = $answer (Faux)</p>";
            } else {
                // Bonne réponse
                echo "<p class='correct'>$line (Correct)</p>";
            }
            ?>
        <?php endforeach; ?>

        <p class="score">Score total : <?php echo htmlspecialchars($score); ?></p>
    </div>

    <br>
    <a href="profile.php" class="back-button">Retour au profil</a>

</body>
</html>
