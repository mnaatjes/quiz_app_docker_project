<?php

    /**
     * Require Utils and Framework
     */
    require_once('/var/www/app/utils/enable_errors.php');
    require_once('/var/www/app/utils/db_connect.php');
    require_once('/var/www/app/simple_http_manager/SimpleHttpManager.php');
    /**
     * Require Models, Controllers, and other Objects
     */
    require_once('/var/www/app/src/models/UserModel.php');
    require_once('/var/www/app/src/models/DifficultyModel.php');
    require_once('/var/www/app/src/models/QuizModel.php');
    /**
     * Enable Utilities
     */
    enable_errors();
    
    /**
     * Attempt PDO Connection to DB
     */
    $dbConfig = require('/var/www/app/config/db_config.php');
    
    if(empty($dbConfig)){
        throw new Exception('Unable to identify Database Configuration!');
    }

    /**
     * @var object|null PDO connection
     */
    $pdo = db_connect($dbConfig);
    
    /**
     * @var object User
     */
    $user = new UserModel($pdo);
    $diff = new DiffModel($pdo);
    $quiz = new QuizModel($pdo);

    /**
     * Debugging
     */
    var_dump($diff->getProp(4, "description"));

?>