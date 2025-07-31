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
     * Require Models, Controllers, and other Objects
     */
    require_once('/var/www/app/src/models/UserModel.php');
    require_once('/var/www/app/src/models/DifficultyModel.php');
    require_once('/var/www/app/src/models/QuizModel.php');
    require_once('/var/www/app/src/models/UserQuizModel.php');
    require_once('/var/www/app/src/models/CategoryModel.php');
    require_once('/var/www/app/src/models/AnswerModel.php');
    require_once('/var/www/app/src/models/QuestionModel.php');

    /**
     * Controllers
     */
    require_once('/var/www/app/src/controllers/get_criteria.php');

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
     * Models
     */
    $user       = new UserModel($pdo);
    $diff       = new DiffModel($pdo);
    $quiz       = new QuizModel($pdo);
    $user_quiz  = new UserQuizModel($pdo);
    $category   = new CategoryModel($pdo);
    $answer     = new AnswerModel($pdo);
    $question   = new QuestionModel($pdo);

    /**
     * Create Quiz Flow:
     * 1) Select Criteria
     * 2) Pull Questions
     * 3) Generate Quiz Seed
     * 4) Create 'Quizzes' Record
     * 5) Create / Update 'UserQuizzes' Record
     * 6) Update User Record
     */
    $user->id = 12;
    $user->load();
    /**
     * 1) Select Criteria and Send Back to User
     */
    $criteria = get_criteria($pdo);

    /**
     * TODO: Send
     */

    /**
     * 2) Pull Questions
     * - Dummy Data from Categories
     * - URI: /user/12/diff/2/cat/5
     * - Grab Questions
     * - Grab associated Answers
     */
    $uri = [
        "user"  => 8,
        "diff"  => 1,
        "cat"   => 14
    ];

    $quiz->generateQuiz($uri["cat"], $uri["diff"], $uri["user"]);
    
?>