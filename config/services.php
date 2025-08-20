<?php

    use mnaatjes\mvcFramework\DataAccess\Database;
    use mnaatjes\mvcFramework\DataAccess\ORM;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * Declare DB Instance
     * @var Database $db_instance
     */
    $db_instance = Database::getInstance();

    /**
     * On $container instance
     * - Bind DB Instance
     * - Bind ORM Instance
     */
    $container->setShared("db", $db_instance);
    $container->setShared("orm", function($container){
        return new ORM($container->get("db"));
    });
    $container->setShared(SessionManager::class, new SessionManager());

    /**-------------------------------------------------------------------------*/
    /**
     * Example set Test Controller
     */
    /**-------------------------------------------------------------------------*/
    use App\Controllers\TestController;
    use App\Repositories\TestRepository;

    $container->set(TestController::class, function($container){
        return new TestController(new TestRepository($container->get("orm")));
    });

    /**-------------------------------------------------------------------------*/
    /**
     * User Dependencies
     */
    /**-------------------------------------------------------------------------*/
    use App\Repositories\UserRepository;
    use App\Controllers\UserController;
    use App\Services\UserService;

    // Set User Repository
    $container->set(UserRepository::class, function($container){
        return new UserRepository($container->get("orm"));
    });

    // Set User Service
    $container->set(UserService::class, function($container){
        return new UserService($container->get(UserRepository::class));
    });

    // Set Dependencies
    $container->set(UserController::class, function($container){
        $user_repo = $container->get(UserRepository::class);

        // Assign UserController
        return new UserController(
            $user_repo,
            new UserService($user_repo)
        );
    });

    /**-------------------------------------------------------------------------*/
    /**
     * Quiz Dependencies
     */
    /**-------------------------------------------------------------------------*/
    // Controller
    use App\Controllers\QuizController;

    // Service
    use App\Services\QuizService;
    
    // Repositories
    use App\Repositories\QuizRepository;
    use App\Repositories\AnswerRepository;
    use App\Repositories\QuestionRepository;
    use App\Repositories\UserQuizRepository;

    // Set Dependencies
    $container->set(QuizController::class, function($container){
        // Get ORM instance
        $orm = $container->get("orm");

        // Declare QuizRepo
        $quiz_repo = new QuizRepository($orm);

        // Return Dependency Controller
        return new QuizController(
            $quiz_repo,
            $container->get(UserService::class),
            // Assign Service Dependency
            new QuizService(
                $quiz_repo,
                new UserQuizRepository($orm),
                new QuestionRepository($orm),
                new AnswerRepository($orm)
            )
        );
    });
    
    
?>