<?php

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $password, $role]);

    $user_id = $pdo->lastInsertId();

    if ($role == 'parent' || $role == 'enseignant') {
        if (isset($_POST['children'])) {
            $children = $_POST['children'];
            foreach ($children as $child_id) {
                $stmt = $pdo->prepare("INSERT INTO user_relationships (parent_id, teacher_id, child_id) VALUES (?, ?, ?)");
                if ($role == 'parent') {
                    $stmt->execute([$user_id, null, $child_id]);
                } else {
                    $stmt->execute([null, $user_id, $child_id]);
                }
            }
        }
    }

    // echo "Inscription réussie !";
    // Rediriger vers la page de connexion après l'inscription réussie
    header('Location: login.php');
    exit;
}

// Récupérer les enfants pour les sélectionner
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'enfant'");
$stmt->execute();
$children = $stmt->fetchAll();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Inscription</title>
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
            width: 100%;
        }

        .select {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
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

        <h2 class="title">Inscription</h2>
        <form class="form" method="POST" action="register.php">
            <div class="input-group">
                <label for="first_name">Prénom:</label>
                <input class="input" type="text" name="first_name" id="first_name" required><br>
            </div>
            <div class="input-group">
                <label for="last_name">Nom:</label>
                <input class="input" type="text" name="last_name" id="last_name" required><br>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input class="input" type="email" name="email" id="email" required><br>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe:</label>
                <input class="input" type="password" name="password" id="password" required><br>
            </div>
            <div class="input-group">
                <label for="role">Rôle:</label>
                <select class="select" name="role" id="role" onchange="toggleChildrenSelect()">
                    <option value="enfant">Enfant</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="parent">Parent</option>
                </select><br>
            </div>
            <div id="children-select" class="input-group" style="display: none;">
                <label for="children">Enfants:</label>
                <select class="select" name="children[]" id="children" multiple>
                    <?php foreach ($children as $child): ?>
                        <option value="<?php echo $child['id']; ?>"><?php echo htmlspecialchars($child['first_name'] . ' ' . htmlspecialchars($child['last_name'])); ?></option>
                    <?php endforeach; ?>
                </select><br>
                <button class="button" type="button" onclick="deselectChildren()">Désélectionner tous les enfants</button>
            </div>
            <button class="button" type="submit">S'inscrire</button>
        </form>

        <!-- Liens pour retourner à l'accueil ou à la page de connexion -->
        <div class="links">
            <a class="link" href="index.php">Retour à l'accueil</a> | <a class="link" href="login.php">Se connecter</a>
        </div>
    </div>

    <script>
    function toggleChildrenSelect() {
        var role = document.getElementById('role').value;
        var childrenSelect = document.getElementById('children-select');
        if (role == 'parent' || role == 'enseignant') {
            childrenSelect.style.display = 'block';
        } else {
            childrenSelect.style.display = 'none';
        }
    }

    function deselectChildren() {
        var childrenSelect = document.getElementById('children');
        for (var i = 0; i < childrenSelect.options.length; i++) {
            childrenSelect.options[i].selected = false;
        }
    }
    </script>
</body>
</html>