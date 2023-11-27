<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['barcode'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing barcode')));
}
else if (!is_numeric($_GET['barcode'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'barcode must be numeric')));
}
$id = $_GET['barcode'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = getProduit($id, $connex);
if ($result == null) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Product not found')));
}
else {
    $result['message'] = 'Product found';
    $json = json_encode($result);
    die($json);
}
?>