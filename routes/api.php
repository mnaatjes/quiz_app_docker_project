<?php

    /**
     * Includes
     */

use App\Controllers\QuizController;
use App\Controllers\UserController;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * User Login
     */
    $router->post("/login", [UserController::class, "show"]);

    /**
     * Store Quiz API
     */
    $router->post("/quiz/store", [QuizController::class, "store"]);

    /**
     * Test Quiz
     */
    $router->get("/quiz/test", function($req, $res){
        // Grab Session
        session_start();
        
        // Render
        $json = json_encode($_SESSION["quiz_data"], JSON_PRETTY_PRINT);

        echo "<pre>" . htmlspecialchars($json) . "</pre>";
    });

    /**
     * Debugging
     */
    $router->get("/debug", function($req, $res) use($container){
        $session = $container->get(SessionManager::class);
        var_dump("Handler");
        var_dump($session->get("pet"));
    }, [function($req, $res, $next) use($container){
        $session = $container->get(SessionManager::class);
        $session->set("pet", "gemini");
        var_dump("Middleware");
        $next();
    }]);
?>