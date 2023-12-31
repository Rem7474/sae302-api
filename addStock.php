<?php
header("Access-Control-Allow-Origin: *");
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['barcode']) || empty($_GET['stock'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_numeric($_GET['barcode']) || !is_numeric($_GET['stock'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric')));
}
$barcode = $_GET['barcode'];
$stock = $_GET['stock'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = addStock($barcode, $stock, $connex);
if ($result != false) {
    //affiche un message de succès formaté en JSON
    die(json_encode(array('message' => 'Stock added')));
}
else {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Stock not added : Product not found')));
}
?>