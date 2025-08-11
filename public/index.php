<?php

    /**
     * Main Public Index:
     * - define root directory
     * - require utils
     *      - enable errors
     * - require bootstrap
     */
    define("ROOT_DIR", "/workspace/"); 
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
     * Set ORM Instance with DB Dependency
     */
    $container->setShared("orm", function($container){
        return new ORM($container->get("db"));
    });

    /**
     * Add Dependency to SampleRepo
     */
    $container->set("SampleRepository", function($container){
        return new SampleRepository($container->get("orm"));
    });
    $container->set(TestRepository::class, function($container){
        return new TestRepository($container->get("orm"));
    });
    $container->set(SampleController::class, function($container){
        return new SampleController($container->get("SampleRepository"));
    });
    $container->set(TestController::class, function($container){
        return new TestController($container->get("TestRepository"));
    });

    $controller = $container->get("TestController");
    $controller->actionHydrate();

    $test = new Repository($container->get("orm"));

    /**
     * Set HTTP Request and Reponse Objects
     */
    $request    = new HttpRequest();
    $response   = new HttpResponse();

    /**
     * TODO: Set ORM Class
     */

    /**
     * Set User Dependencies
     */

    $container->set(SampleController::class, function($container){
        return new SampleController("Hola, Como Estas?");
        // TODO: add dependency for test
    });
    /*

    $container->set("UserRepository", function($container){
        return new UserRepository($container->get("db"));
    });
    $container->set("UserController", function($container){
        
        return new UserController($container->get("UserRepository"));
    });
    */
    /**
     * Debugging
     */
    /*
    $router = new Router($container);
    $router->get("/", function(){
        var_dump("Hello this is the index");
    });
    $router->dispatch($request, $response);
    /**
     * TODO: Declare Router Instance and declare paths
     */
?>