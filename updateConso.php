<?php
header("Access-Control-Allow-Origin: *");
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
//récupération des paramètres : uid et nbconso
if (empty($_GET['id']) || empty($_GET['nbconso'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_string($_GET['id']) || !is_numeric($_GET['nbconso'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric or string')));
}

//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = updateConso($_GET['id'], $_GET['nbconso'], $connex);
if ($result != false) {
    //affiche un message de succès formaté en JSON
    die(json_encode(array('message' => 'Conso updated')));
}
else {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Conso not updated')));
}
?>