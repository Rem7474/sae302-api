<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['barecode']) || empty($_GET['stock'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_numeric($_GET['barecode']) || !is_numeric($_GET['stock'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric')));
}
$barecode = $_GET['barecode'];
$nom = $_GET['nom'];
$prixachat = $_GET['prixachat'];
$prixvente = $_GET['prixvente'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = ajoutProduit($barecode, $nom, $prixachat, $prixvente, $connex);
if ($result != null) {
    //affiche un message de succès formaté en JSON
    die(json_encode(array('message' => 'Stock added')));
}
else {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Stock not added')));
}
?>