<?php

    class Model implements JsonSerializable {
        private int $id;
        private string $text;
        private bool $isActive;
        private string $createdAt;
        private string $updatedAt;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(
            int $id,
            string $text,
            bool $isActive
        ){
            $this->id       = $id;
            $this->text     = $text;
            $this->isActive = $isActive;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * __call Method for Dynamic Method Handling
         * 
         * @param string $method
         * @param array $arguments
         * 
         * @return void
         */
        /**-------------------------------------------------------------------------*/
        public function __call(string $method, array $arguments) {
            /**
             * Validate Incoming Property Name:
             * - For get...()
             * - For set...()
             */
            if(str_starts_with($method, "set") || str_starts_with($method, "get")){
                /**
                 * Perform Match of incoming:
                 * - Declare method type
                 * - Get normalized property name
                 * - Verify Public or Protected
                 * - Execute Method
                 * - Throw exception on fail
                 */
                try {
                    // Method Type
                    $methodType = substr($method, 0, 3);

                    // Normalize incoming $method name
                    $propName = lcfirst(substr($method, 3));

                    // Find Property in Reflection
                    $reflectedProp = new ReflectionProperty($this, $propName);

                    // Validate property is private
                    if($reflectedProp->isPrivate()){
                        // Determine method type
                        if($methodType === "get"){
                            // Validate property is initialized
                            if($reflectedProp->isInitialized($this)){
                                // Perform Get Method
                                return $this->$propName;
                            } else {
                                return NULL;
                            }
                        } elseif($methodType === "set"){
                            // Perform Setting Method
                            $this->$propName = $arguments[0];
                            //var_dump($this->$propName);
                        }
                    }

                } catch(ReflectionException $e){
                    $msg = sprintf('Property "%s" unavailable for setting! Error: %s', $method, $e);
                    var_dump($msg);
                }
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * JSON Serialize String: Take Model Object and return JSON representation of properties
         * 
         * @param void
         * @return mixed
         */
        /**-------------------------------------------------------------------------*/
        public function jsonSerialize(): mixed{
            /**
             * Validate Inherited Model
             */

            /**
             * Collect Properties and return Assoc Array
             */
            $reflection = new ReflectionClass($this);
            return array_reduce($reflection->getProperties(), function($acc, $obj){
                // Make Accessible
                $obj->setAccessible(true);

                // Get names
                $propName   = $obj->getName();
                $methodName = "get" . ucfirst($propName);

                // Push to array and return
                $acc[$propName] = $this->$methodName();
                return $acc;
            }, []);
        }
    }
?>