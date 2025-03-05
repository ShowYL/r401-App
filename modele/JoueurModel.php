<?php
require_once 'db_lestitansdesete.php';

/**
 * Class JoueurModel
 *
 * This class represents the model for a player (joueur) in the application.
 * It is responsible for handling data operations related to players.
 *
 * @package Les-Titans-de-Sete\models
 */
class JoueurModel
{
    /**
     * @var PDO $conn The database connection instance.
     */
    private $conn;

    /**
     * JoueurModel constructor.
     * Initializes a new instance of the JoueurModel class.
     */
    public function __construct()
    {
        $db = new ConnectionBD();
        $this->conn = $db->getConnection();
    }

    /**
     * Create a new joueur (player) record in the database.
     *
     * @param string $licence The licence number of the joueur.
     * @param string $nom The last name of the joueur.
     * @param string $prenom The first name of the joueur.
     * @param float $taille The height of the joueur in meters.
     * @param float $poids The weight of the joueur in kilograms.
     * @param string $date_naissance The birth date of the joueur in YYYY-MM-DD format.
     * @param string $statut The status of the joueur (e.g., active, inactive).
     * @param string $commentaire Additional comments about the joueur.
     * @return bool Returns true on success, false on failure.
     */
    public function createJoueur($licence, $nom, $prenom, $taille, $poids, $date_naissance, $statut, $commentaire)
    {
        $stmt = $this->conn->prepare("INSERT INTO Joueur (Licence, Nom, Prénom, Taille, Poids, Date_Naissance, Statut, Commentaire) VALUES (:licence, :nom, :prenom, :taille, :poids, :date_naissance, :statut, :commentaire)");
        $stmt->bindParam(':licence', $licence);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':taille', $taille);
        $stmt->bindParam(':poids', $poids);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':commentaire', $commentaire);
        $result = $stmt->execute();
        return $result;
    }

    /**
     * Retrieve all players from the database.
     *
     * @return array An array of all players.
     */
    public function getAllJoueurs()
    {
        $stmt = $this->conn->prepare("SELECT ID_Joueur, Licence, Nom, Prénom, Taille, Poids, Date_Naissance, Statut FROM Joueur");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Retrieve a player's information based on their ID.
     *
     * @param int $id The ID of the player.
     * @return array|null The player's information as an associative array, or null if not found.
     */
    public function getJoueur($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Joueur WHERE ID_Joueur = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Updates the details of a joueur (player) in the database.
     *
     * @param string $licence The licence number of the joueur.
     * @param string $nom The last name of the joueur.
     * @param string $prenom The first name of the joueur.
     * @param float $taille The height of the joueur in meters.
     * @param float $poids The weight of the joueur in kilograms.
     * @param string $date_naissance The birth date of the joueur in YYYY-MM-DD format.
     * @param string $statut The status of the joueur.
     * @param string $commentaire Additional comments about the joueur.
     * @param int $id The unique identifier of the joueur.
     * @return bool Returns true on success, false on failure.
     */
    public function updateJoueur($licence, $nom, $prenom, $taille, $poids, $date_naissance, $statut, $commentaire, $id)
    {
        $stmt = $this->conn->prepare("UPDATE Joueur SET Licence = :licence, Nom = :nom, Prénom = :prenom, Taille = :taille, Poids = :poids, Date_Naissance = :date_naissance, Statut = :statut, Commentaire = :commentaire WHERE ID_Joueur = :id");
        $stmt->bindParam(':licence', $licence);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':taille', $taille);
        $stmt->bindParam(':poids', $poids);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
        return $result;
    }

    /**
     * Supprime un joueur de la base de données.
     *
     * @param int $id L'identifiant du joueur à supprimer.
     * @return bool Retourne true si la suppression a réussi, false sinon.
     */
    public function deleteJoueur($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM Joueur WHERE ID_Joueur = :id");
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
    }

    public function aDejaJouer($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Selection WHERE ID_Joueur = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? true : false;
    }

    public function getActiveJoueurs()
    {
    $stmt = $this->conn->prepare("SELECT ID_Joueur, Nom, Prénom FROM Joueur WHERE Statut = 'actif'");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
    }

    /**
     * Ferme la connexion à la base de données.
     *
     * Cette méthode est responsable de fermer la connexion à la base de données
     * pour libérer les ressources et s'assurer qu'aucune autre requête ne peut être exécutée
     * sur cette connexion.
     *
     * @return void
     */
    public function closeConnection()
    {
        $this->conn = null;
    }
}
?>