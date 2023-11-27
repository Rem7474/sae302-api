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
function ajoutProduit($barcode, $nom, $prixachat, $prixvente, $connex){
    $sql = "INSERT INTO produit (produit_barcode, produit_nom, produit_prix_achat, produit_prix_vente) VALUES (:barcode, :nom, :prixachat, :prixvente) RETURNING produit_barecode";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':barcode', $barcode);
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':prixachat', $prixachat);
    $stmt->bindValue(':prixvente', $prixvente);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
//fonction pour récupérer les informations d'un produit à partir de son barecode
function getProduit($barcode, $connex){
    $sql = "SELECT * FROM produit WHERE produit_barcode = :id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $barcode);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function addStock($barcode, $stock, $connex){
    //Etape 1 : vérifier si le produit est enregistré dans la table produit
    $infosProduit = getProduit($barcode, $connex);
    //si le résultat est vide, alors le produit n'est pas enregistré dans la table produit
    if(!empty($infosProduit)){
        $sql = "INSERT INTO stock (stock_barcode, stock_quantite) VALUES (:barcode, :quantite) RETURNING stock_id";
        $stmt = $connex->prepare($sql);
        $stmt->bindValue(':barcode', $barcode);
        $stmt->bindValue(':quantite', $stock);
        $stmt->execute();
        $result = $stmt->fetchColumn();
    }
    else{
        $result = false;
    }
    return $result;
}
//fonction pour récupérer les informations d'un produit à partir de son barecode
function getStock($barcode, $connex){
    $sql = "SELECT * FROM stock WHERE stock_barcode = :id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $barcode);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function updateStock($barcode, $stock, $connex){
    //Vérifier si le produit est déja dans la table stock
    $infosStock = getStock($barcode, $connex);
    //si le résultat est vide, alors le produit n'est pas enregistré dans la table stock
    if(empty($infosStock)){
        $result = false;
    }
    else{
        $sql = "UPDATE stock SET stock_quantite = :quantite WHERE stock_barcode = :barcode RETURNING stock_id";
        $stmt = $connex->prepare($sql);
        $stmt->bindValue(':barcode', $barcode);
        $stmt->bindValue(':quantite', $stock);
        $stmt->execute();
        $result = $stmt->fetchColumn();
    }
    return $result;
}
?>