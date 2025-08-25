<?php
    /**
     * routes/web.php
     *
     * This file is where you can register web routes for your application. These
     * routes are loaded by the RouteServiceProvider and all of them will be
     * assigned to the "web" middleware group.
     *
     * This file defines the public-facing pages of the application, such as
     * the home page, authentication routes, and other user-facing interfaces.
     *
     * @package App\Http\Routes
     * 
     * @since 1.0.0
     * - Created
     * - Refactored
     * - Added docbloc comments
     * 
     * @version 1.1.0
     */

use App\Controllers\QuizController;
use App\Middleware\UserAuth;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * Web Routes:
     * - /
     * - /register
     * - /dashboard
     * - /quiz/create
     * - /quiz/play
     */

    /**-------------------------------------------------------------------------*/
    /**
     * GET /
     *
     * Renders the home page of the application.
     *
     * This route is the main entry point for the website and serves the
     * primary landing page.
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    /*
    $router->get("/", function($req, $res) use($container){
        // clear existing sessions
        $container->get(SessionManager::class)->clear();
        // Render Landing Page
        $res->render("landing");
    });

    /**-------------------------------------------------------------------------*/
    /**
     * GET /
     *
     * Renders the registration page of the application.
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    $router->get("/register", function($req, $res){
        // Render Registraton Page
        $res->render("register");
    });

    /**-------------------------------------------------------------------------*/
    /**
     * GET /
     *
     * Renders the dashboard page of the application.
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    $router->get("/dashboard", [QuizController::class, "index"], [function($req, $res, $next) use($container){
        $container->get(UserAuth::class)->handler($req, $res, $next);
    }]);

    /**-------------------------------------------------------------------------*/
    /**
     * GET /
     *
     * Renders the dashboard page of the application.
     *
     * @return void
     */
    /**-------------------------------------------------------------------------*/
    /*
    $router->get("/quiz/{quiz_id}", [QuizController::class, "show"], [function($req, $res, $next) use($container){
        $container->get(UserAuth::class)->handler($req, $res, $next);
    }]);
    */

?>