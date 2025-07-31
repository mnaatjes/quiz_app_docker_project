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
    public function __construct(?PDO $db = null){
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
     * CRUD Method: Get record by Primary Key and Load it into object
     * 
     * TODO: Confusing
     * TODO: Remove DB Interaction portion
     * TODO: Remove, redundant by static::fill()
     * @depreciated
     * @see static::fill
     */
    /**-------------------------------------------------------------------------*/
    public function load($id=null): bool{
        /**
         * Check for $id definition
         */
        if(is_null($this->{static::$p_key}) && is_null($id)){
            throw new Exception("Primary Key value is undefined!");
        } else if(!is_null($id)){
            $this->{static::$p_key} = $id;
        }
        /**
         * Perform Request
         */
        $results = $this->get();

        /**
         * Verify and return
         */
        if(empty($results)){
            throw new Exception("Unable to find record by provided id");
        }

        /**
         * Fill properties
         */
        $this->fill($results);

        /**
         * Return
         */
        return true;
    }


    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Get record by Primary Key
     * 
     * TODO: Redundant!
     * @see getById()
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
     * CRUD Method: Get record by Primary Key
     * 
     * TODO: Make static
     * TODO: Make protected
     */
    /**-------------------------------------------------------------------------*/
    public function getById($id){
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
        $stmt->execute([static::$p_key => $id]);
        
        /**
         * Return Record
         */
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Returns specific property by associated ID
     * 
     * TODO: Make static
     * TODO: Make protected
     */
    /**-------------------------------------------------------------------------*/
    public function getProp($id, $prop_name){
        /**
         * TODO: Validate $id
         */
        

        /**
         * Check $prop name exists
         */
        if(!property_exists($this, $prop_name)){
            throw new Exception("Unable to find ". $prop_name ." in Object!");
        }

        /**
         * Form Query
         */
        $result     = null;
        $sql        = "SELECT " . $prop_name . " FROM " . static::$table_name . " WHERE " . static::$p_key . " = :" . static::$p_key;
        $stmt       = static::$db->prepare($sql);
        /**
         * Bind P Key value
         */
        $stmt->bindParam(static::$p_key, $id);
        $stmt->execute();
        /**
         * Fetch Column and Validate Result
         */
        $result = $stmt->fetchColumn();

        /**
         * Return Property
         */
        return $result;

    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Get All Records
     * 
     * TODO: Make static
     * TODO: Make protected
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
     * CRUD Method: Get All Records
     * 
     * TODO: Make static
     * TODO: Make protected
     */
    /**-------------------------------------------------------------------------*/
    public function list(){
        return $this->getAll();
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Count by Properties
     * 
     * @return int Count from provided query
     */
    /**-------------------------------------------------------------------------*/
    public static function countByProps(array $properties=[]): ?int{
        /**
         * Evaluate Properties
         */
        if(empty($properties)){
            /**
             * Form Simple SQL
             */
            $sql = "SELECT COUNT(*) FROM ".static::$table_name;

        } else {
            /**
             * Form Clauses
             */
            foreach ($properties as $key => $value) {
                $conditions[] = "`{$key}` = :{$key}";
                $bindValues[":{$key}"] = $value;
            }

            $whereClause = implode(' AND ', $conditions);
            /**
             * Form SQL
             */
            $sql = "SELECT COUNT(*) FROM ".static::$table_name." WHERE ". $whereClause;
        }
        /**
         * Prepare Query
         */
        $stmt = static::$db->prepare($sql);
        
        foreach ($bindValues as $placeholder => $val) {
            // Determine the PDO parameter type
            $paramType = PDO::PARAM_STR; // Default to string
            if (is_int($val)) {
                $paramType = PDO::PARAM_INT;
            } elseif (is_bool($val)) {
                $paramType = PDO::PARAM_BOOL;
            } elseif (is_null($val)) {
                $paramType = PDO::PARAM_NULL;
            }
            $stmt->bindValue($placeholder, $val, $paramType);
        }

        $stmt->execute();

        /**
         * Execute and return result
         */
        return (int) $stmt->fetchColumn();
    }
    
    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Record Exists in table by ID
     * 
     * @param int $id
     * 
     * @return bool 
     */
    /**-------------------------------------------------------------------------*/
    protected function exists($id){
        /**
         * Form SQL
         */
        $sql    = "SELECT 1 FROM ".static::$table_name." WHERE ".static::$p_key." = :".static::$p_key." LIMIT 1";
        $stmt   = static::$db->prepare($sql);
        /**
         * Bind id
         */
        $stmt->bindParam(":".static::$p_key, $id, PDO::PARAM_INT);
        $stmt->execute();

        /**
         * Fetch and return boolean
         */
        return (bool)$stmt->fetch();
    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Record Exists in table by ID
     * 
     * @param int $f_key
     * @param string $f_key_name
     * 
     * @return bool 
     * 
     * TODO: Make static
     * TODO: Change param to assoc array for multiple foreign keys
     */
    /**-------------------------------------------------------------------------*/
    protected static function existsByFkey(array $f_keys){

    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Record Exists in table by ID
     * 
     * @param int $f_key
     * @param string $f_key_name
     * 
     * @return bool 
     * 
     * TODO: Make static
     * TODO: Change param to assoc array for multiple foreign keys
     */
    /**-------------------------------------------------------------------------*/
    public function _existsByFk($f_key, $f_key_name){
        /**
         * Form SQL
         */
        $sql    = "SELECT 1 FROM ".static::$table_name." WHERE ".$f_key_name." = :".$f_key." LIMIT 1";
        $stmt   = static::$db->prepare($sql);
        /**
         * Bind Properties
         */
        $stmt->bindParam(":".$f_key_name, $f_key, PDO::PARAM_INT);
        $stmt->execute();

        /**
         * Fetch and return boolean
         */
        return (bool)$stmt->fetch();
    }

    /**-------------------------------------------------------------------------*/
    /**
     * CRUD Method: Save
     * 
     * Saves the current Model instance to the DB
     * 
     * @param array $properties Columns and values to save to
     * @param ?int $id Primary Key Value for Update
     * 
     * @return bool|int Bool on unsuccessful / Update | Int on success, represents lastInsertID()
     * 
     */
    /**-------------------------------------------------------------------------*/
    protected static function save(array $properties, ?int $id=NULL): bool{
        /**
         * TODO: Check if empty
         */

        /**
         * Evaluate INSERT or UPDATE
         */
        if(!is_null($id)){
            /**
             * UPDATE
             */
            return static::update($id, $properties);
        } else {
            /**
             * INSERT
             */
            return static::insert($properties);
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
     * @return bool|int False on failure; Last insert ID on success
     * 
     */
    /**-------------------------------------------------------------------------*/
    protected static function insert(array $properties): bool {
        /**
         * Create Columns
         */
        $columns = implode(', ', array_keys($properties));
        $placeholders = ':' . implode(', :', array_keys($properties));

        /**
         * Form SQL Statement
         */
        $sql = "INSERT INTO " . static::$table_name . " ({$columns}) VALUES ({$placeholders})";
        $stmt = static::$db->prepare($sql);

        /**
         * Bind Properties
         */
        foreach ($properties as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        /**
         * Execute and evaluate result
         */
        $result = $stmt->execute();
        if ($result && static::$db->lastInsertId()) {
            $result = static::$db->lastInsertId();
        }

        /**
         * Return Default Boolean | ID of last insert
         */
        return $result;
    }

    /**-------------------------------------------------------------------------*/
    /**
     * DB Method: Updates an existing record into the database
     * 
     * @param int $id
     * @param array $properties The properties to update.
     * @return bool True on success, false on failure.
     */
    /**-------------------------------------------------------------------------*/
    protected static function update($id, array $properties): bool {
        /**
         * Render Fields
         */
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
        /**
         * Render SQL Statement
         */
        var_dump(static::$p_key);
        $sql    = "UPDATE " . static::$table_name . " SET {$setClause} WHERE `". static::$p_key."` = :".static::$p_key;
        $stmt   = self::$db->prepare($sql);

        /**
         * Assign properties and bind
         */
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
        $stmt->bindValue(':' . static::$p_key, $id, PDO::PARAM_INT);

        /**
         * Return boolean from update
         */
        return $stmt->execute();
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Utility Method: Get Properties from current model instance
     * 
     * @property void
     * @return array Assoc Array of property key => values
     * 
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