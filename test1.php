<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
//test de la fonction ajouterUtilisateur
//$result=ajouterUtilisateur('Doe', 'John', '999618078', $connex);
//print_r($result);
//test de la fonction getUtilisateur
$result = getUtilisateur('999618078', $connex);
$json = json_encode($result);
echo $json;
?>