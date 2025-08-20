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

    /**
     * Dashboard
     */
    $router->get("/dashboard", function($req, $res){
        // Render Registraton Page
        $res->render("dashboard");
    });

    /**
     * Dashboard
     */
    $router->get("/quiz/create", function($req, $res){
        // Render Registraton Page
        $res->render("create_quiz");
    });

    /**
     * Play Quiz
     */
    $router->get("/quiz/play", function($req, $res){
        // Grab Session
        session_start();
        
        // Render
        $res->render("play_quiz", $_SESSION["quiz_data"]);
    });

?>