<?php

    /**
     * mnaatjes/mvc-framework - The PHP Framework For The Modern Web
     *
     * This file is part of the mnaatjes/mvc-framework package.
     *
     * @author     Mnaatjes (Michael Naatjes) <michael.naatjes87@gmail.com>
     * @copyright  2025 Mnaatjes
     * @license    http://opensource.org/licenses/MIT MIT License
     *
     * The public/index.php file is the sole entry point for the application,
     * responsible for handling all incoming requests. It serves as the front
     * controller and orchestrates the application's bootstrap process.
     *
     * This script performs the following key steps:
     * 1.  Loads environment variables from the `config/.env` file.
     * 2.  Includes the core framework bootstrap file, which initializes the
     * application and returns an instance of the Dependency Injection (DI)
     * Container.
     * 3.  Loads the application's service definitions from `config/services.php`
     * to configure the DI Container.
     * 4.  Retrieves and handles the appropriate HTTP request, dispatching it to
     * the correct controller and action.
     * 5.  Sends the final HTTP response back to the client.
     *
     * All requests are routed through this file, providing a clean separation
     * of concerns and a centralized point of control for the application.
     */

    /**
     * Require Autoloader from Project
     */
    //require __DIR__ . "/../vendor/autoload.php";

    /**
     * Load Environment Variables: 
     * - use namespace of DotEnv 
     * - Find location ~/config/.env
     * - Load into $_ENV
     */
    //require __DIR__ . "/../config/.env";

    /**
     * @var $container
     */
    $container = require __DIR__ . "/../../mvc-framework/bootstrap.php";

    /**
     * Require config/services.php
     */
    require ROOT_DIR . "/config/services.php";

    var_dump($container);
?>