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
 *   
 * 
 * 
 * TODO: Fix how configuration file works
 * TODO: Add .env support
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
        // Path to the configuration file.
        $configPath = __DIR__ . '/../../app/config/db_config.php';
        
        // Check if the configuration file exists before including it.
        if (!file_exists($configPath)) {
            throw new \Exception("Database configuration file not found at: " . $configPath);
        }

        // Load the database configuration from the file.
        $config = require $configPath;
        
        // Extract connection details from the config array.
        $host = $config['host'];
        $db   = $config['db_name'];
        $user = $config['user'];
        $pass = $config['password'];
        $charset = $config['charset'];
        $options = $config['options'];

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
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