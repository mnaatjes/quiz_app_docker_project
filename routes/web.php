<?php
    /**
     * Web Routes
     */

    use mnaatjes\mvcFramework\SessionsCore\SessionManager;
    use App\Controllers\UserController;
    use App\Controllers\DashboardController;
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


?>