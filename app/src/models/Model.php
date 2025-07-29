<?php

/**
 * Abstract base class for all models.
 * Provides basic CRUD operations and database interaction.
 */
abstract class Model
{
    /**
     * @var PDO The PDO database connection object.
     */
    protected static $db;

    /**
     * @var string The name of the database table associated with the model.
     * Must be defined by concrete model classes.
     */
    protected static $table_name;

    /**
     * @var string The primary key column name. Defaults to 'id'.
     */
    protected static $p_key = 'id';

    /**
     * Constructor. Initializes the database connection if not already set.
     *
     * @param PDO|null $db Optional PDO connection object.
     */
    public function __construct(?PDO $db = null)
    {
        /**
         * Check for DB Connection or Create It:
         * - Assign to static $db property
         */
        if ($db !== null) {
            self::$db = $db;
        }
        var_dump($db);
    }

    /**
     * Sets the PDO database connection for all models.
     * This should typically be called once at application bootstrap.
     *
     * @param PDO $db The PDO database connection object.
     */
    public static function setDb(PDO $db): void
    {
        self::$db = $db;
    }

    /**
     * Query
     */
    public function getAll(): array {
        /**
         * TODO: Validate Connection
         * TODO: Table Name
         */

        /**
         * Form Query and Request Records
         */
        $records = [];
        $stmt    = self::$db->query("SELECT * FROM " . static::$table_name);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        /**
         * Return Assoc Array
         */
        return $records;
    }

    
}