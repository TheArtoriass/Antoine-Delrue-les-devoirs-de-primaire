<?php
// profile.php

include 'db.php';

// profile.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Lien vers la page d'accueil et déconnexion
echo '<div style="margin-bottom: 20px;">
    <a href="index.php" style="margin-right: 15px;">Accueil</a>
    <a href="logout.php" style="margin-right: 15px;">Déconnexion</a>
</div>';

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

echo "<h1>Profil de " . htmlspecialchars($user['first_name']) . " " . htmlspecialchars($user['last_name']) . "</h1>";
echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
echo "<p>Rôle: " . htmlspecialchars($user['role']) . "</p>";


if ($user['role'] == 'enfant') {
    // Récupérer les exercices réalisés par l'enfant
    $stmt = $pdo->prepare("SELECT * FROM exercises WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $exercises = $stmt->fetchAll();

    // Vérifier s'il y a des exercices
    if (count($exercises) > 0) {
        // Calcul des statistiques
        $total_exercises = count($exercises);
        $scores = array_column($exercises, 'score'); // Récupérer tous les scores
        $average_score = round(array_sum($scores) / $total_exercises, 2);
        $best_score = max($scores);
        $worst_score = min($scores);

        echo "<h2>Statistiques générales</h2>";
        echo "<ul>
                <li>Nombre total d'exercices : <strong>$total_exercises</strong></li>
                <li>Score moyen : <strong>$average_score</strong></li>
                <li>Meilleur score : <strong>$best_score</strong></li>
                <li>Pire score : <strong>$worst_score</strong></li>
              </ul>";

        // Générer les données pour le graphique
        $exercise_labels = [];
        $exercise_scores = [];
        foreach ($exercises as $exercise) {
            $exercise_labels[] = htmlspecialchars($exercise['date']);
            $exercise_scores[] = $exercise['score'];
        }

        echo "<h2>Exercices réalisés</h2>";
        echo "<ul>";
        foreach ($exercises as $exercise) {
            // Génération du chemin du fichier historique
            $historique_path = urlencode($exercise['exercise_type'] . "/historique/" . $exercise['id'] . ".txt");

            // Lien vers infos_exos.php avec le chemin en paramètre
            echo "<li>
                    <a href='infos_exos.php?historique=$historique_path'>" . 
                        htmlspecialchars($exercise['exercise_type']) . 
                        " - Score: " . htmlspecialchars($exercise['score']) . 
                        " - Date: " . htmlspecialchars($exercise['date']) . 
                    "</a>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun exercice trouvé.</p>";
    }




} elseif ($user['role'] == 'parent') {
    // Récupérer les enfants du parent
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id IN (SELECT child_id FROM user_relationships WHERE parent_id = ?)");
    $stmt->execute([$_SESSION['user_id']]);
    $children = $stmt->fetchAll();

    echo "<h2>Liste des enfants</h2><ul>";
    foreach ($children as $child) {
        echo "<li>ID: " . htmlspecialchars($child['id']) . " - Nom: <a href='result_student.php?id=" . htmlspecialchars($child['id']) . "'>" . htmlspecialchars($child['first_name']) . " " . htmlspecialchars($child['last_name']) . "</a></li>";
    }
    echo "</ul>";

    // Formulaire pour ajouter ou supprimer des enfants
    echo '<h2>Ajouter ou supprimer des enfants</h2>';
    echo "<p>Pour ajouter un nouvel enfant dans la famille, entrez son ID ci-dessous. Pour supprimer un enfant à cause d'un mauvais clique, entrez son ID dans le champ de suppression.</p>";
    echo '<form method="POST" action="update_children.php">';
    echo '<label for="add_child">Ajouter un enfant (ID):</label>';
    echo '<input type="text" name="add_child" id="add_child">';
    echo '<button type="submit" name="action" value="add">Ajouter</button>';
    echo '<br>';
    echo '<label for="remove_child">Supprimer un enfant (ID):</label>';
    echo '<input type="text" name="remove_child" id="remove_child">';
    echo '<button type="submit" name="action" value="remove">Supprimer</button>';
    echo '</form>';

   
} elseif ($user['role'] == 'enseignant') {
    // Récupérer les élèves de l'enseignant
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id IN (SELECT child_id FROM user_relationships WHERE teacher_id = ?)");
    $stmt->execute([$_SESSION['user_id']]);
    $students = $stmt->fetchAll();

    echo "<h2>Liste des élèves</h2><ul>";
    foreach ($students as $student) {
        echo "<li>ID: " . htmlspecialchars($student['id']) . " - Nom: <a href='result_student.php?id=" . htmlspecialchars($student['id']) . "'>" . htmlspecialchars($student['first_name']) . " " . htmlspecialchars($student['last_name']) . "</a></li>";
    }
    echo "</ul>";

    // Formulaire pour ajouter ou supprimer des enfants
    echo '<h2>Ajouter ou supprimer des enfants</h2>';
    echo '<form method="POST" action="update_students.php">';
    echo '<label for="add_child">Ajouter un enfant (ID):</label>';
    echo '<input type="text" name="add_child" id="add_child">';
    echo '<button type="submit" name="action" value="add">Ajouter</button>';
    echo '<br>';
    echo '<label for="remove_child">Supprimer un enfant (ID):</label>';
    echo '<input type="text" name="remove_child" id="remove_child">';
    echo '<button type="submit" name="action" value="remove">Supprimer</button>';
    echo '</form>';
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>