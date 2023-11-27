<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['barcode']) || empty($_GET['nom']) || empty($_GET['prixachat']) || empty($_GET['prixvente'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_numeric($_GET['barcode']) || !is_string($_GET['nom']) || !is_numeric($_GET['prixachat']) || !is_numeric($_GET['prixvente'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric or string')));
}
$barcode = $_GET['barcode'];
$nom = $_GET['nom'];
$prixachat = $_GET['prixachat'];
$prixvente = $_GET['prixvente'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = ajoutProduit($barcode, $nom, $prixachat, $prixvente, $connex);
if ($result != null) {
    //affiche un message de succès formaté en JSON
    die(json_encode(array('message' => 'Product added')));
}
else {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Product not added')));
}
?>