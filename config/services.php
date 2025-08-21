<?php
    /**
     * services.php
     *
     * This file is responsible for defining and configuring application services
     * and dependencies within the Dependency Injection (DI) container.
     *
     * It binds key services such as the database connection, Object-Relational Mapper (ORM),
     * and session manager to the application container, making them accessible
     * throughout the application.
     *
     * This configuration file is a central part of the application's service
     * architecture, ensuring that dependencies are managed in a consistent
     * and organized manner.
     *
     * @package mnaatjes\mvcFramework\Config
     * @author Michael Naatjes michael.naatjes87@gmail.com
     * @link https://mnaatjes.github.io/docs/mvc-framework 
     * 
     * @since 1.0.0
     * - Created
     * - Added comments
     * - Refactored Dependencies
     * 
     * @version 1.1.0
     */

    use mnaatjes\mvcFramework\DataAccess\Database;
    use mnaatjes\mvcFramework\DataAccess\ORM;
    use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * Declare DB Instance
     * @var mnaatjes\mvc-framework\DataAccess\Database The singleton instance of the database connection.
     */
    $db_instance = Database::getInstance();

    /**
     * Bind the Database service to the container.
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \mnaatjes\mvcFramework\DataAccess\Database
     */
    $container->setShared("db", $db_instance);

    /**
     * Bind the ORM service to the container.
     *
     * This service provides an instance of the ORM class,
     * with the database connection as a dependency.
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \mnaatjes\mvcFramework\DataAccess\ORM
     */
    $container->setShared("orm", function($container){
        return new ORM($container->get("db"));
    });

    /**
     * Bind the Session Manager singleton to DI Container
     *
     * This service provides an instance of the SessionManager
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \mnaatjes\mvcFramework\SessionsCore\SessionManager
     */
    $container->setShared(SessionManager::class, new SessionManager());

    /**
     * Required Service Definitions:
     * - Middleware
     * - UserServices
     */
    require_once("Services/Middleware.php");
    require_once("Services/UserServices.php");
    require_once("Services/QuizServices.php");


?>