<?php
    /**
     * Web Routes
     */

    use mnaatjes\mvcFramework\SessionsCore\SessionManager;
    use App\Controllers\UserController;
    use App\Controllers\DashboardController;
use App\Controllers\QuizController;
use App\Middleware\UserAuth;
    
    /**-------------------------------------------------------------------------*/
    /**
     * Login and Registration
     * 
     */
    /**-------------------------------------------------------------------------*/
    /**
     * User Login Form
     * 
     * @see GET /
     * @see GET /login
     * @return void
     */
    $router->get("/", function($req, $res) use($container){
        // clear existing sessions
        $container->get(SessionManager::class)->clear();
        // Render Landing Page
        $res->render("login");
    });
    /**
     * 
     */
    $router->get("/login", function($req, $res) use($container){
        // Check for session variable
        $session = $container->get(SessionManager::class);
        $data = ["error" => $session->get("error", NULL)];

        // Render Landing Page
        $res->render("login", $data ?? []);
    });

    /**
     * User login form handling
     * 
     * @see POST /login
     * @return void
     */
    $router->post("/login", [UserController::class, "login"]);

    /**
     * User Registration form 
     * 
     * @see GET /register
     * @return void
     */
    $router->get("/register", function($req, $res) use($container){
        // Check for session
        $session = $container->get(SessionManager::class);
        $data = ["error" => $session->get("error", NULL)];

        // Render Registraton Page
        $res->render("register", $data ?? []);
    });

    /**
     * User Creation after form submission
     * 
     * @see POST/users
     * @return void
     */
    $router->post("/users", [UserController::class, "create"]);

    /**-------------------------------------------------------------------------*/
    /**
     * Dashboard
     */
    /**-------------------------------------------------------------------------*/
    /**
     * GET /dashboard
     */
    $router->get("/dashboard", [DashboardController::class, "index"], [
        function($req, $res, $next) use($container){
            $container->get(UserAuth::class)->handler($req, $res, $next);
        }
    ]);

    /**
     * GET /quizzes/create
     * Route to form for creating new Quiz
     */
    $router->get("/quizzes/create", function($req, $res, $params){
        // Render form page
        $res->render("create_quiz");
    });

    /**
     * POST /quizzes/create
     * Route to QuizController to create new record in quizzes table
     * @uses QuizController->create()
     * @uses UserAuth->handler()
     * @return void
     */
    $router->post("/quizzes/create", [QuizController::class, "create"], [
        function($req, $res, $next) use($container){
            $container->get(UserAuth::class)->handler($req, $res, $next);
        }
    ]);

    /**
     * GET /quizzes/{quiz_id}
     * Route to play quiz
     * 
     * @uses QuizController->show()
     * @uses UserAuth->handler()
     * @return void
     */
    $router->get("/quizzes/{quiz_id}", [QuizController::class, "show"], [
        function($req, $res, $next) use($container){
            $container->get(UserAuth::class)->handler($req, $res, $next);
        }
    ]);

    /**
     * POST /quizzes/{quiz_id}/submit
     * Route for submitted quiz after play / abandon
     * 
     * @uses QuizController->submit()
     * @uses UserAuth->handler()
     * @return void
     */
    $router->post("/quizzes/{quiz_id}/submit", [QuizController::class, "submit"], [
        function($req, $res, $next) use($container){
            $container->get(UserAuth::class)->handler($req, $res, $next);
        }
    ]);

    


?>