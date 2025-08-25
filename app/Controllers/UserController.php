<?php
    namespace App\Controllers;

use App\Models\UserModel;
use mnaatjes\mvcFramework\DataAccess\BaseRepository;
use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    /**-------------------------------------------------------------------------*/
    /**
     * User Controller inhereting BaseController
     * 
     * @since 1.0.0
     * - Removed __construct
     * - Reset show
     * - Removed $this->service;
     * 
     * @version 1.1.0
     */
    /**-------------------------------------------------------------------------*/
    class UserController extends AppController {

        /**-------------------------------------------------------------------------*/
        /**
         * User Login Controller Method
         * 
         * Takes a POST request and processes user login with UserService
         * 
         * @uses UserService@login
         * @return RedirectResponse
         * 
         * @see GET /users/{user_id} on Success
         * @see GET /login on Failure
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function login($req, $res, $params){
            /**
             * UserService:
             * - Collect parameters
             * - Sanitize
             * - Repository:
             *      -> Generate User Model
             * - Set Session
             */
            $postParams = $req->getPostParams();
            if(empty($postParams)){
                // TODO: 
                // Redirect
            }

            // Check
            if(isset($postParams["username"]) && isset($postParams["password"])){
                // Call UserService Login() method
                $logged_in = $this->UserService->login(
                    $postParams["username"],
                    $postParams["password"]
                ); 

                // Path Fail / Success
                if($logged_in === true){
                    // Remove existing error from session
                    if($this->ErrorService->hasSession()){
                        $this->ErrorService->removeSession();
                    }
                    // Route to dashboard
                    $res->redirect("/index.php/dashboard");
                } else {
                    /**
                     * Set error session and return
                     * 
                     * @uses ErrorService
                     * @see GET /login
                     */
                    // Set error session
                    $this->ErrorService->setSession("Unable to Log in!");

                    $res->redirect("/index.php/login");
                }
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * User Registration Controller Method
         * - Validates and sanitized params
         * - Creates new record in users table
         * 
         * @uses UserService@register
         * 
         * @see POST /login on Success
         * @see GET /register on Failure
         */
        /**-------------------------------------------------------------------------*/
        public function create(HttpRequest $req, HttpResponse $res, array $params): void{
            /**
             * @uses UserService->register()
             */
            $result = $this->UserService->register(
                $req->getPostParam("username"),
                $req->getPostParam("password"),
                $req->getPostParam("email")
            );
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{}
    }
?>
