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
        exit;
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
    <style>
        .body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .title {
            text-align: center;
            color: #333;
        }

        .form {
            display: flex;
            flex-direction: column;
        }

        .input {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button {
            padding: 10px;
            background-color: #45a1ff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #357ab8;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .link {
            color: #45a1ff;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<?php include 'header.php'; ?>
<body class="body">
    <div class="container">

        <h2 class="title">Connexion</h2>
        <form class="form" method="POST" action="login.php">
            Email: <input class="input" type="email" name="email" required><br>
            Mot de passe: <input class="input" type="password" name="password" required><br>
            <button class="button" type="submit">Se connecter</button>
        </form>

        <!-- Liens pour retourner à l'accueil ou à la page de connexion -->
        <div class="links">
            <a class="link" href="index.html">Retour à l'accueil</a> | <a class="link" href="register.php">Créer un compte</a>
        </div>
    </div>
</body>
</html>