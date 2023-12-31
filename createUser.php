<?php
header("Access-Control-Allow-Origin: *");
//inclure le fichier fonctionsBDD
include 'FonctionsBDD.php';
//inclure le fichier fonctionsConnexion
include 'FonctionsConnexion.php';
header('Content-Type: application/json');
//récupération du nom, du prénom et de l'id de l'utilisateur
if (empty($_GET['id']) || empty($_GET['nom']) || empty($_GET['prenom']) || empty($_GET['nbconso'])) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'Missing information')));
}
else if (!is_string($_GET['id']) || !is_string($_GET['nom']) || !is_string($_GET['prenom']) || !is_numeric($_GET['nbconso'])) {
    //affiche un message d'erreur que les données ne sont pas au bon format, formaté en JSON
    die(json_encode(array('message' => 'Information must be numeric or string')));
}
$id = $_GET['id'];
$nom = $_GET['nom'];
$prenom = $_GET['prenom'];
$nbconso = $_GET['nbconso'];
//connexion à la base de données
$connex=connexionBDD('./private/parametres.ini');
//vérfication si l'utilisateur existe déjà
if (getUtilisateur($id, $connex) != null) {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'User already exists')));
}
$result=ajouterUtilisateur($nom, $prenom, $id, $nbconso, $connex);
//test si l'utilisateur a bien été ajouté (si l'id de l'utilisateur est retourné)
if ($result != null) {
    //affiche un message de succès formaté en JSON
    die(json_encode(array('message' => 'User added')));
}
else {
    //affiche un message d'erreur formaté en JSON
    die(json_encode(array('message' => 'User not added')));
}
?>