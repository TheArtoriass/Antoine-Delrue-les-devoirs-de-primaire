<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] == 'add' && isset($_POST['student_first_name'], $_POST['student_last_name'])) {
        $first_name = trim($_POST['student_first_name']);
        $last_name = trim($_POST['student_last_name']);

        // Vérifier si l'élève existe et est bien un "enfant"
        $stmt = $pdo->prepare("SELECT id FROM users WHERE first_name = ? AND last_name = ? AND role = 'enfant'");
        $stmt->execute([$first_name, $last_name]);
        $student = $stmt->fetch();

        if (!$student) {
            $_SESSION['message'] = "❌ Aucun élève trouvé avec ce nom ou l'utilisateur n'est pas un enfant.";
            header("Location: profile.php");
            exit;
        }

        $student_id = $student['id'];

        // Vérifier si l'élève est déjà assigné à cet enseignant
        $stmt = $pdo->prepare("SELECT * FROM user_relationships WHERE teacher_id = ? AND child_id = ?");
        $stmt->execute([$_SESSION['user_id'], $student_id]);
        if ($stmt->fetch()) {
            $_SESSION['message'] = "⚠️ Cet élève est déjà dans votre liste.";
            header("Location: profile.php");
            exit;
        }

        // Ajouter l'élève à l'enseignant
        $stmt = $pdo->prepare("INSERT INTO user_relationships (teacher_id, child_id) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $student_id]);

        $_SESSION['message'] = "✅ Élève ajouté avec succès !";
        header("Location: profile.php");
        exit;
    }

    if ($_POST['action'] == 'remove' && isset($_POST['remove_student'])) {
        $student_id = $_POST['remove_student'];

        // Supprimer la relation enseignant-élève
        $stmt = $pdo->prepare("DELETE FROM user_relationships WHERE teacher_id = ? AND child_id = ?");
        $stmt->execute([$_SESSION['user_id'], $student_id]);

        $_SESSION['message'] = "❌ Élève supprimé avec succès !";
        header("Location: profile.php");
        exit;
    }
}

header("Location: profile.php");
exit;
?>