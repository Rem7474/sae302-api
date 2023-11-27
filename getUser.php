<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['id'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing id')));
}
else if (!is_numeric($_GET['id'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'id must be numeric')));
}
$id = $_GET['id'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = getUtilisateur($id, $connex);
//test si l'utilisateur existe
if ($result == null) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'User not found')));
}
else{
    $json = json_encode($result);
    //ajout d'une valeur de la clé message
    $json = substr_replace($json, '"message":"User found",', 1, 0);
    echo $json;
}
?>