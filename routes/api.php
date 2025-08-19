<?php

    /**
     * Includes
     */
    use App\Controllers\UserController;
    
    /**
     * API Routes
     */
    $router->get("/users/", [UserController::class, "index"]);
    $router->get("/users/{id}/", [UserController::class, "show"]);
?>