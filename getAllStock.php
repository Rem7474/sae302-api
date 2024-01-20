<?php
header("Access-Control-Allow-Origin: *");
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = getAllStock($connex);
//test si l'utilisateur existe
if ($result == null) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Error while getting stock')));
}
else{
    $result['message'] = "All Stock found";
    $json = json_encode($result);
    die($json);
}
?>