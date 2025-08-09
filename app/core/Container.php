<?php

/**
 * A simple Dependency Injection Container.
 *
 * This container manages the creation and resolution of class instances
 * and their dependencies. It allows for the registration of service definitions
 * and provides a central point to retrieve fully configured objects.
 */
class Container {
    /**
     * An array to store the service definitions.
     * @var array<string, mixed>
     */
    protected $definitions = [];

    /**
     * Registers a service definition with the container.
     *
     * The definition can be a class instance or a closure that returns an instance.
     * Using a closure allows for lazy loading of dependencies.
     *
     * @param string $key A unique identifier for the service.
     * @param mixed $definition The service instance or a closure to create it.
     * @return void
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
     */
    public function get(string $key) {
        if (!isset($this->definitions[$key])) {
            throw new Exception("No definition found for key: $key");
        }
        $definition = $this->definitions[$key];
        
        // If the definition is a closure, execute it to get the instance
        if ($definition instanceof Closure) {
            return $definition($this);
        }
        
        // Otherwise, it's a shared instance
        return $definition;
    }
}