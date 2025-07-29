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

    /**-------------------------------------------------------------------------*/
    /**
     * Constructor. Initializes the database connection if not already set.
     *
     * @param PDO|null $db Optional PDO connection object.
     */
    /**-------------------------------------------------------------------------*/
    public function __construct(?PDO $db = null)
    {
        /**
         * Check for DB Connection or Create It:
         * - Assign to static $db property
         */
        if ($db !== null) {
            self::$db = $db;
        } else {
            throw new Exception('Model could not connect to DB!');
        }
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Sets the PDO database connection for all models.
     * This should typically be called once at application bootstrap.
     *
     * @param PDO $db The PDO database connection object.
     */
    /**-------------------------------------------------------------------------*/
    public static function setDb(PDO $db): void
    {
        self::$db = $db;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Get All Records
     */
    /**-------------------------------------------------------------------------*/
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
    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Save
     * 
     * Saves the current Model instance to the DB
     * 
     * @param void
     * @property array $props Object model properties
     */
    /**-------------------------------------------------------------------------*/
    public function save(){

        /**
         * TODO: Validate DB and Table
         */

        /**
         * Get properties from Model
         */
        self::getProps();
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Utility Method: Get Properties from current model instance
     * 
     * @property void
     * @return array Assoc Array of property key => values
     */
    /**-------------------------------------------------------------------------*/
    protected function getProps(): array {
        /**
         * @var array $results
         */
        $results = get_object_vars($this);

        var_dump($results);
        /**
         * Return results
         */
        return $results;
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * Utility Method: Fill
     * 
     * @param array $data Assoc array of user data
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    protected function fill(array $data): void {
        /**
         * Check if Array property exists and populate in Object
         */
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    
}