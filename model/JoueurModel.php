<?php
    require_once '../connection/connection.php';

    header('Content-Type:application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

    $con = getDBCon();

    function createJoueur($licence, $nom, $prenom, $taille, $poids, $date_naissance, $statut, $commentaire)
    {
        global $con;
        $stmt = $con->prepare("INSERT INTO Joueur (Licence, Nom, Prénom, Taille, Poids, Date_Naissance, Statut, Commentaire) VALUES (:licence, :nom, :prenom, :taille, :poids, :date_naissance, :statut, :commentaire)");
        $stmt->bindParam(':licence', $licence);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':taille', $taille);
        $stmt->bindParam(':poids', $poids);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':commentaire', $commentaire);
        return $stmt->execute();
    }

    function getAllJoueurs()
    {
        global $con;
        $stmt = $con->prepare("SELECT ID_Joueur, Licence, Nom, Prénom, Taille, Poids, Date_Naissance, Statut FROM Joueur");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getJoueur($id)
    {
        global $con;   
        $stmt = $con->prepare("SELECT * FROM Joueur WHERE ID_Joueur = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateJoueur($licence, $nom, $prenom, $taille, $poids, $date_naissance, $statut, $commentaire, $id)
    {
        global $con;
        $stmt = $con->prepare("UPDATE Joueur SET Licence = :licence, Nom = :nom, Prénom = :prenom, Taille = :taille, Poids = :poids, Date_Naissance = :date_naissance, Statut = :statut, Commentaire = :commentaire WHERE ID_Joueur = :id");
        $stmt->bindParam(':licence', $licence);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':taille', $taille);
        $stmt->bindParam(':poids', $poids);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Supprime un joueur de la base de données.
     *
     * @param int $id L'identifiant du joueur à supprimer.
     * @return bool Retourne true si la suppression a réussi, false sinon.
     */
    function deleteJoueur($id)
    {
        global $con;
        $stmt = $con->prepare("DELETE FROM Joueur WHERE ID_Joueur = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    function aDejaJouer($id)
    {
        global $con;
        $stmt = $con->prepare("SELECT * FROM Selection WHERE ID_Joueur = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? true : false;
    }

    function getActiveJoueurs()
    {
        global $con;
        $stmt = $con->prepare("SELECT ID_Joueur, Nom, Prénom FROM Joueur WHERE Statut = 'actif'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
?>