<?php
header("Access-Control-Allow-Origin: *");
//affiche les erreurs et les avertissements PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
if (empty($_GET['iduser']) || empty($_GET['barcode']) || empty($_GET['quantite'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_string($_GET['iduser']) || !is_numeric($_GET['barcode']) || !is_numeric($_GET['quantite'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric')));
}
$iduser = $_GET['iduser'];
$barcode = $_GET['barcode'];
$quantite = $_GET['quantite'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
$result = addVente($iduser, $barcode, $quantite ,$connex);
if ($result["erreur"] != true) {
    //affiche un message de succès formaté en JSON
    die(json_encode(array('message' => 'sale added')));
}
else {
    //affiche un message d'erreur formaté en JSON
    $result["message"] = "Error while adding sale";
    die(json_encode($result));
}
?>