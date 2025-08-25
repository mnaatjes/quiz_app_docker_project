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

use App\Controllers\UserController;
use App\Middleware\UserAuth;
use App\Services\UserService;

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
    $router->post("/login", [UserController::class, "show"]);

    /**-------------------------------------------------------------------------*/
    /**
     * GET /
     *
     * Executes Middleware for User Auth Failure
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    $router->get("/auth-failure", [], [function($req, $res, $next) use($container){
        // Render Registraton Page
        $container->get(UserAuth::class)->onFailure($req, $res, $next);
    }]);

    /**-------------------------------------------------------------------------*/
    /**
     * GET /
     * 
     * Login Failure
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    $router->get("/login-fail", [], [function($req, $res, $next){
        // Render Registraton Page
        $res->render("landing", ["error" => "Login not found. Please Try Again"]);
    }]);

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