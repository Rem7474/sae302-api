<?php
header("Access-Control-Allow-Origin: *");
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = getAllSales($connex);
//test si l'utilisateur existe
if ($result == null) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Error while getting sales')));
}
else{
    $result['message'] = "Sales found";
    $json = json_encode($result);
    die($json);
}
?>