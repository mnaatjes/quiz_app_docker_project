<?php

/**
 * A simple Dependency Injection Container.
 *
 * This container manages the creation and resolution of class instances
 * and their dependencies. It allows for the registration of service definitions
 * and provides a central point to retrieve fully configured objects.
 * 
 * @version 1.1
 * @since 1.0 :
 *  - Created
 *  - Integrated with Router Class
 * @since 1.1 :
 *  - Added Singleton / Shared registration
 *  - Added shared array
 *  - Modified get() method to check for shared entries
 */
class Container {
    /**
     * An array to store the service definitions.
     * @var array<string, mixed>
     */
    protected array $definitions = [];

    /**
     * An array to store shared definitions
     */
    protected array $shared = [];

    /**
     * Sets new class as an explicitly registered "singleton" / shared service
     */
    public function setShared(string $key, $factory){
        /**
         * Assign $factory (instance or Closure) to definitions
         * Set shared[DependencyName] to NULL by default:
         *  - Means that this shared DependencyName has not been created yet
         */
        $this->definitions[$key] = $factory;
        $this->shared[$key] = NULL;
    }

    /**
     * Registers a service definition with the container.
     *
     * The definition can be a class instance or a closure that returns an instance.
     * Using a closure allows for lazy loading of dependencies.
     *
     * @param string $key A unique identifier for the service.
     * @param mixed $definition The service instance or a closure to create it.
     * @return void
     * 
     * @example Set as instance:
     *  $container->set("desiredName", $instance);
     *  $container->set("ClassName", $classInstance);
     *  $container->set(ClassName::class, $classInstance);
     * 
     * @example Set with dependency
     *  $container->set("ClassName", function($container){
     *      return new ClassName($container->get("existing_dependency_className"));
     *  });
     * 
     *  $container->set("dependency", $dependency_instance);
     *  $container->set("ClassName", function($container){
     *      return new Classname($container->get("dependency"));
     *  });
     */
    public function set(string $key, $definition): void {
        $this->definitions[$key] = $definition;
    }

    /**
     * Resolves and returns a service from the container.
     *
     * If the definition is a closure, it is executed to create the service instance.
     * The container itself is passed to the closure, allowing for dependency
     * resolution within the definition.
     *
     * @param string $key The unique identifier of the service to retrieve.
     * @return mixed The resolved service instance.
     * @throws Exception If no definition is found for the given key.
     * 
     * @example Retrieving established dependency
     *  $container->get("dependencyName");
     *  $container->get("UserRepository");
     * 
     */
    public function get(string $key) {
        /**
         * Validate key
         */
        if (!isset($this->definitions[$key])) {
            throw new Exception("No definition found for key: $key");
        }

        /**
         * Modified v1.1
         * Check if $key exists in the $shared array:
         *  - Checks that service has been instantiated 
         *  - !==NULL means an instance already exists
         */
        if(array_key_exists($key, $this->shared) && $this->shared[$key] !== NULL){
            // Return existing instance of service
            return $this->shared[$key];
        }

        /**
         * Shared Instance has not been created || Regular service:
         * - Create instance of service from $definitions
         * - Check if instance exists as a key in $shared
         * - If shared[instanceName] then save to $shared and override NULL
         */
        $definition = $this->definitions[$key];

        /**
         * Register Instance:
         * - Check as Closure
         * - Check as defined
         * - Declare instance
         */
        $instance = ($definition instanceof Closure) ? $definition($this) : $definition;

        /**
         * Check if shared service
         * Store for later use as sole shared instance
         */
        if(array_key_exists($key, $this->shared)){
            /**
             * Override NULL and store instance as single / shared:
             * - Check if closure
             * - Otherwise store definition normally
             */
            $this->shared[$key] = $instance;
        }
        
        /**
         * Return the instance
         */
        return $instance;
    }
}