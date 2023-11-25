<?php
//Fonction pour récupérer les informations de l'utilisateur à partir de son id
function getUtilisateur($id, $connex){
    $sql = "SELECT * FROM utilisateur WHERE id = :id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
//fonction test qui ajoute un utilisateur
function ajouterUtilisateur($nom, $prenom, $id, $connex){
    $sql = "INSERT INTO utilisateur (Utilisateur_RFIDUID, Utilisateur_Nom, Utilisateur_Prenom) VALUES (:id, :nom, :prenom)";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':prenom', $prenom);
    $stmt->execute();
}




?>