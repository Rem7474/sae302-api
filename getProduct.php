<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['barecode'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing barecode')));
}
else if (!is_numeric($_GET['barecode'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'barecode must be numeric')));
}
$id = $_GET['barecode'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = getProduit($id, $connex);
if ($result == null) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Product not found')));
}
$json = json_encode($result);
//ajout d'une valeur de la clé message
$json = substr_replace($json, '"message":"Product found",', 1, 0);
echo $json;
?>