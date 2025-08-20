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
    $router->get("/debug", function() use($container){
        $session = $container->get(SessionManager::class);
        $session->set("puppy_id", "Gemini");

        var_dump($session->getAll());
    });
?>