<?php
//Fonction pour récupérer les informations de l'utilisateur à partir de son id
function getUtilisateur($id){
    $sql = "SELECT * FROM utilisateur WHERE id = :id";
    $stmt = $connex->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $result;
}