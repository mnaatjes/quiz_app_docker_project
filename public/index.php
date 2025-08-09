<?php

    /**
     * Main Public Index:
     * - define root directory
     * - require utils
     *      - enable errors
     * - require bootstrap
     */
    define("ROOT_DIR", dirname(__DIR__) . "/");
    require_once(ROOT_DIR . "app/utils/enable_errors.php");
    enable_errors();
    require_once( ROOT_DIR . "bootstrap.php");

    /**
     * Create Container Instance and make declarations
     * @var Container $container
     */
    $container = new Container();
    
    /**
     * Set Database Instance Dependency
     */
    $dbInstance = Database::getInstance();
    $container->set("db", $dbInstance);

    /**
     * TODO: Set ORM Class
     */

    /**
     * Set User Dependencies
     */
    $container->set("UserRepository", function($container){
        return new UserRepository($container->get("db"));
    });
    $container->set("UserController", function($container){
        $userRepository = $container->get("UserRepository");
        return new UserController($userRepository);
    });

    /**
     * Debugging
     */
    var_dump($dbInstance);

    /**
     * TODO: Declare Router Instance and declare paths
     */
?>


?>