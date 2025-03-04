<?php

include 'db.php';

// login.php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier les informations de connexion
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_first_name'] = $user['first_name'];
        $_SESSION['user_last_name'] = $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        header('Location: index.php');
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>

<form method="POST" action="login.php">
    Email: <input type="email" name="email" required><br>
    Mot de passe: <input type="password" name="password" required><br>
    <button type="submit">Se connecter</button>
</form>

<!-- Liens pour retourner à l'accueil ou à la page de connexion -->
<div style="margin-top: 20px;">
    <a href="index.html">Retour à l'accueil</a> | <a href="register.php">Créer un compte</a>
</div>