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

    echo "<h2>Exercices réalisés</h2><ul>";
    foreach ($exercises as $exercise) {
        echo "<li>" . htmlspecialchars($exercise['exercise_type']) . " - Score: " . htmlspecialchars($exercise['score']) . " - Date: " . htmlspecialchars($exercise['date']) . "</li>";
    }
    echo "</ul>";

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