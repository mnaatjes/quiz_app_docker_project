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
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, array $params): void{
            /**
             * Unset Session
             */
            /**
             * Authentication:
             * - Collect parameters
             * - Sanitize
             * - Authenticate
             * - Generate User Model
             */

            /**
             * Select Path:
             * - Login
             * - Re-register
             */
        }
    }
?>
