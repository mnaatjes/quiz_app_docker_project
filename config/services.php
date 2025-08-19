<?php

    use App\Controllers\TestController;
use App\Controllers\UserController;
use App\Repositories\TestRepository;
use App\Repositories\UserRepository;
use mnaatjes\mvcFramework\DataAccess\Database;
    use mnaatjes\mvcFramework\DataAccess\ORM;
    /**
     * Declare DB Instance
     * @var Database $db_instance
     */
    $db_instance = Database::getInstance();

    /**
     * On $container instance
     * - Bind DB Instance
     * - Bind ORM Instance
     */
    $container->setShared("db", $db_instance);
    $container->setShared("orm", function($container){
        return new ORM($container->get("db"));
    });

    /**
     * Example set Test Controller
     */
    $container->set(TestController::class, function($container){
        return new TestController(new TestRepository($container->get("orm")));
    });

    /**
     * User Dependencies
     */
    $container->set(UserController::class, function($container){
        return new UserController(new UserRepository($container->get("orm")));
    });
    
    
?>