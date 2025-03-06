<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Résultats</title>
</head>
<body style="background-color:grey;">
    <center>
    <?php include(__DIR__ . '/../header.php'); ?>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                    <center>

                        <?php
                        include 'utils.php';
                        include '../db.php'; // Connexion à la base de données

                        $role = $_SESSION['user_role'] ?? '';
                        $prenom = htmlspecialchars($_SESSION['user_first_name'] ?? '');
                        $nom = htmlspecialchars($_SESSION['user_last_name'] ?? '');

                        if (!isset($_GET['prenomRes']) || $_GET['prenomRes'] == "") {
                            log_adresse_ip("logs/log.txt", "affiche_resultat.php");
                        ?>
                        
                        <h3>Quel est le prénom de l'enfant ?</h3><br />
                        <form action="./affiche_resultat.php" method="get">
                            <input type="text" id="prenomRes" name="prenomRes" autocomplete="off"
                                value="<?php echo $prenom; ?>" <?php echo ($role == 'enfant') ? 'readonly' : ''; ?>><br /><br />
                            <input type="text" id="nomRes" name="nomRes" autocomplete="off"
                                value="<?php echo $nom; ?>" <?php echo ($role == 'enfant') ? 'readonly' : ''; ?>><br /><br />
                            <input type="submit" value="Afficher les résultats">
                        </form>

                        <?php
                        } else {
                            log_adresse_ip("logs/log.txt", "affiche_resultat.php - " . $_GET['prenomRes']);
                            echo '<h1>Résultats de ' . $_GET['prenomRes'] . '</h1>';
                            
                            $_GET['prenomRes'] = strtolower($_GET['prenomRes']);
                            $_GET['prenomRes'] = supprime_caracteres_speciaux($_GET['prenomRes']);

                            $files = scandir('./resultats/');
                            $total = 0;

                            // Fonction pour récupérer les infos de l'exercice en BDD
                            function get_exercise_info($pdo, $exercise_id) {
                                $query = "SELECT exercise_type, score, date FROM exercises WHERE id = ?";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute([$exercise_id]);
                                return $stmt->fetch(PDO::FETCH_ASSOC);
                            }

                            foreach ($files as $fichier) {
                                if (substr($fichier, 0, strlen($_GET['prenomRes'])) == $_GET['prenomRes']) {
                                    
                                    // On cherche l'ID de l'exercice dans historique/
                                    $historique_files = scandir('./historique/');
                                    $id_exercice = null;

                                    foreach ($historique_files as $historique_file) {
                                        if (pathinfo($historique_file, PATHINFO_EXTENSION) === 'txt') {
                                            $file_content = file_get_contents('./historique/' . $historique_file);
                                            
                                            if (trim($file_content) === './resultats/' . $fichier) {
                                                $id_exercice = pathinfo($historique_file, PATHINFO_FILENAME);
                                                break;
                                            }
                                        }
                                    }

                                    // Si on a trouvé un ID, on récupère ses infos en BDD
                                    if ($id_exercice !== null) {
                                        $exercice_info = get_exercise_info($pdo, $id_exercice);

                                        if ($exercice_info) {
                                            $exercise_type = ucfirst($exercice_info['exercise_type']); // Majuscule initiale
                                            $score = $exercice_info['score'];
                                            $date = date('Y-m-d H:i:s', strtotime($exercice_info['date']));

                                            // Correction : Ajout de "conjugaison_phrase/" devant historique/
                                            $historique_path = urlencode("conjugaison_phrase/historique/" . $id_exercice . ".txt");
                                            
                                            echo '<a href="../infos_exos.php?historique=' . $historique_path . '">'
                                                 . $exercise_type . ' - Score: ' . $score . ' - Date: ' . $date . '</a> : ';
                                        } else {
                                            echo '<a href="../infos_exos.php?historique=' . urlencode("conjugaison_phrase/historique/" . $id_exercice . ".txt") . '">'
                                                 . 'Exercice inconnu</a> : ';
                                        }
                                    } else {
                                        echo '<a href="./resultats/' . $fichier . '">' . $fichier . '</a> : ';
                                    }

                                    // Lire le score du fichier et ajouter au total
                                    $fichierOuvert = file('./resultats/' . $fichier);
                                    $der_ligne = trim($fichierOuvert[count($fichierOuvert) - 1]);
                                    $total += (int)$der_ligne;

                                    echo $der_ligne . ' points - <a href="supprimer_resultat.php?prenomRes=' . $_GET['prenomRes'] . '&nomFichier=' . $fichier . '">supprimer</a><br /><br />';
                                }
                            }

                            echo '<hr><br />';
                            echo '<h2>TOTAL : ' . $total . ' ' . ($total > 1 ? 'POINTS' : 'POINT') . '</h2>';
                        }
                        ?>

                    </center>
                </td>
                <td style="width:280px;height:430px;background-image:url('./images/NE.jpg');background-repeat:no-repeat;"></td>
            </tr>
            <tr>
                <td style="width:1000px;height:323px;background-image:url('./images/SO.jpg');background-repeat:no-repeat;"></td>
                <td style="width:280px;height:323px;background-image:url('./images/SE.jpg');background-repeat:no-repeat;"></td>
            </tr>
        </table>
    </center>
    <br />
    <footer style="background-color: #45a1ff;">
        <center>
            Rémi Synave<br />
            Contact : remi . synave @ univ - littoral [.fr]<br />
            Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/">Mimzy</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=1576791">Pixabay</a> <br />
            et Image par <a href="https://pixabay.com/fr/users/everesd_design-16482457/">everesd_design</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=5213756">Pixabay</a> <br />
        </center>
    </footer>
</body>
</html>