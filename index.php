<?php
header("Access-Control-Allow-Origin: *");
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
echo "Hello World!";
?>