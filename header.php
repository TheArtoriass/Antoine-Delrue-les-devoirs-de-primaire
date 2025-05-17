<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .header {
        background-color: #007BFF;
        color: white;
        padding: 10px;
        text-align: center;
    }
    .header a {
        color: white;
        margin: 0 15px;
        text-decoration: none;
        font-weight: bold;
    }
    .header a:hover {
        text-decoration: underline;
    }
</style>
<div class="header">
    <?php
     if (!isset($_SESSION['user_id'])) {
        echo '<a href="./index.php">Accueil</a>
              <a href="./register.php">Inscription</a>
              <a href="./login.php">Connexion</a>';
    } else {
        echo '<a href="./index.php">Accueil</a>
              <a href="./profile.php">Profil</a>
              <a href="./logout.php">Déconnexion</a>';
    }
    ?>
</div>