<?php
//Fonction pour récupérer les informations de l'utilisateur à partir de son id
function getUtilisateur($id, $connex){
    $sql = "SELECT * FROM utilisateur WHERE utilisateur_rfid_uid = :id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
//fonction pour ajouter un utilisateur
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
//fonction pour ajouter un produit
function ajoutProduit($barecode, $nom, $prixachat, $prixvente, $connex){
    $sql = "INSERT INTO produit (produit_barecode, produit_nom, produit_prix_achat, produit_prix_vente) VALUES (:barecode, :nom, :prixachat, :prixvente) RETURNING produit_barecode";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':barecode', $barecode);
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':prixachat', $prixachat);
    $stmt->bindValue(':prixvente', $prixvente);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
?>