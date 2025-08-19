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
     */
    /**-------------------------------------------------------------------------*/
    class UserController extends AppController {

        /**
         * @var Object $service
         */
        private object $service;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(BaseRepository $repository, $user_service){
            // Assign Service
            $this->service = $user_service;

            // Invoke BaseRepository Construct
            parent::__construct($repository);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Returns one instance
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{
            /**
             * Collect Parameters:
             * - Sanitize
             */
            $username = $req->getPostParam("username");
            $password = $req->getPostParam("password");

            /**
             * Attempt to assign model:
             * - Grab Record from repository
             */
            // Search for Username
            $result = $this->repository->findBy(["username" => $username]);

            // Authenticate
            $user = $this->service->authenticate($result);

            // Select Path
            if(is_null($user)){
                // Redirect to login
                $res->render("/landing", ["title" => "Failure to Authenticate"]);

            } else {
                // Redirect to Dashboard
                $res->redirect("/index.php/dashboard");
            }
        }
    }
?>
