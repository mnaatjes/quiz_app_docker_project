<?php
    /**
     * Declare Namespaces
     */
    namespace mnaatjes\DataAccess;
    use PDO;
    use Exception;
    use mnaatjes\DataAccess\Database;

    /**
     * ORM: 
     * 
     * @version 1.2.1
     * 
     * @since 1.0.0:
     *  - Created
     * 
     * 
     * @since 1.1.0:
     *  - Pulled from quiz_app and moved to data-access repo
     *  - Added Namespace
     * 
     * @since 1.2.0:
     * - Added Create Method
     * - Added Delete, columnExists, query, update, findOne, findColumns, and count methods
     * 
     * @since 1.2.1:
     * - Modified find() method to include LIMIT and ORDER BY parameters
     * - Modified findOne() method to accept parameter change of find()
     * 
     */
    class ORM {
        /**
         * Database Object Instance
         */
        protected PDO $db;

        /**
         * Count of number of instantiations of ORM Class
         * @static
         * @var int $instanceCount Default = 0
         */
        private static int $instanceCount = 0;
        
        /**-------------------------------------------------------------------------*/
        /**
         * Constructor
         * 
         * @param Database $db_instance
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(Database $db_instance){
            /**
             * Define Database Instance Property of ORM Object
             */
            $this->db = $db_instance->getConnection();
            // Increment ORM Instance Count
            self::$instanceCount++;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD: Find
         * 
         * @param string $table_name
         * @param array $conditions
         * @param array $columns
         * @param array $orderBy Associative Array of columnName => ASC | DESC, Default empty array
         * @param int $limit Default 0 meaning no LIMIT
         * @param string $fetchMethod Default = fetchAll
         * @param string $fetchMethod Default = PDO::FETCH_ALL
         */
        /**-------------------------------------------------------------------------*/
        public function find(string $table_name, array $conditions=[], array $orderBy=[], $limit=0, array $columns=["*"], $fetchMethod="fetchAll", $fetchStyle=PDO::FETCH_ASSOC){
            /**
             * Form SQL:
             * - Apply columns
             * - Apply conditions
             */
            $sql = "SELECT " . implode(", ", $columns) . " FROM " . $table_name;

            /**
             * Generate WHERE clause:
             * - Determine if $conditions
             * - Bind Conditions
             */
            $whereClause = [];
            $bindings    = [];

            if(!empty($conditions)){
                // Form where clause with bindings
                foreach($conditions as $condition){
                    // Validate Condition Array
                    if(!is_array($condition)){
                        throw new Exception("Unable to process Condition in find(). Condition is NOT an array!");
                    }
                    
                    // Determine array key format: assoc. v indexed
                    if(array_keys($condition) != range(0, count($condition) - 1)){
                        /**
                         * Conditions array is associative array:
                         * - Collect keys
                         * - Collect values
                         * - Define default operator
                         * - Generate WHERE clause
                         * - Assign value bindings
                         */
                        // Loop and collect keys and values
                        $keys   = array_keys($condition);
                        $values = array_values($condition);

                        // Assign default operator
                        $operator = "=";

                        // Assemble WHERE clause and bindings
                        for($i = 0; $i < count($keys); $i++){
                            $whereClause[]  = $keys[$i] . " " . $operator . " ?";
                            $bindings[]     = $values[$i];
                        }

                    } else {
                        /**
                         * Condition array is NOT an associative array
                         */
                        if(count($condition) === 2){
                            // Determine count 2 (non-assoc array) to inject equals operator
                            list($column, $value) = $condition;

                            // Assign default operator
                            $operator = "=";

                        } elseif(count($condition) === 3){
                            // Determine if length 3 and normal list
                            list($column, $operator, $value) = $condition;
                        }

                        // Assemble WHERE clause and bindings
                        $whereClause[]  = $column . " " . $operator . " ?";
                        $bindings[]     = $value;
                    }
                }

                /**
                 * Append WHERE Clause
                 */
                $sql .= " WHERE " . implode(" AND ", $whereClause);
            }

            /**
             * Append ORDER BY
             */
            if(!empty($orderBy)){
                // Grab Values
                $orderClause = [];
                foreach($orderBy as $column => $direction){
                    $orderClause[] = $column . " " . $direction;
                }

                // Implode and Append
                $sql .= " ORDERY BY " . implode(", ", $orderClause);
            }

            /**
             * Append LIMIT
             */
            if($limit > 0){
                // Append
                $sql .= " LIMIT " . $limit;
            }
        
            /**
             * Prepare Statement
             * Set Bindings
             * Execute
             */
            $stmt = $this->db->prepare($sql);
            $stmt->execute($bindings);
            
            /**
             * Determine Mode of Fetch and type of Records
             */
            $result = call_user_func_array([$stmt, $fetchMethod], [$fetchStyle]);
            return $result;
        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * CRUD Method: Find One: Returns one (first) record encountered by Conditions
         * 
         * @uses find()
         */
        /**-------------------------------------------------------------------------*/
        public function findOne(string $table_name, array $conditions, array $columns=["*"]){
            // Use find() method
            return $this->find($table_name, $conditions, [], 1, $columns);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD Method: Select Columns to retrieve
         */
        /**-------------------------------------------------------------------------*/
        public function findColumns(string $table_name, array $columns){
            return $this->find($table_name, [], $columns);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD Method: Create (INSERT) Single Table Entry
         */
        /**-------------------------------------------------------------------------*/
        public function create(string $table_name, array $data){
            /**
             * Get columns
             * Get placeholders
             * Write SQL Statement
             */
            $columns        = implode(", ", array_keys($data));
            $placeholders   = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO " . $table_name . " ( " .$columns . " ) VALUES ( " . $placeholders . ")";
            
            /**
             * Try: Prepare and Execute
             * Catch: Exception
             * Return: Last insert ID / false
             */
            try {
                // Prepare Statement
                $stmt = $this->db->prepare($sql);

                // Bind Params
                foreach($data as $key => &$value){
                    $stmt->bindParam(":" . $key, $value);
                }

                // Execute and Return
                $stmt->execute();
                return $this->db->lastInsertId();

            } catch (Exception $e){
                // Return false on failure to execute
                return false;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update Record
         */
        /**-------------------------------------------------------------------------*/
        public function update(string $table_name, array $data, array $conditions){
            /**
             * @var array setClause
             */
            $setClause = [];

            /**
             * @var array bindings Placeholder bindings
             */
            $bindings = [];

            // Get setClause and Bindings
            foreach ($data as $column => $value) {
                $setClause[]    = $column . " = ?";
                $bindings[]     = $value;
            }

            /**
             * @var array $whereClause
             */
            $whereClause = [];

            // Check if $conditions array of arrays
            if(array_keys($conditions) !== range(0, count($conditions) - 1)){
                $conditions = [$conditions];
            }

            // Build whereClause
            foreach($conditions as $condition){
                // Validate Condition Array
                if(!is_array($condition)){
                    throw new Exception("Unable to process Condition in Update(). Condition is NOT an array!");
                }

                // Determine array key format: assoc. v indexed
                if(array_keys($condition) != range(0, count($condition) - 1)){
                    /**
                     * Conditions array is associative array:
                     * - Collect keys
                     * - Collect values
                     * - Define default operator
                     * - Generate WHERE clause
                     * - Assign value bindings
                     */
                    // Loop and collect keys and values
                    $keys   = array_keys($condition);
                    $values = array_values($condition);

                    // Assign default operator
                    $operator = "=";

                    // Assemble WHERE clause and bindings
                    for($i = 0; $i < count($keys); $i++){
                        $whereClause[]  = $keys[$i] . " " . $operator . " ?";
                        $bindings[]     = $values[$i];
                    }

                } else {
                    /**
                     * Condition array is NOT an associative array
                     */
                    if(count($condition) === 2){
                        // Determine count 2 (non-assoc array) to inject equals operator
                        list($column, $value) = $condition;

                        // Assign default operator
                        $operator = "=";

                    } elseif(count($condition) === 3){
                        // Determine if length 3 and normal list
                        list($column, $operator, $value) = $condition;
                    }

                    // Assemble WHERE clause and bindings
                    $whereClause[]  = $column . " " . $operator . " ?";
                    $bindings[]     = $value;
                }
            }

            /**
             * Build SQL
             */
            $sql = "UPDATE " . $table_name . " SET " . implode(", ", $setClause) . " WHERE " . implode(" AND ", $whereClause);
            /**
             * Try: Execute with Bindings
             */
            try {
                // Prepare and Execute
                $stmt = $this->db->prepare($sql);
                $stmt->execute($bindings);

                // Return
                // Return number of affected rows
                return $stmt->rowCount();

            } catch(Exception $e){
                // Return false on failure to execute
                return false;
            }

            // Return Default
            return false;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Delete Record
         */
        /**-------------------------------------------------------------------------*/
        public function delete(string $table_name, array $conditions){
            /**
             * @var array bindings Placeholder bindings
             */
            $bindings = [];

            /**
             * @var array $whereClause
             */
            $whereClause = [];

            // Check if $conditions array of arrays
            if(array_keys($conditions) !== range(0, count($conditions) - 1)){
                $conditions = [$conditions];
            }
            
            // Build whereClause
            foreach($conditions as $condition){
                // Validate Condition Array
                if(!is_array($condition)){
                    throw new Exception("Unable to process Condition in Delete(). Condition is NOT an array!");
                }
                // Determine array key format: assoc. v indexed
                if(array_keys($condition) != range(0, count($condition) - 1)){
                    /**
                     * Conditions array is associative array:
                     * - Collect keys
                     * - Collect values
                     * - Define default operator
                     * - Generate WHERE clause
                     * - Assign value bindings
                     */
                    // Loop and collect keys and values
                    $keys   = array_keys($condition);
                    $values = array_values($condition);

                    // Assign default operator
                    $operator = "=";

                    // Assemble WHERE clause and bindings
                    for($i = 0; $i < count($keys); $i++){
                        $whereClause[]  = $keys[$i] . " " . $operator . " ?";
                        $bindings[]     = $values[$i];
                    }

                } else {
                    /**
                     * Condition array is NOT an associative array
                     */
                    if(count($condition) === 2){
                        // Determine count 2 (non-assoc array) to inject equals operator
                        list($column, $value) = $condition;

                        // Assign default operator
                        $operator = "=";

                    } elseif(count($condition) === 3){
                        // Determine if length 3 and normal list
                        list($column, $operator, $value) = $condition;
                    }

                    // Assemble WHERE clause and bindings
                    $whereClause[]  = $column . " " . $operator . " ?";
                    $bindings[]     = $value;
                }
            }

            /**
             * Build SQL
             */
            $sql = "DELETE FROM " . $table_name . " WHERE " . implode(" AND ", $whereClause);
            var_dump($sql);

            /**
             * Try: Execute with Bindings
             */
            try {
                // Prepare and Execute
                $stmt = $this->db->prepare($sql);
                $stmt->execute($bindings);

                // Return
                // Return number of affected rows
                return $stmt->rowCount();

            } catch(Exception $e){
                // Return false on failure to execute
                return false;
            }

            // Return Default
            return false;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Utility Method: Count Number of Records that match Conditions
         */
        /**-------------------------------------------------------------------------*/
        public function count(string $table_name, array $conditions=[]): int|bool{
            // Declare Properties
            $bindings    = [];
            $whereClause = [];

            // Check if $conditions array of arrays
            if(array_keys($conditions) !== range(0, count($conditions) - 1)){
                $conditions = [$conditions];
            }
            
            // Build whereClause
            foreach($conditions as $condition){
                // Validate Condition Array
                if(!is_array($condition)){
                    throw new Exception("Unable to process Condition in Delete(). Condition is NOT an array!");
                }
                // Determine array key format: assoc. v indexed
                if(array_keys($condition) != range(0, count($condition) - 1)){
                    /**
                     * Conditions array is associative array:
                     * - Collect keys
                     * - Collect values
                     * - Define default operator
                     * - Generate WHERE clause
                     * - Assign value bindings
                     */
                    // Loop and collect keys and values
                    $keys   = array_keys($condition);
                    $values = array_values($condition);

                    // Assign default operator
                    $operator = "=";

                    // Assemble WHERE clause and bindings
                    for($i = 0; $i < count($keys); $i++){
                        $whereClause[]  = $keys[$i] . " " . $operator . " ?";
                        $bindings[]     = $values[$i];
                    }

                } else {
                    /**
                     * Condition array is NOT an associative array
                     */
                    if(count($condition) === 2){
                        // Determine count 2 (non-assoc array) to inject equals operator
                        list($column, $value) = $condition;

                        // Assign default operator
                        $operator = "=";

                    } elseif(count($condition) === 3){
                        // Determine if length 3 and normal list
                        list($column, $operator, $value) = $condition;
                    }

                    // Assemble WHERE clause and bindings
                    $whereClause[]  = $column . " " . $operator . " ?";
                    $bindings[]     = $value;
                }
            }

            /**
             * Form SQL
             */
            $sql = "SELECT COUNT(*) FROM " . $table_name;
            if (!empty($whereClause)) {
                // Append WHERE Clause if not empty
                $sql .= " WHERE " . implode(" AND ", $whereClause);
            }

            /**
             * Try: Execute and return int
             * Catch: Return bool
             */
            try {
                // Prepare and Execute
                $stmt = $this->db->prepare($sql);
                $stmt->execute($bindings);
                return $stmt->fetchColumn();

            } catch (Exception $e) {
                // Handle the exception
                return false;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Utility Method: Check if a Column Exists
         * 
         * @uses getenv()
         */
        /**-------------------------------------------------------------------------*/
        public function columnExists(string $table_name, string $column_name): bool{
            /**
             * Form SQL
             */
            $sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :dbName AND TABLE_NAME = :tableName AND COLUMN_NAME = :columnName LIMIT 1";
            /**
             * Try: Prepare, Bind, and Execute: bool
             * Catch: Return false
             */
            try {
                // Prepare
                $stmt = $this->db->prepare($sql);
                var_dump($sql);

                // Bind Values and Execute
                $stmt->bindValue(":dbName", getenv("DB_DATABASE"));
                $stmt->bindValue(":tableName", $table_name);
                $stmt->bindValue(":columnName", $column_name);
                $stmt->execute();

                // Return Boolean
                return $stmt->fetchColumn();

            } catch(Exception $e){
                // Return false
                return false;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Query Methods: Raw Query
         * 
         * @param string $sql SQL query string with "?" reprepsenting bindings
         * @param array $bindings Single Dimension Array with sequential values for bindings
         */
        /**-------------------------------------------------------------------------*/
        public function query(string $sql, array $bindings, string $fetchMethod="fetchAll", string $fetchStyle=PDO::FETCH_ASSOC){
            /**
             * Try: prepare and execute
             * Catch: Return Error
             */
            try {
                // Prepare
                $stmt = $this->db->prepare($sql);
                $stmt->execute($bindings);

                // Determine if "SELECT" statement
                $isSelect = stripos(trim($sql), 'SELECT') === 0;
                $isInsert = stripos(trim($sql), 'INSERT') === 0;

                // Return Values
                if($isSelect === true){
                    // Return Assoc Array with method
                    return call_user_func_array([$stmt, $fetchMethod], [$fetchStyle]);

                } elseif($isInsert === true){
                    // Return last created id
                    $this->db->lastInsertId();

                } else {
                    // Return Default: Record Affected
                    return $stmt->rowCount();
                }

            } catch (Exception $e){
                // log error
                // Return false
                return false;
            }
        }
        
        /**-------------------------------------------------------------------------*/
    }
?>