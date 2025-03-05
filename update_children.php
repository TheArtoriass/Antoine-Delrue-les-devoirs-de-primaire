<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] == 'add' && isset($_POST['child_first_name'], $_POST['child_last_name'])) {
        $first_name = trim($_POST['child_first_name']);
        $last_name = trim($_POST['child_last_name']);

        // Vérifier si l'enfant existe et est bien un "enfant"
        $stmt = $pdo->prepare("SELECT id FROM users WHERE first_name = ? AND last_name = ? AND role = 'enfant'");
        $stmt->execute([$first_name, $last_name]);
        $child = $stmt->fetch();

        if (!$child) {
            $_SESSION['message'] = "❌ Aucun enfant trouvé avec ce nom ou l'utilisateur n'est pas un enfant.";
            header("Location: profile.php");
            exit;
        }

        $child_id = $child['id'];

        // Vérifier combien de parents a déjà cet enfant
        $stmt = $pdo->prepare("SELECT COUNT(*) as parent_count FROM user_relationships WHERE child_id = ? AND parent_id IS NOT NULL");
        $stmt->execute([$child_id]);
        $parent_count = $stmt->fetch()['parent_count'];

        if ($parent_count >= 2) {
            $_SESSION['message'] = "❌ Cet enfant a déjà deux parents enregistrés.";
            header("Location: profile.php");
            exit;
        }

        // Vérifier si l'enfant est déjà associé au parent
        $stmt = $pdo->prepare("SELECT * FROM user_relationships WHERE parent_id = ? AND child_id = ?");
        $stmt->execute([$_SESSION['user_id'], $child_id]);
        if ($stmt->fetch()) {
            $_SESSION['message'] = "⚠️ Cet enfant est déjà dans votre liste.";
            header("Location: profile.php");
            exit;
        }

        // Ajouter l'enfant au parent
        $stmt = $pdo->prepare("INSERT INTO user_relationships (parent_id, child_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $child_id]);

        $_SESSION['message'] = "✅ Enfant ajouté avec succès !";
        header("Location: profile.php");
        exit;
    }

    if ($_POST['action'] == 'remove' && isset($_POST['remove_child'])) {
        $child_id = $_POST['remove_child'];

        // Supprimer la relation parent-enfant
        $stmt = $pdo->prepare("DELETE FROM user_relationships WHERE parent_id = ? AND child_id = ?");
        $stmt->execute([$_SESSION['user_id'], $child_id]);

        $_SESSION['message'] = "❌ Enfant supprimé avec succès !";
        header("Location: profile.php");
        exit;
    }
}

header("Location: profile.php");
exit;
?>