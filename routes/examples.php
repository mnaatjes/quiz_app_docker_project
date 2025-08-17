<?php

    /**
     * This file contains example routes
     */

    /**
     * Example Implementation of Controller
     */
    use App\Controllers\TestController;

    // Declare Test Controller
    //$router->get("/", function(){echo "Hello World!";});
    $router->get("/", [TestController::class, "index"]);
    //$router->get("/", "TestController@index()");
    

?>