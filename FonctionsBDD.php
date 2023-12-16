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
function ajouterUtilisateur($nom, $prenom, $id, $nbconso, $connex){
    $sql = "INSERT INTO utilisateur (utilisateur_rfid_uid, utilisateur_nom, utilisateur_prenom, utilisateur_conso) VALUES (:id, :nom, :prenom, :nbconso) RETURNING utilisateur_id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':prenom', $prenom);
    $stmt->bindValue(':nbconso', $nbconso);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
//fonction pour ajouter un produit
function ajoutProduit($barcode, $nom, $prixachat, $prixvente, $connex){
    $sql = "INSERT INTO produit (produit_barcode, produit_nom, produit_prix_achat, produit_prix_vente) VALUES (:barcode, :nom, :prixachat, :prixvente) RETURNING produit_barcode";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':barcode', $barcode);
    $stmt->bindValue(':nom', $nom);
    $stmt->bindValue(':prixachat', $prixachat);
    $stmt->bindValue(':prixvente', $prixvente);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
function updateProduit($barcode, $nom, $prixachat, $prixvente, $connex){
    //Vérifier si le produit est déja dans la table produit
    $infosProduit = getProduit($barcode, $connex);
    //si le résultat est vide, alors le produit n'est pas enregistré dans la table produit
    if(empty($infosProduit)){
        $result = false;
    }
    else{
        $sql = "UPDATE produit SET produit_nom = :nom, produit_prix_achat = :prixachat, produit_prix_vente = :prixvente WHERE produit_barcode = :barcode RETURNING produit_barcode";
        $stmt = $connex->prepare($sql);
        $stmt->bindValue(':barcode', $barcode);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prixachat', $prixachat);
        $stmt->bindValue(':prixvente', $prixvente);
        $stmt->execute();
        $result = $stmt->fetchColumn();
    }
    return $result;
}
//fonction pour récupérer les informations d'un produit à partir de son barcode
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
//fonction pour récupérer les informations d'un produit à partir de son barcode
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
function addVente($user, $barcode, $quantite, $connex){
    //Etape 1 : vérifier si le produit est enregistré dans la table produit
    $infosProduit = getProduit($barcode, $connex);
    //si le résultat est vide, alors le produit n'est pas enregistré dans la table produit
    if(!empty($infosProduit)){
        $produit=false;
    }
    else {
        $produit=true;
    }
    //Etape 2 : vérifier si le produit est enregistré dans la table stock
    $infosStock = getStock($barcode, $connex);
    //si le résultat est vide, alors le produit n'est pas enregistré dans la table stock
    if(empty($infosStock)){
        $stock=false;
    }
    //tester si la quantité demandée est disponible
    else if($infosStock['stock_quantite']<$quantite){
        $stock=false;
    }
    else {
        $stock=true;
    }
    //Etape 3 : vérifier si l'utilisateur est enregistré dans la table utilisateur
    $infosUtilisateur = getUtilisateur($user, $connex);
    //si le résultat est vide, alors l'utilisateur n'est pas enregistré dans la table utilisateur
    if(empty($infosUtilisateur)){
        $utilisateur=false;
    }
    else {
        $utilisateur=true;
    }
    //si toutes les conditions sont remplies, alors on peut ajouter la vente
    if($produit && $stock && $utilisateur){
        $sql = "INSERT INTO vente (vente_refuser, vente_refproduit, vente_date, vente_quantite) VALUES (:utilisateur, :barcode, :quantite) RETURNING vente_id";
        $stmt = $connex->prepare($sql);
        $stmt->bindValue(':utilisateur', $user);
        $stmt->bindValue(':barcode', $barcode);
        $stmt->bindValue(':quantite', $quantite);
        //ajout de la date de la vente : timestamp
        $stmt->bindValue(':date', time());
        $stmt->execute();
        $result = $stmt->fetchColumn();
    }
    //sinon on retourne les erreurs
    else{
        $result = array("erreur"=>true, "produit"=>$produit, "stock"=>$stock, "utilisateur"=>$utilisateur);
    }
    return $result;
}
?>