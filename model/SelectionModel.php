<?php
require_once '../connection/connection.php';

    $con = getDBCon();

    function createSelection($idJoueur, $idMatch, $titulaire, $poste)
    {
        global $con;
        $stmt = $con->prepare("INSERT INTO Selection (ID_Joueur, ID_Match, Titulaire, Poste) VALUES (:ID_Joueur, :ID_Match, :Titulaire, :Poste)");
        $stmt->bindParam(':ID_Joueur', $idJoueur);
        $stmt->bindParam(':ID_Match', $idMatch);
        $stmt->bindParam(':Titulaire', $titulaire);
        $stmt->bindParam(':Poste', $poste);
        return $stmt->execute();
    }

    function getAllSelection()
    {
        global $con;
        $stmt = $con->prepare("SELECT ID_Selection, ID_Joueur, ID_Match, Titulaire, Poste FROM Selection");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getSelection($id)
    {
        global $con;
        $stmt = $con->prepare("SELECT * FROM Selection WHERE ID_Selection = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateSelection($idJoueur, $idMatch, $titulaire, $poste, $note)
    {
        global $con;
        $stmt = $con->prepare("UPDATE Selection SET ID_Joueur = :idJoueur, ID_Match = :idMatch, Titulaire = :titulaire, Poste = :poste, Note = :note WHERE ID_Match = :idMatch AND ID_Joueur = :idJoueur");
        $stmt->bindParam(':idJoueur', $idJoueur);
        $stmt->bindParam(':idMatch', $idMatch);
        $stmt->bindParam(':titulaire', $titulaire);
        $stmt->bindParam(':poste', $poste);
        $stmt->bindParam(':note', $note);
        return $stmt->execute();
    }

    function deleteSelectionByPlayerAndMatch($idJoueur, $idMatch)
    {
        global $con;
        $stmt = $con->prepare("DELETE FROM Selection WHERE ID_Joueur = :idJoueur AND ID_Match = :idMatch");
        $stmt->bindParam(':idJoueur', $idJoueur);
        $stmt->bindParam(':idMatch', $idMatch);
        return $stmt->execute();
    }

    function getSelectionByPlayerAndMatch($joueurId, $matchId)
    {
        global $con;
        $stmt = $con->prepare("
        SELECT s.ID_Selection, s.ID_Match, s.ID_Joueur, s.Titulaire, s.Poste, s.Note, m.Date_Match 
        FROM Selection s, `Match` m
        WHERE s.ID_Match = m.ID_Match
        AND s.ID_Joueur = :joueurId AND s.ID_Match = :matchId
        ");
        $stmt->bindParam(':joueurId', $joueurId);
        $stmt->bindParam(':matchId', $matchId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   function deleteSelection($id)
    {
        global $con;
        $stmt = $con->prepare("DELETE FROM Selection WHERE ID_Selection = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    function getPlayersByMatch($idMatch)
    {
        global $con;
        $stmt = $con->prepare("SELECT ID_Joueur FROM Selection WHERE ID_Match = :id_match");
        $stmt->bindParam(':id_match', $idMatch);
        $stmt->execute();
        return $stmt->fetchAll();
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