<?php

    namespace mnaatjes\DataAccess;
    use ReflectionProperty;

    /**-------------------------------------------------------------------------*/
    /**
     * Base Model Abstract Class
     */
    /**-------------------------------------------------------------------------*/
    abstract class BaseModel {

        

        /**-------------------------------------------------------------------------*/
        /**
         * BaseModel Constructor
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(array $data){

            // Populate Initial Properties
            foreach($data as $key => $value){
                // Declare Reflection Property
                // TODO: Converter from or to camelCase 
                $reflected_prop = new \ReflectionProperty($this, $key);

                // Make accessible to define
                $reflected_prop->setAccessible(true);

                // Define prop
                $reflected_prop->setValue($this, $value);
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function __call(string $method_name, $arguments){}
        
        /**-------------------------------------------------------------------------*/
        /**
         * to Array
         */
        /**------------------------------------------------------------------------*/
        public function toArray(): array{
            return get_object_vars($this);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Sanitize call input
         */
        /**-------------------------------------------------------------------------*/
        private function sanitize(){}
    }

?>