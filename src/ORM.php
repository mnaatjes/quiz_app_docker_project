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
     * - Added ShowTables() method
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
         * Admin Method: Show Tables
         * - Lists all tables in database
         */
        /**-------------------------------------------------------------------------*/
        public function showTables(){
            /**
             * Perform with try to catch exceptions
             */
            try {
                // Form sql and prepare
                $stmt = $this->db->prepare("SHOW TABLES");

                // Execute Query and return array
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_COLUMN);

            } catch(Exception $e){
                var_dump("Unable to list Database Tables! Error: " . $e);
            }
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
         * CRUD Method:
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
            var_dump($placeholders);

        }
    }
?>