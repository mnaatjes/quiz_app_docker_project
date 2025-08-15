<?php

    namespace mnaatjes\DataAccess;
    use mnaatjes\DataAccess\ORM;
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
     * - Changed to Abstract Class
     * - Removed isInherited property (abstract classes MUST be inherited)
     * 
     * 
     * @since 1.1.1:
     * - Added hydrate() and dehydrate() methods
     * - Added validation methods for invocation of model classes
     * - Enforced $tableName declaration
     * - Enforced $className declaration
     * 
     * @since 1.2.0:
     * - 
     * 
     * @version 1.2.0
     * 
     */
    /**-------------------------------------------------------------------------*/
    abstract class BaseRepository {
        /**
         * @var ORM $orm ORM Class Instance
         * TODO: Change to private
         */
        protected ORM $orm;

        /**
         * Table associated with Repository; Overridden by child class
         * @var string $tableName String from Child Repository Class
         */
        protected string $tableName;

        /**
         * Name of Model associated with Repository; Overridden by Child Class
         * @var string $modelClass 
         */
        protected string $modelClass;

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

            // Enforce properties of modelClass and TableName
            if (empty($this->modelClass) || empty($this->tableName)) {
                throw new \Exception("Repository must define a model and table name.");
            }

            // Validate Model Class as defined in Child Class
            $this->validateModelClass();

            // Validate BaseModel Inheritance
            $this->validateModelInheritance();

            // Validate tablename entered
            if(is_null($this->tableName) || empty(trim($this->tableName))){
                throw new \Exception("Table Name must be defined in Repository Class!");
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Returns string of table name
         */
        /**-------------------------------------------------------------------------*/
        protected function getTableName():string {return $this->tableName;}

        /**-------------------------------------------------------------------------*/
        /**
         * Validates the model class.
         *
         * This private method ensures that the `$modelClass` property is a valid,
         * instantiable class. It attempts to create a ReflectionClass instance
         * and throws a custom exception if the class does not exist or is invalid.
         *
         * @return void
         *
         * @throws \Exception If the model class namespace is invalid or the class does not exist.
         */
        /**-------------------------------------------------------------------------*/
        public function hydrate(array $data) {
            // Get ModelClass name
            $modelName = $this->modelClass;

            // return hydrated model
            return new $modelName($data);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Validates the model class.
         *
         * This private method ensures that the `$modelClass` property is a valid,
         * instantiable class. It attempts to create a ReflectionClass instance
         * and throws a custom exception if the class does not exist or is invalid.
         *
         * @return void
         *
         * @throws \Exception If the model class namespace is invalid or the class does not exist.
         */
        /**-------------------------------------------------------------------------*/
        public function dehydrate(BaseModel $model){
            return $model->toArray();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Validates the model class.
         *
         * This private method ensures that the `$modelClass` property is a valid,
         * instantiable class. It attempts to create a ReflectionClass instance
         * and throws a custom exception if the class does not exist or is invalid.
         *
         * @return void
         *
         * @throws \Exception If the model class namespace is invalid or the class does not exist.
         */
        /**-------------------------------------------------------------------------*/
        private function validateModelClass(): void{
            /**
             * Attempt to instantiate Reflection Class with child modelClass property
             */
            try {
                /**
                 * Reflection of Model Class
                 * @var \ReflectionClass $reflection
                 */
                $reflection = new \ReflectionClass($this->modelClass);
            } catch(\Exception $e){
                // Throw error message and exception
                throw new \Exception("Error! Invalid Model Class Namespace! Use format TestModel::class! Error: ". $e);
            }            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Validates the model class.
         *
         * This private method ensures that the `$modelClass` property is a valid,
         * instantiable class. It attempts to create a ReflectionClass instance
         * and throws a custom exception if the class does not exist or is invalid.
         *
         * @return void
         *
         * @throws \Exception If the model class namespace is invalid or the class does not exist.
         */
        /**-------------------------------------------------------------------------*/
        private function validateModelInheritance(){
            // Get modelClass value
            $modelClass = $this->modelClass;

            // Validate child inherits BaseModel
            if(!is_subclass_of($modelClass, BaseModel::class)){
                throw new \Exception("Model Class MUST extend BaseModel abstract Class");
            }
        }

        /**-------------------------------------------------------------------------*/

    }


?>