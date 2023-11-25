<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
//test de la fonction ajouterUtilisateur
ajouterUtilisateur('Doe', 'John', '999618078', $connex);
//test de la fonction getUtilisateur
$result = getUtilisateur('999618078', $connex);
echo $result['utilisateur_nom'];
echo $result['utilisateur_prenom'];
echo $result['utilisateur_rfid_uid'];
?>