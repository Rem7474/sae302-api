<?php
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
//test de la fonction ajouterUtilisateur
print "après co :)<br />";
$result=ajouterUtilisateur('Doe', 'John', '999618078', $connex);
print_r($result);
print "après ajout :)<br />";
//test de la fonction getUtilisateur
$result = getUtilisateur('999618078', $connex);
print "après get :)<br />";
print_r($result);
echo $result['utilisateur_nom'];
echo $result['utilisateur_prenom'];
echo $result['utilisateur_rfid_uid'];
print "FIN :)<br />";
?>