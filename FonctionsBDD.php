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
//fonction pour récupérer toutes les informations de touts les produits en stock + les informations du produits pour les produits qui sont en stock
function getAllStock($connex){
    $sql = "SELECT * FROM stock 
        INNER JOIN produit ON stock.stock_barcode = produit.produit_barcode
        WHERE stock.stock_quantite > 0";
    $stmt = $connex->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    if(empty($infosProduit)){
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
        $conso_utilisateur=false;
    }
    else {
        $utilisateur=true;
        $idUser=$infosUtilisateur['utilisateur_id'];
        //tester si l'utilisateur a assez de consommations
        $prix=$quantite*$infosProduit['produit_prix_vente']/0.8;
        if($infosUtilisateur['utilisateur_conso']<$prix){
            
        }
        else {
            $conso_utilisateur=true;
        }
    }
    
    //si toutes les conditions sont remplies, alors on peut ajouter la vente
    if($produit && $stock && $utilisateur && $conso_utilisateur){
        $sql = "INSERT INTO vente (vente_refuser, vente_refproduit, vente_date, vente_quantite) VALUES (:utilisateur, :barcode, :horodatage, :quantite) RETURNING vente_id";
        $stmt = $connex->prepare($sql);
        $stmt->bindValue(':utilisateur', $idUser);
        $stmt->bindValue(':barcode', $barcode);
        $stmt->bindValue(':quantite', $quantite);
        //ajout de la date de la vente : timestamp
        $stmt->bindValue(':horodatage', date('Y-m-d H:i:s', time()));
        $stmt->execute();
        $result1 = $stmt->fetchColumn();
        //mise à jour du stock
        $newStock = $infosStock['stock_quantite']-$quantite;
        $result2= updateStock($barcode, $newStock, $connex);
        //mise à jour du nombre de consommations de l'utilisateur
        $nbconso = $infosUtilisateur['utilisateur_conso']-($quantite*$infosProduit['produit_prix_vente']/0.8);
        $sql = "UPDATE utilisateur SET utilisateur_conso = :nbconso WHERE utilisateur_rfid_uid = :id RETURNING utilisateur_id";
        $stmt = $connex->prepare($sql);
        $stmt->bindValue(':id', $user);
        $stmt->bindValue(':nbconso', $nbconso);
        $stmt->execute();
        $result3 = $stmt->fetchColumn();
        //test si toutes les requêtes ont été exécutées
        if (!empty($result1) && !empty($result2) && !empty($result3)) {
            $result = array("erreur"=>false, "produit"=>$produit, "stock"=>$stock, "utilisateur"=>$utilisateur, "conso"=>$conso_utilisateur, "message"=>"sale added");
        }
        else {
            $result = array("erreur"=>true, "produit"=>$produit, "stock"=>$stock, "utilisateur"=>$utilisateur, "conso"=>$conso_utilisateur, "message"=>"Error while adding sale");
        }
    }
    //sinon on retourne les erreurs
    else{
        $result = array("erreur"=>true, "produit"=>$produit, "stock"=>$stock, "utilisateur"=>$utilisateur, "conso"=>$conso_utilisateur, "message"=>"Error while adding sale");
    }
    return $result;
}
function getAllSales($connex){
    $sql = "SELECT * FROM vente 
        INNER JOIN utilisateur ON vente.vente_refuser = utilisateur.utilisateur_id
        INNER JOIN produit ON vente.vente_refproduit = produit.produit_barcode
        ORDER BY vente.vente_date DESC LIMIT 10";
    $stmt = $connex->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
//fonction pour modifier le nombre de consommations d'un utilisateur
function updateConso($id, $nbconso, $connex){
    $sql = "UPDATE utilisateur SET utilisateur_conso = :nbconso WHERE utilisateur_rfid_uid = :id RETURNING utilisateur_id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nbconso', $nbconso);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result;
}
?>