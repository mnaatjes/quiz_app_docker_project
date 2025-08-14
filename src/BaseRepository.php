<?php

    namespace mnaatjes\DataAccess;
    use ReflectionClass;
    use ReflectionProperty;
    use Exception;
    use PDO;
    /**-------------------------------------------------------------------------*/
    /**
     * Base / Parent Repository Class
     * 
     * @since 1.0.0: 
     *  - Created
     * 
     * @since 1.0.1:
     * - Moved from MVC-Core to data-access
     * 
     * @since 1.1.0:
     * - Added comments to getReflection()
     * - Added comments to getTableName()
     * - Removed:
     *  --> findById()
     *  --> findByWhere()
     * - Added all main comments to all existing methods
     * 
     * @since 1.1.1:
     * 
     * @version 1.1.1
     */
    /**-------------------------------------------------------------------------*/
    class BaseRepository {
        /**
         * @var ORM $orm ORM Class Instance
         * TODO: Change to private
         */
        protected ORM $orm;

        /**
         * @var bool $isInherited
         */
        private bool $isInherited = false;

        /**
         * Model Reflection
         * @var ReflectionClass $reflection Associated Model Class Reflection
         * TODO: change to Private
         */
        protected $reflection;

        /**
         * @var string $tableName String from Child Repository Class
         * Child classes must override this property.
         */
        protected static string $tableName = "";

        /**-------------------------------------------------------------------------*/
        /**
         * BaseRepository constructor.
         *
         * Initializes the repository by setting the ORM instance, determining if the class is inherited,
         * and creating a ReflectionClass instance for the associated model.
         *
         * @param ORM $orm_instance The ORM instance to be used by the repository.
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
         * Dynamically retrieves a ReflectionClass instance for the associated model.
         *
         * This method uses a convention-based approach to find the corresponding model
         * class. It assumes the model's class name is the repository's class name with
         * "Repository" replaced by "Model". This process only runs if the
         * `$isInherited` property is set to `true`.
         *
         * For example, if called from `App\Repositories\UserRepository`, it will attempt
         * to find and reflect the `App\Repositories\UserModel` class.
         *
         * @return \ReflectionClass|null A ReflectionClass instance of the associated model, or null
         * if the repository is not inherited.
         * @throws \Exception If the associated model class cannot be found.
         */
        /**-------------------------------------------------------------------------*/
        private function getReflection(): ?object{
            if($this->isInherited === true){
                // TODO: Ensure namespace check and verification

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
         * Retrieves the table name associated with the repository.
         *
         * This method validates that the concrete repository class has a public
         * static property named `$tableName` and that it is not empty. It then
         * returns the value of this property.
         *
         * @return string The name of the database table.
         * @throws \Exception If the `$tableName` property is not defined or is empty in the repository class.
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
         * Retrieves the names of all properties from the associated model.
         *
         * This private helper method uses a ReflectionClass instance to get all
         * properties of the associated model and returns their names as a simple array
         * of strings. This operation is only performed if the repository is inherited
         * and a valid reflection object is available. It also makes all properties
         * accessible before returning their names.
         *
         * @return string[] An array of strings representing the names of the model's properties.
         * @throws \Exception If the repository is not inherited or the reflection object is null.
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
         * Retrieves the names of all methods from the associated model.
         *
         * This private helper method uses a ReflectionClass instance to get all
         * methods of the associated model and returns their names as a simple array
         * of strings. This operation is only performed if the repository is inherited
         * and a valid reflection object is available. It also makes all methods
         * accessible before returning their names.
         *
         * @return string[] An array of strings representing the names of the model's methods.
         * @throws \Exception If the repository is not inherited or the reflection object is null.
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
         * Retrieves the names of the constructor parameters for the associated model.
         *
         * This private helper method uses a ReflectionClass instance to get the
         * constructor's parameters and returns their names as a simple array of strings.
         * This operation is only performed if the repository is inherited and a
         * valid reflection object is available.
         *
         * @return string[] An array of strings representing the parameter names of the model's constructor.
         * @throws \Exception If the repository is not inherited or the reflection object is null.
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

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/


        /**-------------------------------------------------------------------------*/

    }


?>