<?php
// result_student.php

include 'db.php';

// profile.php
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

echo "<h2>Exercices réalisés</h2><ul>";
foreach ($exercises as $exercise) {
    echo "<li>" . htmlspecialchars($exercise['exercise_type']) . " - Score: " . htmlspecialchars($exercise['score']) . " - Date: " . htmlspecialchars($exercise['date']) . "</li>";
}
echo "</ul>";
?>