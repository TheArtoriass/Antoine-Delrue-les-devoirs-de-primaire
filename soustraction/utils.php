<?php
// log adresse ip
// paramètre : nom du fichier de log
function log_adresse_ip($cheminFichierLog, $nomPage) {
    date_default_timezone_set('Europe/Paris');
    
    $adresseIP = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $dateTime = new DateTime();
    $jour = $dateTime->format('d/m/Y');
    $heure = $dateTime->format('H\hi\ms\s');
    $d = "[" . $jour . " " . $heure . "]";

    // Calculer le temps passé sur la page précédente
    $tempsPasse = '';
    if (isset($_SESSION['dernierAcces'])) {
        $dernierAcces = $_SESSION['dernierAcces'];
        $tempsPasse = $dateTime->getTimestamp() - $dernierAcces;
        $tempsPasse = " - Temps passé : " . $tempsPasse . "s";
    }

    // Mettre à jour le dernier accès
    $_SESSION['dernierAcces'] = $dateTime->getTimestamp();

    $logEntry = $d . " - " . $adresseIP . " - " . $httpMethod . " - " . $nomPage . $tempsPasse . " - " . $userAgent . "\n";

    // Créer le dossier avec la date du jour s'il n'existe pas
    $dossierLog = dirname($cheminFichierLog) . '/' . $dateTime->format('Y-m-d');
    if (!is_dir($dossierLog)) {
        mkdir($dossierLog, 0777, true);
    }

    // Chemin complet du fichier de log
    $cheminFichierLogComplet = $dossierLog . '/' . basename($cheminFichierLog);

    try {
        $fichierLog = fopen($cheminFichierLogComplet, "a");
        if ($fichierLog === false) {
            throw new Exception("Erreur lors de l'ouverture du fichier de log : " . $cheminFichierLogComplet);
        }
        fwrite($fichierLog, $logEntry);
        fclose($fichierLog);
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}
?>

<?php
function supprime_caracteres_speciaux($chaine) { 
    $chaine=str_replace("à","a",$chaine);
    $chaine=str_replace("â","a",$chaine);
    $chaine=str_replace("é","e",$chaine);
    $chaine=str_replace("è","e",$chaine);
    $chaine=str_replace("ë","e",$chaine);
    $chaine=str_replace("ê","e",$chaine);
    $chaine=str_replace("î","i",$chaine);
    $chaine=str_replace("ï","i",$chaine);
    $chaine=str_replace("ô","o",$chaine);
    $chaine=str_replace("ö","o",$chaine);
    $chaine=str_replace("ù","u",$chaine);
    $chaine=str_replace("û","u",$chaine);
    $chaine=str_replace("ü","u",$chaine);
    $chaine=str_replace("ÿ","y",$chaine);
    $chaine=str_replace("ç","c",$chaine);
    return $chaine;
}
?>

<?php
function conjugaison($nomFichier, $numLigne) {
    $fichierVerbe = file($nomFichier);
    $reponse = $fichierVerbe[$numLigne-1];
    $reponse = substr($reponse,0,-1);
    return $reponse;
}
?>
