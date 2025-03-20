<?php
require_once '../connection/connection.php';

    header('Content-Type:application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

    $con = getDBCon();

    function createMatch($date, $heure, $adversaire, $lieu, $resultat) {
        global $con;
        $stmt = $con->prepare("INSERT INTO `Match` (Date_Match, Heure_Match, Equipe_Adverse, Lieu, Résultat) VALUES (:date, :heure, :adversaire, :lieu, :resultat)");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':heure', $heure);
        $stmt->bindParam(':adversaire', $adversaire);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':resultat', $resultat);
        $result = $stmt->execute();
        return $result;
    }

    function getAllMatchs(){
        global $con;
        $stmt = $con->prepare("SELECT ID_Match, Date_Match, Heure_Match, Equipe_Adverse, Lieu, Résultat FROM `Match`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getMatch($id) {
        global $con;
        $stmt = $con->prepare("SELECT * FROM `Match` WHERE ID_Match = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateMatch($date, $heure, $adversaire, $lieu, $resultat, $id) {
        global $con;
        $stmt = $con->prepare("UPDATE `Match` SET Date_Match = :date, Heure_Match = :heure, Equipe_Adverse = :adversaire, Lieu = :lieu, Résultat = :resultat WHERE ID_Match = :id");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':heure', $heure);
        $stmt->bindParam(':adversaire', $adversaire);
        $stmt->bindParam(':lieu', $lieu);
        $stmt->bindParam(':resultat', $resultat);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

     function updateMatchResult($resultat, $id) {
        global $con;
        $stmt = $con->prepare("UPDATE `Match` SET Résultat = :resultat WHERE ID_Match = :id");
        $stmt->bindParam(':resultat', $resultat);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

     function deleteMatch($id) {
        global $con;
        $stmt = $con->prepare("DELETE FROM `Match` WHERE ID_Match = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    

    function getMatchStats() {
        global $con;
        $stmt = $con->prepare("SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN Résultat = 'Victoire' THEN 1 ELSE 0 END) AS won,
            SUM(CASE WHEN Résultat = 'Nul' THEN 1 ELSE 0 END) AS draw,
            SUM(CASE WHEN Résultat = 'Défaite' THEN 1 ELSE 0 END) AS lost
            FROM `Match`");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>