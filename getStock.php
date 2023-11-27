<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['barcode'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_numeric($_GET['barcode'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric')));
}
$barcode = $_GET['barcode'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = getStock($barcode, $connex);
if ($result != null) {
    //affiche un message de succès formaté en JSON
    $json = json_encode($result);
    //ajout d'une valeur de la clé message
    $json = substr_replace($json, '"message":"Stock found",', 1, 0);
    die($json);
}
else {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Stock not found')));
}
?>