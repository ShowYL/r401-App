<?php

/**
 * Class Connection
 *
 * This class handles the connection to a MySQL database using PDO.
 */
class Connection {

    /**
     * @var PDO|null $conn The PDO connection instance.
     */
    private $conn;

    /**
     * @var string $servername The server name for the database connection.
     */
    private $servername = "mysql-lestitansdesete.alwaysdata.net"; // "localhost";

    /**
     * @var string $username The username for the database connection.
     */
    private $username = "385432"; // "root";

    /**
     * @var string $password The password for the database connection.
     */
    private $password = "\$iutinfo";

    /**
     * @var string $dbname The database name.
     */
    private $dbname = "lestitansdesete_bdapp";

    /**
     * Connection constructor.
     *
     * Initializes the database connection using the provided credentials.
     * Sets the PDO error mode to exception.
     *
     * @throws PDOException if the connection fails.
     */
    public function __construct() {
        try {
            $dsn = "mysql:host=$this->servername;dbname=$this->dbname;charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("La connexion a échoué: " . $e->getMessage());
        }
    }

    /**
     * Get the PDO connection instance.
     *
     * @return PDO|null The PDO connection instance.
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * Close the PDO connection.
     */
    public function closeConnection() {
        $this->conn = null;
    }

}

?>