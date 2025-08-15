<?php

    namespace mnaatjes\DataAccess;
    use ReflectionClass;
    use ReflectionProperty;
    use Exception;
    use mnaatjes\DataAccess\ORM;
    use mnaatjes\DataAccess\BaseModel;
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
     * @version 1.1.1
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
        protected string $tableName = "";

        /**
         * Name of Model associated with Repository; Overridden by Child Class
         * @var string $modelClass 
         */
        protected string $modelClass;

        /**
         * Reflection of Model Class
         * @var ReflectionClass $reflection
         */
        protected ReflectionClass $reflection;

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
                throw new Exception("Repository must define a model and table name.");
            }

            // Declare Reflection of model
            //$this->reflection = new ReflectionClass($this->modelClass);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Returns string of table name
         */
        /**-------------------------------------------------------------------------*/
        protected function getTableName():string {return $this->tableName;}

        /**-------------------------------------------------------------------------*/
        /**
         * Returns string of model class name
         */
        /**-------------------------------------------------------------------------*/
        protected function getModelClass():string {return $this->modelClass;}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        private function getModelProps(){}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        private function getModelMethods(){}

        /**-------------------------------------------------------------------------*/
        /**
         * Retrieves the names of the constructor parameters for the associated model.
         */
        /**-------------------------------------------------------------------------*/
        private function getModelParams(){}

        /**-------------------------------------------------------------------------*/
        /**
         * Hydrate
         */
        /**-------------------------------------------------------------------------*/
        public function hydrate($record){}

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/


        /**-------------------------------------------------------------------------*/

    }


?>