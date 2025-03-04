<?php
// result_student.php

include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID de l'élève non spécifié.";
    exit;
}

$student_id = $_GET['id'];

// Récupérer les informations de l'élève
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    echo "Élève non trouvé.";
    exit;
}

echo "<h1>Résultats de " . htmlspecialchars($student['first_name']) . " " . htmlspecialchars($student['last_name']) . "</h1>";

// Récupérer les exercices réalisés par l'élève
$stmt = $pdo->prepare("SELECT * FROM exercises WHERE user_id = ?");
$stmt->execute([$student_id]);
$exercises = $stmt->fetchAll();

echo "<h2>Exercices réalisés</h2>";

if (count($exercises) > 0) {
    echo "<ul>";
    foreach ($exercises as $exercise) {
        $exercise_type = htmlspecialchars($exercise['exercise_type']);
        $score = htmlspecialchars($exercise['score']);
        $date = htmlspecialchars($exercise['date']);
        $exercise_id = $exercise['id']; // On récupère l'ID de l'exercice

        // Construire le chemin du fichier historique
        $historique_path = urlencode("$exercise_type/historique/$exercise_id.txt");

        // Lien vers infos_exos.php avec le bon fichier historique
        echo "<li>
                <a href='infos_exos.php?historique=$historique_path'>
                    $exercise_type - Score: $score - Date: $date
                </a>
              </li>";
    }
    echo "</ul>";
} else {
    echo "<p>Aucun exercice trouvé pour cet élève.</p>";
}

// Ajout d'un bouton retour
echo '<br><a href="profile.php">Retour au profil</a>';
?>
