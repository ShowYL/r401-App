<?php
    require_once '../connection/connection.php';

    $con = getDBCon();

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

    function getPlayerStats($id)
    {
        global $con;
        $stmt = $con->prepare("
            SELECT 
                COUNT(CASE WHEN Titulaire = 1 THEN 1 END) AS titularisations,
                COUNT(CASE WHEN Titulaire = 0 THEN 1 END) AS remplacements,
                AVG(Note) AS moyenne_evaluations,
                (SUM(CASE WHEN Résultat = 'Victoire' THEN 1 ELSE 0 END) / COUNT(*)) * 100 AS pourcentage_matchs_gagnes
            FROM Selection
            JOIN `Match` ON Selection.ID_Match = `Match`.ID_Match
            WHERE ID_Joueur = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getConsecutiveSelections($id)
    {
        global $con;
        $stmt = $con->prepare("
            SELECT COUNT(*) AS consecutive_selections
            FROM (
                SELECT 
                    ID_Joueur, 
                    Titulaire, 
                    LAG(Titulaire) OVER (ORDER BY Date_Match) AS previous_titulaire
                FROM Selection
                JOIN `Match` ON Selection.ID_Match = `Match`.ID_Match
                WHERE ID_Joueur = :id
            ) AS subquery
            WHERE Titulaire = 1 AND (previous_titulaire = 1 OR previous_titulaire IS NULL)
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['consecutive_selections'];
    }

    function getPreferredPosition($id_joueur)
    {
        global $con;
        $stmt = $con->prepare("
            SELECT Poste, COUNT(*) AS count
            FROM Selection
            WHERE ID_Joueur = :id
            GROUP BY Poste
            ORDER BY count DESC
            LIMIT 1
        ");
        $stmt->bindParam(':id', $id_joueur);
        $stmt->execute();
        $result= $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['Poste'];
        } else {
            error_log("No preferred position found for player ID: " . $id_joueur);
            return null;
        }
    }

?>