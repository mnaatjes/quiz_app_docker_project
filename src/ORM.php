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
     * @version 1.2.0
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
     * 
     * TODO: Test
     * TODO: Finish Crud Methods
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
         * @param string $fetchMethod Default = fetchAll
         * @param string $fetchMethod Default = PDO::FETCH_ALL
         */
        /**-------------------------------------------------------------------------*/
        public function find(string $table_name, array $conditions=[], array $columns=["*"], $fetchMethod="fetchAll", $fetchStyle=PDO::FETCH_ASSOC){
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
    }
?>