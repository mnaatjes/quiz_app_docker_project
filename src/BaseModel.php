<?php

    namespace mnaatjes\DataAccess;
    use ReflectionProperty;

    /**-------------------------------------------------------------------------*/
    /**
     * Base Model Abstract Class
     * 
     * @since 1.0.0: 
     * - Created
     * - Added getProperties()
     * - Created __call() magic method
     * - Utilized array of properties to validate __call()
     * 
     * @since 1.0.1:
     * - Finished __call()
     * - Tested __call()
     * 
     * @since 1.1.0: 
     * - Changed __call() method to validate via Reflection->hasProperty()
     * - Tested
     * 
     * @since 1.1.1:
     * - Created fill() method
     * - Changed __construct to use fill() method
     * 
     * @since 1.1.2:
     * - Created toArray Method
     * - Tested toArray Method
     * 
     * @since 1.2.0:
     * - getProperties() method no longer needed; not removed
     * - Testing integration with BaseRepository Class
     * 
     * @since 1.2.1:
     * - Added snake to camel conversion method
     * - Added snake to camel $key conversion in fill()
     * 
     * @since 1.2.2:
     * - Added protected $id to enforce primary key assignment
     * 
     * @since 1.2.3:
     * - 
     * 
     * @version 1.2.3
     */
    /**-------------------------------------------------------------------------*/
    abstract class BaseModel {

        /**
         * The unique identifier (primary key) for the model.
         * This property is explicitly declared here to satisfy the contract of the
         * IdentifiableModelInterface.
         *
         * @var int|null
         */
        private ?int $id = null;

        /**-------------------------------------------------------------------------*/
        /**
         * Constructs a new Model instance and populates its properties with initial data.
         *
         * This constructor initializes the model by calling the `fill()` method, which
         * takes an associative array and assigns the corresponding values to the model's
         * properties. This provides a convenient way to instantiate a model and
         * hydrate it with data in a single step, typically from a database record
         * or a user's form submission.
         *
         * @param array $data An associative array containing the initial property values for the model.
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(array $data){

            // Populate Initial Properties
            $this->fill($data);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Magic method to handle calls to undefined methods.
         *
         * This method acts as a generic getter and setter for class properties.
         * It dynamically validates the method name and arguments, and then
         * uses PHP's Reflection API to get or set a corresponding property.
         *
         * @param string $method_name The name of the method being called (e.g., 'getName', 'setAge').
         * @param array<mixed> $arguments A numerically indexed array containing the parameters passed to the method.
         *
         * @return mixed Returns the value of the property for 'get' methods.
         * @throws \InvalidArgumentException If the method prefix is not 'get' or 'set', or if the number of arguments is incorrect.
         * @throws \BadMethodCallException If a corresponding property for the method name does not exist.
         * @throws \ReflectionException If the reflection process fails.
         */
        /**-------------------------------------------------------------------------*/
        public function __call(string $method_name, $arguments){
            /**
             * Used to validate accepted methods
             * @var string $methodPrefix
             */
            $methodPrefix = strpos($method_name, 'get') === 0 ? 'get' : (strpos($method_name, 'set') === 0 ? 'set' : NULL);

            /**
             * @var string $methodSuffix
             */
            $methodSuffix = lcfirst(substr($method_name, 3));

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

            // Validate methodSuffix
            if(!$reflection->hasProperty($methodSuffix)){
                throw new \BadMethodCallException("Invalid get...() or set...() method: " . $method_name ."()");
            }

            /**
             * Reflection Property in order to access value of inherited (child) class with private property
             * @var \ReflectionProperty $reflectProp
             */
            $reflectProp = new \ReflectionProperty($this, $methodSuffix);

            /**
             * Execute Method Call:
             * - get
             * - set
             */
            if($methodPrefix === "get"){
                // Return value from Reflected Property
                return $reflectProp->getValue($this);

            } elseif($methodPrefix === "set"){
                // Get sanitized value
                $sanitizedValue = htmlspecialchars($arguments[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');

                // Assign value
                $reflectProp->setValue($this, $sanitizedValue);
            }
        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * Get Class Property Names
         */
        /**-------------------------------------------------------------------------*/
        private function getProperties(): array{
            /**
             * Reflected Child Class
             * @var \ReflectionClass $reflection
             */
            $reflection = new \ReflectionClass(get_called_class());

            /**
             * Reflection Class Properties[name]; Assoc Array with normalized (lowercase), snake_case, and camelCase
             */
            return array_map(function($obj){
                $obj->setAccessible(true);
                return [
                    "normal"    => strtolower($obj->getName()),
                    "propName"  => $obj->getName(),
                    "arrayName" =>  preg_replace('/(?<=[a-z])(?=[A-Z])/', '_', $obj->getName())
                ];
            }, $reflection->getProperties(\ReflectionProperty::IS_PRIVATE));
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Converts the model's private properties into an associative array.
         *
         * This method uses PHP's Reflection API to access all private properties
         * of the child class. It then uses array_reduce to transform these properties
         * into a single associative array where property names are converted from
         * camelCase to snake_case, making them suitable for use with databases or APIs.
         *
         * The method is a standard pattern for serializing a model's data.
         *
         * @return array An associative array of the model's properties and their values, with keys in snake_case.
         */
        /**------------------------------------------------------------------------*/
        public function toArray(): array{
            /**
             * Reflected Child Class
             * @var \ReflectionClass $reflection
             */
            $reflection = new \ReflectionClass(get_called_class());

            /**
             * Return array of snake_case keyed properties
             */
            return array_reduce($reflection->getProperties(\ReflectionProperty::IS_PRIVATE), function($acc, $obj){
                // Make property available
                $obj->setAccessible(true);

                // Format key to snake_case
                $propName = preg_replace('/(?<=[a-z])(?=[A-Z])/', '_', $obj->getName());

                // Return entry
                $acc[$propName] = $obj->getValue($this);
                return $acc;
            }, []);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Fills the model's properties with data from an array.
         *
         * This method iterates through an associative array, and for each key,
         * it attempts to set the corresponding public property on the model instance.
         * It uses PHP's Reflection API to ensure that only properties that actually
         * exist on the model are set, preventing "mass assignment" vulnerabilities.
         * Keys in the input array that do not correspond to a property are safely ignored.
         *
         * @param array $data An associative array of data to assign to the model's properties.
         * @return void
         */
        /**------------------------------------------------------------------------*/
        public function fill(array $data): void{
            /**
             * Reflected Child Class
             * @var \ReflectionClass $reflection
             */
            $reflection = new \ReflectionClass(get_called_class());

            /**
             * Loop incomding data
             */
            foreach($data as $input => $value){
                // Convert Key to camelCase
                $key = $this->snakeToCamel($input);

                // Declare Reflection Property
                $reflectProp = new \ReflectionProperty($this, $key);

                // Validate Property
                if(!$reflection->hasProperty($key)){
                    continue;
                }

                // Define prop
                $reflectProp->setValue($this, $value);
            }
        }

        /**------------------------------------------------------------------------*/
        /**
         * Converts a snake_case string to camelCase.
         *
         * This method splits a string by underscores, capitalizes the first letter of
         * each subsequent word, and then joins them to create a camelCase string.
         * The first word remains in its original case.
         *
         * @param string $input The snake_case string to be converted.
         * @return string The converted camelCase string.
         */
        /**------------------------------------------------------------------------*/
        private function snakeToCamel(string $input){
            // Split the string into an array of words using the underscore as a delimiter.
            $words = explode('_', $input);

            // If there's only one word, return it as is.
            if (count($words) <= 1) {
                return $input;
            }

            // Initialize an empty array to hold the new words.
            $camelCasedWords = [];

            // The first word remains lowercase.
            $camelCasedWords[] = array_shift($words);

            // Loop through the rest of the words.
            foreach ($words as $word) {
                // Capitalize the first letter of each subsequent word.
                $camelCasedWords[] = ucfirst($word);
            }

            // Join the words back together to form the final camelCase string.
            return implode('', $camelCasedWords);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update Model
         * @return BaseModel
         */
        /**-------------------------------------------------------------------------*/
        public function update(array $data){}
    }

?>