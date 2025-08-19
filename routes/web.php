<?php

    /**
     * Web Routes
     */
    $router->get("/", function($req, $res){
        // Render Landing Page
        $res->render("landing", []);
    });
    $router->get("/register", function($req, $res){
        // Render Registraton Page
        $res->render("register", []);
    });

?>