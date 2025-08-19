<?php

    /**
     * Includes
     */

use App\Controllers\QuizController;
use App\Controllers\UserController;
    
    /**
     * User Login
     */
    $router->post("/login", [UserController::class, "show"]);

    /**
     * Store Quiz API
     */
    $router->post("/quiz/store", [QuizController::class, "store"]);
?>