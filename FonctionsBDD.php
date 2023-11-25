<?php
//Fonction pour récupérer les informations de l'utilisateur à partir de son id
function getUtilisateur($id, $connex){
    $sql = "SELECT * FROM utilisateur WHERE utilisateur_rfid_uid = :id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
//fonction test qui ajoute un utilisateur
function ajouterUtilisateur($nom, $prenom, $id, $connex){
    $sql = "INSERT INTO utilisateur (utilisateur_rfid_uid, utilisateur_nom, utilisateur_prenom) VALUES (:id, :nom, :prenom) RETURNING utilisateur_id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':prenom', $prenom);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}




?>