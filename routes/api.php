<?php

    /**
     * routes/api.php
     *
     * This file is where you can register API routes for your application. These
     * routes are loaded by the RouteServiceProvider and all of them will be
     * assigned to the "api" middleware group.
     *
     * You can define public and protected API endpoints here for mobile
     * applications, third-party integrations, or single-page applications (SPAs).
     *
     * @package mnaatjes\mvcFramework
     * 
     * @since 1.0.0:
     * - Created
     * - Refactored
     * - added Middleware Capabilites
     * - added SessionManager Class to dependencies
     * 
     * @version 1.1.0
     */


    /**
     * Routes:
     * - /login
     * - /quiz/test
     * - /quiz/store
     * - /debug
     * - 
     */
    /**-------------------------------------------------------------------------*/
    /**
     * POST /
     *
     * API routes /login for user authentication
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    $router->post("/login", function($req, $res, $params) use($container){
        var_dump("/login test");
        var_dump("params", $params);
        var_dump("container", $container);
    });

    /**-------------------------------------------------------------------------*/
    /**
     * POST /
     *
     * Example...
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/

?>