<?php
// result_student.php

include 'db.php';
session_start();
include 'header.php'; 
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

echo "<h1>📊 Résultats de " . htmlspecialchars($student['first_name']) . " " . htmlspecialchars($student['last_name']) . "</h1>";

// Récupérer les exercices réalisés par l'élève
$stmt = $pdo->prepare("SELECT * FROM exercises WHERE user_id = ?");
$stmt->execute([$student_id]);
$exercises = $stmt->fetchAll();

echo "<h2>📝 Exercices réalisés</h2>";

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

    // Calcul des statistiques
    $total_exercises = count($exercises);
    $scores = array_column($exercises, 'score'); // Récupérer tous les scores
    $average_score = round(array_sum($scores) / $total_exercises, 2);
    $best_score = max($scores);
    $worst_score = min($scores);

    // Calcul de la médiane
    sort($scores);
    $middle = floor($total_exercises / 2);
    if ($total_exercises % 2 == 0) {
        $median_score = round(($scores[$middle - 1] + $scores[$middle]) / 2, 2);
    } else {
        $median_score = $scores[$middle];
    }

    // Nombre de scores au-dessus et en dessous de la moyenne
    $above_average = count(array_filter($scores, fn($s) => $s > $average_score));
    $below_average = count(array_filter($scores, fn($s) => $s < $average_score));

    echo "<h2>📊 Statistiques générales</h2>";
    echo "<ul>
            <li>📌 <strong>Nombre total d'exercices :</strong> $total_exercises</li>
            <li>📈 <strong>Score moyen :</strong> $average_score</li>
            <li>📊 <strong>Score médian :</strong> $median_score</li>
            <li>🏆 <strong>Meilleur score :</strong> $best_score</li>
            <li>💀 <strong>Pire score :</strong> $worst_score</li>
            <li>📊 <strong>Scores au-dessus de la moyenne :</strong> $above_average</li>
            <li>📉 <strong>Scores en dessous de la moyenne :</strong> $below_average</li>
          </ul>";

} else {
    echo "<p>Aucun exercice trouvé pour cet élève.</p>";
}

// Ajout d'un bouton retour
echo '<br><a href="profile.php">Retour au profil</a>';
?>
