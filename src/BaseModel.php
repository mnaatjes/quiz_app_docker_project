<?php

    namespace mnaatjes\DataAccess;

use Exception;
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
                // TODO: Validate property exists
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
        public function __call(string $method_name, $arguments){
            /**
             * Used to validate accepted methods
             * @var string $methodPrefix
             */
            $methodPrefix = strpos($method_name, 'get') === 0 ? 'get' : (strpos($method_name, 'set') === 0 ? 'set' : NULL);

            /**
             * Normalized (lowercase) method name minus get | set
             * @var string $methodSuffix
             */
            $methodSuffix = strtolower(substr($method_name, 3));

            // Validate method prefix
            if(is_null($methodPrefix)){
                throw new \InvalidArgumentException("Invalid Method Prefix! Only get... and set... methods accepted!");
            }

            /**
             * Validate arguments:
             * - get() = 0 arguments
             * - set() = 1 argument
             */
            if($methodPrefix === "get" && count($arguments) !== 0){
                throw new \InvalidArgumentException("Invalid method argument(s) for method: " . $method_name . "()");
            } elseif($methodPrefix === "set" && count($arguments) !== 1){
                throw new \InvalidArgumentException("Method: " . $method_name . "requires exactly ONE argument!");
            }

            /**
             * Reflected Child Class
             * @var \ReflectionClass $reflection
             */
            $reflection = new \ReflectionClass(get_called_class());

            /**
             * Reflection Class Properties[name]; Assoc Array with Normalized (lowercase) value and property value
             * @var array $properties
             */
            $properties = array_map(function($obj){
                $obj->setAccessible(true);
                return [
                    "normal" => strtolower($obj->getName()),
                    "propName" => $obj->getName()
                ];
            }, $reflection->getProperties(ReflectionProperty::IS_PRIVATE));
            
            var_dump($properties);
            /**
             * Sanitize name and argument
             */

            /**
             * Execute Method Call
             */

        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * to Array
         */
        /**------------------------------------------------------------------------*/
        public function toArray(): array{
            // TODO: Use reflection class
            return get_object_vars($this);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * from Array
         */
        /**------------------------------------------------------------------------*/
        public function fromArray(array $data){}
    }

?>