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
    private static $db;

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
            static::$db = $db;
        } else {
            throw new Exception('Model could not connect to DB!');
        }
    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Get record by Primary Key
     */
    /**-------------------------------------------------------------------------*/
    public function get(){
        /**
         * Validate PKEY and Value
         */
        if(!isset(static::$p_key)){
            throw new Exception("Missing Primary Key definition!");
        }
        if(is_null($this->{static::$p_key})){
            throw new Exception("Primary Key: " . static::$p_key. " has no value!");
        }

        /**
         * Form query and execute
         */
        $sql = "SELECT * FROM " . static::$table_name . " WHERE ". static::$p_key ." = :id";
        $stmt = static::$db->prepare($sql);
        $stmt->execute([static::$p_key => $this->{static::$p_key}]);
        
        /**
         * Return Record
         */
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $stmt    = static::$db->query("SELECT * FROM " . static::$table_name);
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
    protected function save(): bool{

        /**
         * TODO: Validate DB and Table and PKEY
         */

        /**
         * Get properties from Model
         */
        $props = $this->getProps();

        /**
         * TODO: Check if empty
         */

        /**
         * Evaluate INSERT or UPDATE
         */
        if(isset($this->{static::$p_key})){
            /**
             * UPDATE
             */
            return $this->update($props);
        } else {
            /**
             * INSERT
             */
            return $this->insert($props);
        }

        /**
         * Return Default
         */
        return false;
    }
    /**-------------------------------------------------------------------------*/
    /**
     * DB Method: Inserts a new record into the database.
     *
     * @param array $properties The properties to insert.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    private function insert(array $properties): bool {
        $columns = implode(', ', array_keys($properties));
        $placeholders = ':' . implode(', :', array_keys($properties));

        $sql = "INSERT INTO " . static::$table_name . " ({$columns}) VALUES ({$placeholders})";
        $stmt = static::$db->prepare($sql);

        foreach ($properties as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $result = $stmt->execute();
        if ($result && static::$db->lastInsertId()) {
            $this->{static::$p_key} = static::$db->lastInsertId();
        }
        /**
         * Return Boolean
         */
        return $result;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * DB Method: Updates an existing record into the database
     * 
     * @param array $properties The properties to update.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    protected function update(array $properties): bool {
        $fields = [];
        foreach ($properties as $key => $_) {
            // Skip primary key
            if($key === static::$p_key){
                continue;
            } else {
                // Apply fields and placeholders
                $fields[] = "{$key} = :{$key}";
            }
        }
        /**
         * Collapse clauses into string
         */
        $setClause = implode(', ', $fields);

        $sql    = "UPDATE " . static::$table_name . " SET {$setClause} WHERE `" . static::$p_key . "` = :" . static::$p_key;
        $stmt   = self::$db->prepare($sql);

        foreach ($properties as $key => $value) {
            // Skip p key
            if($key === static::$p_key){
                continue;
            } else {
                // Bind Values
                $stmt->bindValue(':' . $key, $value);
            }
        }

        /**
         * Bind primary key to value
         */
        $stmt->bindValue(':' . static::$p_key, $this->{static::$p_key});

        /**
         * Return update result
         */
        return $stmt->execute();
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
        /**
         * Unset unneccessary keys
         * - Strip Primary Key
         * - Strip DB object
         */
        if(property_exists($this, 'p_key')){
            unset($results['p_key']);
        }
        if(property_exists($this, 'db')){
            unset($results['db']);
        }
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