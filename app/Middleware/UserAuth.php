<?php

namespace App\Middleware;

use App\Utils\Utility;
use mnaatjes\mvcFramework\HttpCore\HttpRequest;
use mnaatjes\mvcFramework\HttpCore\HttpResponse;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * UserAuth Middleware
     */
    class UserAuth {

        /**
         * @var SessionManager
         */
        protected SessionManager $session;

        /**
         * Construct
         */
        public function __construct(SessionManager $session_manager){
            // Register SessionManager
            $this->session = $session_manager;
        }

        /**
         * Handler Method
         */
        public function handler(HttpRequest $req, HttpResponse $res, callable $next){
            /**
             * Auth Flow:
             * - Check if session exists
             * - NO: 
             *      -> Redirect
             * - YES:
             *      -> Next()
             */
            if($this->session->has("user_id") && is_numeric($this->session->get("user_id"))){
                // User Authenticated -> Proceed to original destination
                $next();
            } else {
                // Destroy Session
                $this->session->destroy();

                // Redirect with error message
                $res->redirect("/index.php/login?error=" . "100");
            }
        }
    }

?>