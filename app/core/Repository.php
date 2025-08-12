<?php

    /**
     * Base / Parent Repository Class
     * 
     * @version 1.0
     * @since 1.0: 
     *  - Created
     */
    class Repository {
        /**
         * @var ORM $orm ORM Class Instance
         */
        protected ORM $orm;

        /**
         * @var bool $isInherited
         */
        private bool $isInherited = false;

        /**
         * Model Reflection
         * @var ReflectionClass $reflection Associated Model Class Reflection
         */
        protected $reflection;

        /**
         * @var string $tableName String from Child Repository Class
         * Child classes must override this property.
         */
        protected static string $tableName = "";

        /**-------------------------------------------------------------------------*/
        /**
         * Constructor
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(ORM $orm_instance){
            // Apply ORM Instance
            $this->orm = $orm_instance;

            // Assign is_inherited
            $this->isInherited = get_called_class() !== self::class;
            
            // Assign model reflection
            $this->reflection = $this->getReflection();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Init Method: Assigns reflection of model
         */
        /**-------------------------------------------------------------------------*/
        private function getReflection(): ?object{
            if($this->isInherited === true){
                // Define Model Name
                $modelName  = str_replace("Repository", "", get_called_class()) . "Model";

                // Validate Model Class Exists and Return
                if(class_exists($modelName) === true){
                    // Return Reflected Class
                    return new ReflectionClass($modelName);
                } else {
                    // Throw Exception
                    throw new Exception("Unable to find Model Class associated with " . get_called_class());
                }
            } else {
                // Return Default
                return NULL;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Get Table Name
         * Retrieves the static table name property from child class
         */
        /**-------------------------------------------------------------------------*/
        protected function getTableName(){
            /**
             * Get Child Repo Class name
             */
            $childClass = get_called_class();

            /**
             * Validate Child Class repository has defined tableName
             */
            if (!property_exists($childClass, 'tableName') || empty($childClass::$tableName)) {
                throw new Exception("Table Name in Repository class is NOT Defined!");
            }

            return static::$tableName;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD: Find By column
         */
        /**-------------------------------------------------------------------------*/
        public function findBy(?array $columns){
            /**
             * Query ORM
             */
            $this->orm->find(
                $this->getTableName(),
                [],
                $columns
            );
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD: Find By with conditions WHERE
         */
        /**-------------------------------------------------------------------------*/
        public function findByWhere(?array $conditions, ?array $columns=["*"]){
            /**
             * Query ORM
             */
            try {
                $records = $this->orm->find(
                    $this->getTableName(),
                    $conditions,
                    $columns
                );
            } catch (Exception $e){
                var_dump($e);
            }

            /**
             * Return records
             */
            return $records;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD: Find by ID / PKey
         */
        /**-------------------------------------------------------------------------*/
        public function findById(int $id){
            $this->orm->find(
                $this->getTableName(),
                ["id" => $id],
                ["id", "name"],
                "fetchColumn",
                PDO::FETCH_COLUMN
            );
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD Method: Get all records
         */
        /**-------------------------------------------------------------------------*/
        public function getAll(){
            return $this->orm->find(
                $this->getTableName(),
                [],
                ["*"],
                "fetchAll",
                PDO::FETCH_ASSOC
            );
        }

        /**-------------------------------------------------------------------------*/
        /**
         * CRUD Method: Get all primary keys
         */
        /**-------------------------------------------------------------------------*/
        public function getAllIds(){
            return $this->orm->find(
                $this->getTableName(),
                [],
                ["id"],
                "fetchAll",
                PDO::FETCH_COLUMN
            );
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Get Model Properties
         * 
         * @return array
         * @throws Exception
         */
        /**-------------------------------------------------------------------------*/
        private function getModelProps(){
            if($this->isInherited === true && !is_null($this->reflection)){
                return array_map(function($obj){
                    $obj->setAccessible(true);
                    return $obj->getName();
                }, $this->reflection->getProperties());
            } else {
                throw new Exception("Unable to parse properties!");
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Get Model Methods
         * 
         * @deprecated
         * @return array
         * @throws Exception
         */
        /**-------------------------------------------------------------------------*/
        private function getModelMethods(){
            if($this->isInherited === true && !is_null($this->reflection)){
                return array_map(function($obj){
                    $obj->setAccessible(true);
                    return $obj->getName();
                }, $this->reflection->getMethods());
            } else {
                throw new Exception("Unable to parse properties!");
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Find Parameters of the model constructor
         */
        /**-------------------------------------------------------------------------*/
        private function getModelParams(){
            if($this->isInherited === true && !is_null($this->reflection)){
                return array_map(function($obj){
                    return $obj->getName();
                }, $this->reflection->getConstructor()->getParameters());
            } else {
                throw new Exception("Unable to parse properties!");
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Hydrate
         * 
         * @param string|array $record Row from the database representing one model of the repository
         *  - Values coming in will be snake_case
         */
        /**-------------------------------------------------------------------------*/
        public function hydrate($record){
            /**
             * Get model properties, methods, and parameters
             */
            $properties = $this->getModelProps();
            $parameters = array_map(function($param){
                // Convert to snake case if necessary
                return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $param));
            }, $this->getModelParams());

            // Find the remaining properties to assign after instance created
            $difference = array_diff($properties, $parameters);
            
            /**
             * Create instance of model and assign parameters
             * @var Model $model Instance created
             */
            $model = $this->reflection->newInstance(
                // Assign parameters
                ...array_map(function($param) use($record){
                    if(array_key_exists($param, $record)){
                        return $record[$param];
                    }
                }, $parameters)
            );

            /**
             * Assign remaining Properties:
             * - Validate Difference
             * - Check for uninitialized properties of $reflection
             */
            foreach($difference as $diff){
                // Validate property has not been set
                $reflectedProp = new ReflectionProperty($model, $diff);
                if(!$reflectedProp->isInitialized($model)){
                    /**
                     * Execute Method on UnInitialized Properties:
                     * - Form method string
                     * - Find value from $record (camelCase to snake_case)
                     * - Execute set method
                     */
                    // Form Method string
                    $method = "set" . ucfirst($diff);

                    // snake_case key
                    $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $diff));

                    // Validate record key
                    if(array_key_exists($key, $record)){
                        // Perform Set Action and inject record value
                        $model->$method($record[$key]);
                    }
                }
            }
            /**
             * Return Model
             */
            return $model;
        }

    }


?>