<?php

    /**
     * config/Services/QuizServices.php
     */

use App\Controllers\QuizController;
use App\Models\DifficultyModel;
use App\Models\QuestionModel;
use App\Repositories\AnswerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DifficultyRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizRepository;
use App\Repositories\UserQuizRepository;
use App\Services\QuizService;
use App\Services\UserService;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;
use App\Services\ErrorService;

    /**
     * Bind the Session Manager singleton to DI Container
     *
     * This service provides an instance of the SessionManager
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \App\Controllers\QuizService
     */
    $container->set(QuizService::class, new QuizService(
        new QuizRepository($container->get("orm")),
        new UserQuizRepository($container->get("orm")),
        new QuestionRepository($container->get("orm")),
        new AnswerRepository($container->get("orm")),
        new CategoryRepository($container->get("orm")),
        new DifficultyRepository($container->get("orm")),
        $container->get(SessionManager::class)
    ));

    /**
     * Bind the Session Manager singleton to DI Container
     *
     * This service provides an instance of the SessionManager
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \App\Controllers\QuizController
     */
    $container->set(QuizController::class, new QuizController(
        $container->get(QuizService::class),
        $container->get(UserService::class),
        $container->get(ErrorService::class)
    ));


?>