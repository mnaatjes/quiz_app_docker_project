<?php

    namespace App\Services;
    use App\Models\UserModel;
use App\Repositories\UserRepository;
use mnaatjes\mvcFramework\MVCCore\BaseModel;

    /**-------------------------------------------------------------------------*/
    /**
     * UserService Class associated with UserController, UserRepo, UserModel
     * 
     * @since 1.0.0:
     * - Created
     * - Added authernticate(), login(), hashPassword() methods
     * 
     * @version 1.0.0
     * 
     */
    /**-------------------------------------------------------------------------*/
    class UserService {

        protected UserRepository $repository;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(UserRepository $user_repository){
            // Set Repo
            $this->repository = $user_repository;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Authenticator
         * 
         * @return NULL|UserModel
         */
        /**-------------------------------------------------------------------------*/
        public function authenticate(array $params): ?UserModel{

            // Validate Params
            if(!is_array($params) || (!isset($params["username"]) && !isset($params["password"]))){
                // Dump Error
                return NULL;
            }

            // Check Database table
            $record = $this->repository->findBy(["username" => $params["username"]]);

            // Validate Record
            if(is_array($record) && is_a($record[0], UserModel::class)){
                // Return User Model
                return $record[0];

            } else {
                // Failure to find record
                return NULL;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Hash Password
         */
        /**-------------------------------------------------------------------------*/
        public function hashPassword(string $password){
            // TODO: perform hash
            return $password;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Login Method
         * - Creates / Starts Session
         */
        /**-------------------------------------------------------------------------*/
        public function login(){}
        
        /**-------------------------------------------------------------------------*/
        /**
         * Get User Id
         */
        /**-------------------------------------------------------------------------*/
        public function getUserId(string $username, string $password){
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Start User Session
         */
        /**-------------------------------------------------------------------------*/
        public function startSession(BaseModel $user): void{
            /**
             * Set Session and store user data
             */
            session_start();
            $_SESSION["user_id"]        = $user->getId();
            $_SESSION["username"]       = $user->getUsername();
            $_SESSION["is_logged_in"]   = true;
        }
        

        /**-------------------------------------------------------------------------*/
        /**
         * Check User Session is Valid
         */
        /**-------------------------------------------------------------------------*/
        public function isValidSession(): bool{
            // Start Session
            session_start();

            // Validate is_active and get user_id
            if(!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
                return false;
            }

            // Validate user id against db
            $result = $this->repository->findById($_SESSION["user_id"]);
            
            // Validate result
            if(is_object($result) && is_a($result, UserModel::class)){
                return $_SESSION["username"] === $result->getUsername();
            }

            // Return default
            return false;
        }
        
        /**-------------------------------------------------------------------------*/
    }
?>