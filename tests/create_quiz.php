<?php

    /**
     * Require Utils and Framework
     */
    require_once('/var/www/app/utils/enable_errors.php');
    require_once('/var/www/app/utils/db_connect.php');
    require_once('/var/www/app/simple_http_manager/SimpleHttpManager.php');

    /**
     * Enable Utilities
     */
    enable_errors();

    /**
     * User Model, Repo, Controller
     */
    require_once('/var/www/app/models/UserModel.php');
    require_once('/var/www/app/models/UserRepository.php');
    require_once('/var/www/app/controllers/UserController.php');

    /**
     * Quiz Model, Repo, Controller
     */
    require_once('/var/www/app/models/QuizModel.php');
    require_once('/var/www/app/models/QuizRepository.php');
    require_once('/var/www/app/controllers/QuizController.php');
    require_once('/var/www/app/http/reponses/QuizResponseObject.php');

    /**
     * Question Model, Repo, Controller
     */
    require_once('/var/www/app/models/QuestionModel.php');
    require_once('/var/www/app/models/QuestionRepository.php');
    //require_once('/var/www/app/controllers/QuestionController.php');

    /**
     * Answer Model, Repo, Controller
     */
    require_once('/var/www/app/models/AnswerModel.php');
    require_once('/var/www/app/models/AnswerRepository.php');
    //require_once('/var/www/app/controllers/QuestionController.php');
    /**
     * Attempt PDO Connection to DB
     */
    $dbConfig = require('/var/www/app/config/db_config.php');
    
    if(empty($dbConfig)){
        throw new Exception('Unable to identify Database Configuration!');
    }

    /**
     * Create Dependencies
     */
    $userRepo       = new UserRepository();
    $answerRepo     = new AnswerRepository();
    $questionRepo   = new QuestionRepository();
    $quizRepo       = new QuizRepository($questionRepo);


    /**
     * Create User
     */
    /*
    $userController = new UserController($userRepo);
    $userController->createUserAction(["params" => [
        "username" => "SteveisWonder",
        "firstname" => "Steve",
        "lastname" => "Wonderful",
        "password_hash" => "dsfdsffewlkfok2k3lklkl32",
        "email" => "wondersteve@gmail.com"
    ]], "");
    */

    /**
     * Create Quiz
     */
    $quizController = new QuizController($quizRepo, $questionRepo, $answerRepo);
    $quizController->createQuizAction(4, 1, 8, 10);
?>