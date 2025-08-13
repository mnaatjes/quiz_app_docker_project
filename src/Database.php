<?php
/**
 * Database Singleton Class
 *
 * This class provides a single, shared instance of a PDO database connection,
 * with configuration data loaded from an external file.
 * 
 * @version 1.1.0 
 * @since 1.0.0 Created
 * @since 1.1.0:
 *  - Pulled from quiz_repo and made a dedicated package
 *  - Added __clone() method
 *  - Modified configuration variable retrieval:
 *      -> No more dependence on file path
 *      -> Maintains singleton 
 *      -> Modified getInstance() to get values using getenv()
 * 
 *  - DotEnv Class added to package to parse env variables
 *  - Added Namespace
 */
namespace mnaatjes\DataAccess;
use PDO;

class Database {
    /**
     * The single instance of the Database class.
     * @var Database|null
     */
    private static $instance = null;

    /**
     * The PDO database connection object.
     * @var PDO
     */
    private $connection;

    /**
     * Private constructor to prevent direct object creation.
     *
     * Loads database configuration from the specified file and
     * initializes a new PDO connection.
     *
     * @throws PDOException if the connection fails.
     */
    private function __construct() {
        /**
         * Load DB properties as ENV variables
         * Since 1.1.1 No longer loading from filepath! 
         */
        $host       = getenv("DB_HOST");
        $connection = getenv("DB_CONNECTION");
        $db         = getenv("DB_DATABASE");
        $user       = getenv("DB_USERNAME");
        $pass       = getenv("DB_PASSWORD");
        $charset    = getenv("DB_CHARSET");
        $options    = $_ENV["DB_OPTIONS"];

        $dsn = "$connection:host=$host;dbname=$db;charset=$charset";
        
        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // Throw a new exception with a more descriptive message.
            throw new \PDOException("Failed to connect to the database: " . $e->getMessage(), (int)$e->getCode());
        }
    }


    /**
     * Prevents the instance from being cloned
     * TODO: Test
     */
    //private function __clone(){}


    /**
     * Returns the single instance of the Database class.
     *
     * @return Database The single instance of the Database class.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Returns the PDO database connection object.
     *
     * @return PDO The PDO connection object.
     */
    public function getConnection() {
        return $this->connection;
    }
}
?>