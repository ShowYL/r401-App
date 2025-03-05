<?php
require_once '../connection/connection.php';

/**
 * Class SelectionModel
 *
 * This class represents the model for a selection (selection) in the application.
 * It is responsible for handling data operations related to selection.
 *
 * @package Les-Titans-de-Sete\models
 */
class SelectionModel
{
    /**
     * @var PDO $conn The database connection object.
     */
    private $conn;

    /**
     * Constructor initializes the database connection.
     */
    public function __construct()
    {
        $db = new Connection();
        $this->conn = $db->getConnection();
    }

    /**
     * Creates a new selection record in the database.
     *
     * @param int $idJoueur The ID of the player.
     * @param int $idMatch The ID of the match.
     * @param bool $titulaire Whether the player is a starter.
     * @param string $poste The position of the player.
     * @return bool Returns true on success, false on failure.
     */
    public function createSelection($idJoueur, $idMatch, $titulaire, $poste)
    {
        $stmt = $this->conn->prepare("INSERT INTO Selection (ID_Joueur, ID_Match, Titulaire, Poste) VALUES (:ID_Joueur, :ID_Match, :Titulaire, :Poste)");
        $stmt->bindParam(':ID_Joueur', $idJoueur);
        $stmt->bindParam(':ID_Match', $idMatch);
        $stmt->bindParam(':Titulaire', $titulaire);
        $stmt->bindParam(':Poste', $poste);
        return $stmt->execute();
    }

    /**
     * Retrieves all selection records from the database.
     *
     * @return array An associative array of all selection records.
     */
    public function getAllSelection()
    {
        $stmt = $this->conn->prepare("SELECT ID_Selection, ID_Joueur, ID_Match, Titulaire, Poste FROM Selection");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a specific selection record by ID.
     *
     * @param int $id The ID of the selection.
     * @return array An associative array of the selection record.
     */
    public function getSelection($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Selection WHERE ID_Selection = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates an existing selection record in the database.
     *
     * @param int $idJoueur The ID of the player.
     * @param int $idMatch The ID of the match.
     * @param bool $titulaire Whether the player is a starter.
     * @param string $poste The position of the player.
     * @return bool Returns true on success, false on failure.
     */
    public function updateSelection($idJoueur, $idMatch, $titulaire, $poste, $note)
    {
        $stmt = $this->conn->prepare("UPDATE Selection SET ID_Joueur = :idJoueur, ID_Match = :idMatch, Titulaire = :titulaire, Poste = :poste, Note = :note WHERE ID_Match = :idMatch AND ID_Joueur = :idJoueur");
        $stmt->bindParam(':idJoueur', $idJoueur);
        $stmt->bindParam(':idMatch', $idMatch);
        $stmt->bindParam(':titulaire', $titulaire);
        $stmt->bindParam(':poste', $poste);
        $stmt->bindParam(':note', $note);
        return $stmt->execute();
    }

    public function deleteSelectionByPlayerAndMatch($idJoueur, $idMatch)
    {
        $stmt = $this->conn->prepare("DELETE FROM Selection WHERE ID_Joueur = :idJoueur AND ID_Match = :idMatch");
        $stmt->bindParam(':idJoueur', $idJoueur);
        $stmt->bindParam(':idMatch', $idMatch);
        return $stmt->execute();
    }

    public function getSelectionByPlayerAndMatch($joueurId, $matchId)
    {
        $stmt = $this->conn->prepare("
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

    /**
     * Deletes a selection record from the database.
     *
     * @param int $id The ID of the selection.
     * @return bool Returns true on success, false on failure.
     */
    public function deleteSelection($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Selection WHERE ID_Selection = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Retrieves the list of players for a given match.
     *
     * @param int $idMatch The ID of the match.
     * @return array An associative array containing the IDs of the players.
     */
    public function getPlayersByMatch($idMatch)
    {
        $stmt = $this->conn->prepare("SELECT ID_Joueur FROM Selection WHERE ID_Match = :id_match");
        $stmt->bindParam(':id_match', $idMatch);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieve the statistics of a player based on their ID.
     *
     * @param int $id The ID of the player.
     * @return array The player's statistics.
     */
    public function getPlayerStats($id)
    {
        $stmt = $this->conn->prepare("
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

    /**
     * Retrieve the number of consecutive selections for a player.
     *
     * @param int $id The ID of the player.
     * @return int The number of consecutive selections.
     */
    public function getConsecutiveSelections($id)
    {
        $stmt = $this->conn->prepare("
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

    /**
     * Retrieve the preferred position of a player based on the position they have played the most.
     *
     * @param int $id The ID of the player.
     * @return string The preferred position of the player.
     */
    public function getPreferredPosition($id_joueur)
    {
        $stmt = $this->conn->prepare("
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
    
    /**
     * Closes the database connection.
     */
    public function closeConnection()
    {
        $this->conn = null;
    }
}

?>