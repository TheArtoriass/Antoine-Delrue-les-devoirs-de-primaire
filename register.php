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

    echo "Inscription réussie !";
    // Rediriger vers la page de connexion après l'inscription réussie
    header('Location: login.php');
    exit;
}

// Récupérer les enfants pour les sélectionner
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'enfant'");
$stmt->execute();
$children = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>
<form method="POST" action="register.php">
    <div style="margin-bottom: 10px;">
        <label for="first_name">Prénom:</label>
        <input type="text" name="first_name" id="first_name" required style="width: 100%;"><br>
    </div>
    <div style="margin-bottom: 10px;">
        <label for="last_name">Nom:</label>
        <input type="text" name="last_name" id="last_name" required style="width: 100%;"><br>
    </div>
    <div style="margin-bottom: 10px;">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required style="width: 100%;"><br>
    </div>
    <div style="margin-bottom: 10px;">
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" required style="width: 100%;"><br>
    </div>
    <div style="margin-bottom: 10px;">
        <label for="role">Rôle:</label>
        <select name="role" id="role" onchange="toggleChildrenSelect()" style="width: 100%;">
            <option value="enfant">Enfant</option>
            <option value="enseignant">Enseignant</option>
            <option value="parent">Parent</option>
        </select><br>
    </div>
    <div id="children-select" style="display: none; margin-bottom: 10px;">
        <label for="children">Enfants:</label>
        <select name="children[]" id="children" multiple style="width: 100%;">
            <?php foreach ($children as $child): ?>
                <option value="<?php echo $child['id']; ?>"><?php echo htmlspecialchars($child['first_name'] . ' ' . htmlspecialchars($child['last_name'])); ?></option>
            <?php endforeach; ?>
        </select><br>
        <button type="button" onclick="deselectChildren()">Désélectionner tous les enfants</button>
    </div>
    <button type="submit">S'inscrire</button>
</form>

<!-- Liens pour retourner à l'accueil ou à la page de connexion -->
<div style="margin-top: 20px;">
    <a href="index.php">Retour à l'accueil</a> | <a href="login.php">Se connecter</a>
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