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
         * Returns one instance
         * Called by router->post(/login)
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{
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
                    // To Dashboard
                    $res->redirect("/index.php/dashboard");
                } else {
                    // To Fail-Login
                    $res->redirect("/index.php/login-fail");
                }
            }
        }
    }
?>
