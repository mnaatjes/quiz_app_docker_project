<?php
    /**
     * Declare Namespaces
     */
    namespace mnaatjes\DataAccess;
    use PDO;
    use Exception;
    use mnaatjes\DataAccess\Database;

	/**-------------------------------------------------------------------------*/
    /**
	 * ORM (Object-Relational Mapper)
	 *
	 * This class provides a simplified interface for interacting with a relational database. It abstracts
	 * common database operations such as C-R-U-D (Create, Read, Update, Delete) and raw query execution
	 * using PDO prepared statements to ensure security and prevent SQL injection.
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
     * @since 1.2.2:
     * - Fixed type in ORDER BY clause on find()
     * - Added join() method for INNER JOINS
	 * - Added phpdocs comments for most methods
     * 
	 * @since 1.2.3:
	 * - created parseConditions() to parse all types of condition formats
	 * - deployed parseConditions() to find()
	 * 
	 * @since 1.2.4:
	 * - deployed parseConditions() to all CRUD methods that will utilize it
	 * 
	 * @since 1.2.5: 
	 * - Unified parseConditions() with find() and update() to all use :placeholder and assoc array during binding
	 * - Generates specific placeholder name for duplicate entries
	 * - Prepends "set_:" to keys in SET Clause
	 * 
     * @version 1.2.5
     */
	/**-------------------------------------------------------------------------*/
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
		 * Constructs a new ORM instance, initializing the database connection and incrementing the instance count.
		 *
		 * This method is called automatically when a new object of the ORM class is created. It takes a database
		 * instance, extracts the PDO connection object from it, and stores it for use in other ORM methods.
		 * It also keeps track of how many ORM objects have been created.
		 *
		 * @param Database $db_instance The Database object containing the PDO connection.
		 * @return void
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
         * Finds and retrieves records from a specified table based on conditions, ordering, and limits.
         *
         * This method constructs a `SELECT` query, securely binding parameters to prevent SQL injection. It provides
         * flexible options for filtering, sorting, and limiting the results.
         *
         * @param string $table_name The name of the table to query.
         * @param array $conditions An array of conditions to filter the results. Conditions can be:
         * - An associative array: `['column_name' => 'value']`.
         * - An indexed array with two elements: `['column_name', 'value']` (defaults to `=` operator).
         * - An indexed array with three elements: `['column_name', 'operator', 'value']`.
         * - An array of the above formats to apply multiple conditions with `AND`.
         * @param array $orderBy An associative array for ordering the results (e.g., `['column_name' => 'DESC']`).
         * @param int $limit The maximum number of rows to return. A value of `0` means no limit.
         * @param array $columns An array of columns to select from the table. Defaults to `["*"]`.
         * @param string $fetchMethod The PDO fetch method to use (e.g., `"fetchAll"`, `"fetch"`).
         * @param int $fetchStyle The PDO fetch style constant (e.g., `PDO::FETCH_ASSOC`).
         * @return mixed The result of the query, which can be an array of rows, a single row, or `false` on an empty result or error.
         * @throws \Exception If a condition is not a valid array.
		 * 
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
			 * Collect WhereClause and Bindings
			 */
			$clauses 	 = $this->parseConditions($conditions);
			$whereClause = isset($clauses["whereClause"]) ? $clauses["whereClause"] : [];
			$bindings 	 = isset($clauses["bindings"]) ? $clauses["bindings"] : [];

			/**
			 * Assign WHERE clause if present
			 */
			if(!empty($whereClause)){
				// Append
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
                $sql .= " ORDER BY " . implode(", ", $orderClause);
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
			try {
				$stmt = $this->db->prepare($sql);
				$stmt->execute($bindings);
				
				/**
				 * Determine Mode of Fetch and type of Records
				 */
				$result = call_user_func_array([$stmt, $fetchMethod], [$fetchStyle]);
				return $result;
			} catch (Exception $e){
				// Return empty record
				return NULL;
			}
        }
        
        /**-------------------------------------------------------------------------*/
        /**
		 * Finds and retrieves a single record from a specified table that matches the given conditions.
		 *
		 * This is a convenience method that acts as a wrapper for the `find()` method, automatically
		 * applying a limit of 1 to ensure only one result is returned. This is useful for fetching
		 * a specific, unique record (e.g., by a primary key).
		 *
		 * @param string $table_name The name of the table to query.
		 * @param array $conditions An array of conditions to filter the results. This method requires a valid condition to be passed.
		 * @param array $columns An array of columns to select from the table. Defaults to `["*"]`.
		 * @return mixed The single record (as an associative array) if found, or `false` if no record matches the conditions.
		 * @throws \Exception If a condition is not a valid array.
		 * @see find()
         */
        /**-------------------------------------------------------------------------*/
        public function findOne(string $table_name, array $conditions, array $columns=["*"]){
            // Use find() method
			return $this->find($table_name, $conditions, [], 0, $columns, "fetch");
        }

        /**-------------------------------------------------------------------------*/
        /**
		 * Finds and retrieves all records from a specified table, selecting only the requested columns.
		 *
		 * This is a convenience method that acts as a wrapper for the `find()` method, allowing
		 * you to easily specify which columns you want to retrieve without setting any conditions.
		 *
		 * @param string $table_name The name of the table to query.
		 * @param array $columns An array of column names to select from the table.
		 * @return mixed The result of the query, which can be an array of rows or `false` on an empty result or error.
		 * @see find()
         */
        /**-------------------------------------------------------------------------*/
        public function findColumns(string $table_name, array $columns){
            return $this->find($table_name, [], $columns);
        }

        /**-------------------------------------------------------------------------*/
        /**
		 * Inserts a new record into a specified table.
		 *
		 * This method constructs a parameterized `INSERT` query based on an associative array of data. It
		 * automatically generates the column list and placeholders, and then safely binds the values
		 * to prevent SQL injection.
		 *
		 * @param string $table_name The name of the table to insert the new record into.
		 * @param array $data An associative array where keys are column names and values are the data to be inserted.
		 * @return int|bool The ID of the last inserted row on success, or `false` on failure.
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
		 * Updates records in a specified table that match a given set of conditions.
		 *
		 * This method securely updates data using prepared statements. It constructs a parameterized
		 * `UPDATE` query from the provided data and conditions, ensuring that values are properly
		 * bound to prevent SQL injection.
		 *
		 * @param string $table_name The name of the table to update.
		 * @param array $data An associative array where keys are column names and values are the new data.
		 * @param array $conditions An array of conditions to filter which rows to update. Conditions can be:
		 * - An associative array: `['column_name' => 'value']`.
		 * - An indexed array with two elements: `['column_name', 'value']` (defaults to `=` operator).
		 * - An indexed array with three elements: `['column_name', 'operator', 'value']`.
		 * - An array of the above formats to apply multiple conditions with `AND`.
		 * @return int|bool The number of rows affected by the update on success, or `false` on failure.
		 * @throws \Exception If a condition is not a valid array.
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
				$key 		 = ":set_" . $column;
                $setClause[] = $column . " = " . $key;
                $bindings 	 = array_merge([$key => $value], $bindings);
            }

            /**
			 * Collect WhereClause and Bindings
			 */
			$clauses 	 = $this->parseConditions($conditions);
			$whereClause = isset($clauses["whereClause"]) ? $clauses["whereClause"] : [];
			$bindings 	 = isset($clauses["bindings"]) ? array_merge($clauses["bindings"], $bindings) : $bindings;

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
		 * Deletes records from a specified table that match a given set of conditions.
		 *
		 * This method securely deletes data using prepared statements. It constructs a parameterized
		 * `DELETE` query from the provided conditions, ensuring that values are properly
		 * bound to prevent SQL injection.
		 *
		 * @param string $table_name The name of the table to delete records from.
		 * @param array $conditions An array of conditions to filter which rows to delete. Conditions can be:
		 * - An associative array: `['column_name' => 'value']`.
		 * - An indexed array with two elements: `['column_name', 'value']` (defaults to `=` operator).
		 * - An indexed array with three elements: `['column_name', 'operator', 'value']`.
		 * - An array of the above formats to apply multiple conditions with `AND`.
		 * @return int|bool The number of rows affected by the delete on success, or `false` on failure.
		 * @throws \Exception If a condition is not a valid array.
         */
        /**-------------------------------------------------------------------------*/
        public function delete(string $table_name, array $conditions){
            /**
             * @var array bindings Placeholder bindings
             */
            $bindings = [];

            /**
			 * Collect WhereClause and Bindings
			 */
			$clauses 	 = $this->parseConditions($conditions);
			$whereClause = isset($clauses["whereClause"]) ? $clauses["whereClause"] : [];
			$bindings 	 = isset($clauses["bindings"]) ? $clauses["bindings"] : [];

            /**
             * Build SQL
             */
            $sql = "DELETE FROM " . $table_name . " WHERE " . implode(" AND ", $whereClause);

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
		 * Counts the number of rows in a specified table that match the given conditions.
		 *
		 * This method constructs a `SELECT COUNT(*)` query and safely executes it using prepared statements.
		 * It supports different formats for specifying conditions in the `$conditions` array.
		 *
		 * @param string $table_name The name of the table to count rows from.
		 * @param array $conditions An array of conditions to filter the count. Conditions can be:
		 * - An associative array: `['column_name' => 'value']`.
		 * - An indexed array with two elements: `['column_name', 'value']` (defaults to `=` operator).
		 * - An indexed array with three elements: `['column_name', 'operator', 'value']`.
		 * - An array of the above formats to apply multiple conditions with `AND`.
		 * @return int|bool The number of rows matching the conditions, or `false` on failure.
		 * @throws \Exception If a condition is not a valid array.
         */
        /**-------------------------------------------------------------------------*/
        public function count(string $table_name, array $conditions=[]): int|bool{
            /**
			 * Collect WhereClause and Bindings
			 */
			$clauses 	 = $this->parseConditions($conditions);
			$whereClause = isset($clauses["whereClause"]) ? $clauses["whereClause"] : [];
			$bindings 	 = isset($clauses["bindings"]) ? $clauses["bindings"] : [];

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
		 * Checks if a specific column exists in a given database table.
		 *
		 * This method queries the `INFORMATION_SCHEMA` to verify the existence of a column. It's a safe way to
		 * check for a column's presence before attempting to perform operations on it, preventing potential
		 * query errors.
		 *
		 * @param string $table_name The name of the table to check.
		 * @param string $column_name The name of the column to search for.
		 * @return bool Returns `true` if the column exists in the specified table, otherwise returns `false`.
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
         * Executes a SQL query with prepared statements and parameter bindings.
         *
         * This method is the core execution logic for the ORM. It handles `SELECT`, `INSERT`, `UPDATE`, and `DELETE`
         * queries, and returns the appropriate result based on the query type. It uses PDO prepared statements
         * to prevent SQL injection attacks.
         *
         * @param string $sql The SQL query string to be executed.
         * @param array $bindings An associative or indexed array of values to bind to the prepared statement. Defaults to an empty array.
         * @param string $fetchMethod The PDO fetch method to use for SELECT queries (e.g., `"fetchAll"`, `"fetch"`).
         * @param int $fetchStyle The PDO fetch style constant (e.g., `PDO::FETCH_ASSOC`) to be used with the fetch method.
         * @return mixed The result of the query:
         * - For `SELECT` queries: An array of rows or a single row, depending on the fetch method.
         * - For `INSERT` queries: The ID of the last inserted row.
         * - For `UPDATE` or `DELETE` queries: The number of affected rows.
         * - On failure: `false`.
         */
        /**-------------------------------------------------------------------------*/
        public function query(string $sql, array $bindings=[], string $fetchMethod="fetchAll", string $fetchStyle=PDO::FETCH_ASSOC){
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
        /**
         * Query Method: INNER JOIN
         * 
         * Executes a SELECT query with one or more JOIN clauses, allowing for custom selection, ordering, and limiting.
         *
         * This method constructs a SQL query from a primary table and an array of join clauses. It supports
         * two formats for defining join clauses: a simple string format or an associative array.
         *
         * @param string $primaryTable The name of the main table for the query.
         * @param array $joins An array of join clauses. Each element can be:
         * - A string (e.g., `"users INNER JOIN posts ON posts.user_id = users.id"`).
         * - An associative array (e.g., `["table" => "posts", "on" => "posts.user_id = users.id"]`). The key should be the join keyword (e.g., 'on') and the value the condition.
         * @param array $selects An array of columns to select from the tables. Defaults to `["*"]`.
		 * @param array $conditions An array of conditions to filter the count. Conditions can be:
		 * - An associative array: `['column_name' => 'value']`.
		 * - An indexed array with two elements: `['column_name', 'value']` (defaults to `=` operator).
		 * - An indexed array with three elements: `['column_name', 'operator', 'value']`.
		 * - An array of the above formats to apply multiple conditions with `AND`.
         * @param array $orderBy An associative array for ordering the results (e.g., `["column_name" => "DESC"]`).
         * @param int $limit The maximum number of rows to return. A value of `0` means no limit.
         * @param string $fetchMethod The PDO fetch method to use (e.g., `fetchAll`, `fetch`).
         * @param int $fetchStyle The PDO fetch style constant (e.g., `PDO::FETCH_ASSOC`).
         * @return mixed The result of the query, typically an array of rows or a single row, depending on the fetch method.
         * @throws \Exception If a join clause array is malformed and cannot be processed.
         */
        /**-------------------------------------------------------------------------*/
        public function join(string $primaryTable, array $joins=[], array $selects=["*"], array $conditions=[], $orderBy=[], $limit=0, $fetchMethod="fetchAll", $fetchStyle=PDO::FETCH_ASSOC){
            /**
             * Parse Join Clauses
             */
            $joinClauses = [];

			// Check dimensions of array
			if(array_keys($joins) !== range(0, count($joins) - 1)){
				$joins = [$joins];
			}
			// Loop arrays in joins
            foreach($joins as $join){
                // Determine type of array
                $isIndexed = array_keys($join) === range(0, count($join) - 1);
                if($isIndexed === true && count($join) === 1){
                    // Format: "table KEYWORD other_table.column = table.column
                    $joinClauses[] = $join[0];

                } else {
                    // Format: "table" => "table_name", "KEYWORD" => "condition"
                    if(count($join) > 2 || !isset($join["table"])){
                        throw new Exception("Unable to process INNER JOIN request!");
                    }

                    // Find Properties
                    $keys       = array_keys($join);
                    $table      = $join["table"];

                    // Find Keyword
                    $last_key   = array_diff($keys, ["table"]);
                    $keyword    = reset($last_key);
                    $condition  = $join[$keyword];
                    
                    // Push Condition to Join Clauses array
                    $joinClauses[] = $table . " " . strtoupper($keyword) . " " . $condition;
                }
            }

			/**
			 * Parse Conditions
			 */
			$clauses 	 = $this->parseConditions($conditions);
			$whereClause = isset($clauses["whereClause"]) ? $clauses["whereClause"] : [];
			$bindings 	 = isset($clauses["bindings"]) ? $clauses["bindings"] : [];

            /**
             * Form SQL
             */
            $sql = "SELECT " . implode(", ", $selects) . " FROM " . $primaryTable;
			
			/**
			 * Append INNER JOIN Clause
			 */
			if(!empty($joinClauses)){
				$sql .= " INNER JOIN " . implode(" INNER JOIN ", $joinClauses);
			}

			/**
			 * Append WHERE Clause
			 */
            if (!empty($whereClause)) {
                // Append WHERE Clause if not empty
                $sql .= " WHERE " . implode(" AND ", $whereClause);
            }

            /**
			 * Append ORDER BY Clause
			 */
            if(!empty($orderBy)){
                // Grab Values
                $orderClause = [];
                foreach($orderBy as $column => $direction){
                    $orderClause[] = $column . " " . $direction;
                }

                // Implode and Append
                $sql .= " ORDER BY " . implode(", ", $orderClause);
            }

            /**
			 * Append LIMIT clause
			 */
            if($limit > 0){
                $sql .= " LIMIT " . $limit;
            }
            /**
             * Perform Query
             */
            try {
                // Prepare
				$stmt = $this->db->prepare($sql);
                $stmt->execute($bindings);
				return call_user_func_array([$stmt, $fetchMethod], [$fetchStyle]);

			} catch(\Exception $e){
				return [];
			}
        }

		/**-------------------------------------------------------------------------*/
		/**
		 * Query Method: First
		 * - Doesnt use conditions
		 * Return the first record found in the table
		 */
		/**-------------------------------------------------------------------------*/
		public function first(string $table_name, array $columns=["*"]){
			return $this->find($table_name, [], [], 1, $columns, "fetch");
		}

		/**-------------------------------------------------------------------------*/
		/**
		 * Query Method: Fetch All records
		 */
		/**-------------------------------------------------------------------------*/
		public function all(string $table_name){
			return $this->find($table_name);
		}

		/**-------------------------------------------------------------------------*/
		/**
		 * This is an efficient helper method that determines if one or more records match
		 * a set of conditions without fetching any data. It works by internally calling
		 * the `count()` method and checking if the returned count is greater than zero.
		 * This is the ideal way to check for a record's presence before proceeding with other logic.
		 *
		 * @param string $table_name The name of the table to check.
		 * @param array $conditions An array of conditions to filter the check. The format is the same as the `find()` and `count()` methods.
		 * @return bool Returns `true` if one or more records exist that match the conditions, otherwise `false`.
		 * @throws \Exception If a condition is not a valid array.
 		 * @example if ($orm->exists('users', [['id', 1]])) { echo "User exists!"; }
		 * @see count()
		 */
		/**-------------------------------------------------------------------------*/
		public function exists(string $table_name, array $conditions): bool{
			// Checks for a record's presence in a table
			return $this->count($table_name, $conditions);
		}
		
		/**-------------------------------------------------------------------------*/
		/**
		 * Find of fail
		 */
		/**-------------------------------------------------------------------------*/
		public function findOrFail(string $table_name, array $conditions, array $columns=["*"]){
			// Perform Query
			$results = $this->find($table_name, $conditions, [], 0, $columns);

			// Return Record OR Throw Exception
			if(empty($results)){
				throw new Exception("Could NOT find record in table: " . $table_name . "!!!");
			} else {
				return $results;
			}
		}
		
		/**-------------------------------------------------------------------------*/
		/**
		 * Parse Conditions
		 * 
		 * Acceptable Format for Conditions:
		 * - [column_name => value] : id = 49
		 * - [column_name, operator, value] : user_id <= 49
		 * - [column_name, value] : price = 32
		 * - ["column_name operator value"] : amount >= 99.99
		 */
		/**-------------------------------------------------------------------------*/
		private function parseConditions(array $conditions): array{
            /**
             * Generate WHERE clause:
             * - Determine if $conditions
             * - Bind Conditions
             */
            $whereClause = [];
            $bindings    = [];

            if(!empty($conditions)){
				// Check $conditions for simple assoc array
				if(array_keys($conditions) != range(0, count($conditions) - 1)){
					$conditions = [$conditions];

				}
                // Form where clause with bindings
                foreach($conditions as $condition){
					/**
					 * @var array $acc Accumulator array for $clauses[key, operator, value]
					 */
					$acc = [];
					
					// Determine type of $condition
					if(is_string($condition)){
						/**
						 * Circumstance 1: $condition is a string: "product_id >= 12"
						 */
						// Check for comma delimiters
						if(strpos($condition, ",")){
							// Separate and format
							$clauses = explode(",", $condition);
						} else {
							$clauses = [$condition];
						}

						// Loop Condition strings
						foreach($clauses as $clause){
							// Trim
							$clause = trim($clause);

							// Parse key, operator, value
							$pattern = '/^(\w+)\s*([<>=!]{1,2})\s*(.+)$/';
							$matches = [];
							if(preg_match($pattern, $clause, $matches)){
								// Validate 3 parts to array
								$index_clause = array_keys($matches, $clause)[0];
								
								if(count(array_diff($matches, [$matches[$index_clause]])) !== 3){
									throw new Exception("Incorrect Condition format!");
								}
								
								// Push to accumulator array
								$acc[] = [
									"column" 	=> $matches[$index_clause + 1],
									"operator"  => $matches[$index_clause + 2],
									"value" 	=> $matches[$index_clause + 3],
								];
							}
						}
					} elseif(is_array($condition)){
						/**
						 * Circumstance 2: $condition is an array
						 */
						if(array_keys($condition) != range(0, count($condition) - 1)){
							/**
							 * Associative Array
							 */
							foreach($condition as $key => $value){
								// Push to accumulator array
								$acc[] = [
									"column" 	=> $key,
									"operator"  => "=",
									"value" 	=> $value
								];
							}
						} else {
							/**
							 * Indexed Array:
							 * - Count = 2: Operator default "="
							 * - Count = 3: Operator index 1
							 */
							if(count($condition) === 2){
								// Operator = "="
								$acc[] = [
									"column" 	=> $condition[0],
									"operator"  => "=",
									"value" 	=> $condition[1]
								];
							} elseif(count($condition) === 3){
								// Operator in array
								$acc[] = [
									"column" 	=> $condition[0],
									"operator"  => $condition[1],
									"value" 	=> $condition[2]
								];
							} else {
								// Skip malformed $condition arrays
								continue;
							}
						}
					}

					/**
					 * Loop accumulator array entries and push to $whereClause
					 */
					foreach($acc as $clause){
						// Count bindings to check if placeholder already taken
						$count = 0;

						// Define placeholder key
						$key = ":" . $clause["column"];

						// Replace period character with underscore
						if(strpos($key, ".")){
							$key = str_replace(".", "_", $key);
						}

						// Check if placeholder key already exists in Bindings
						if(array_key_exists($key, $bindings)){
							$key = ":" . $clause["column"] . "_" . $count;
						}

						// Push to $whereClause
						$whereClause[] = $clause["column"] . " " . $clause["operator"] . " " . $key;

						// Bind Key => Value to bindings
						$bindings = array_merge([$key => $clause["value"]], $bindings);

						// Increment Count
						$count++;
					}
				}
				/**
				 * Return assoc array of whereClause and Bindings
				 */
				return [
					"whereClause" 	=> $whereClause,
					"bindings"		=> $bindings
				];
			}

			// Return Default: No Conditions to Parse
			return [];
		}
    }
?>