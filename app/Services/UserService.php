<?php

    namespace App\Services;
    use App\Models\UserModel;
use App\Repositories\UserRepository;
use App\Utils\Utility;
use mnaatjes\mvcFramework\HttpCore\HttpRequest;
use mnaatjes\mvcFramework\HttpCore\HttpResponse;
use mnaatjes\mvcFramework\MVCCore\BaseModel;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**-------------------------------------------------------------------------*/
    /**
     * UserService Class associated with UserController, UserRepo, UserModel
     * 
     * @since 1.0.0:
     * - Created
     * - Added authernticate(), login(), hashPassword() methods
     * - Removed authenticate() --> now a middleware method
     * - Removes session methods from User Service --> now part of SessionManager
     * 
     * @version 1.0.0
     * 
     */
    /**-------------------------------------------------------------------------*/
    class UserService {

        /**
         * @var SessionManager
         */
        protected SessionManager $session;

        /**
         * @var UserRepository $repository
         */
        protected UserRepository $repository;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(UserRepository $user_repository, SessionManager $session_manager){
            // Set Repo
            $this->repository = $user_repository;

            // Session
            $this->session = $session_manager;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Get Logged In User properties stored in the session
         */
        /**-------------------------------------------------------------------------*/
        public function getUserFromSession(){
            if(!$this->session->has("user_id")){
                // Failure
                return NULL;
            }

            // Return user id
            return $this->session->get("user_id", NULL);
        }


        /**-------------------------------------------------------------------------*/
        /**
         * Hash Password
         */
        /**-------------------------------------------------------------------------*/
        private function hashPassword(string $password){
            // TODO: perform hash
            return $password;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Login Method
         */
        /**-------------------------------------------------------------------------*/
        public function login(string $username, $password): bool{

            // Has Password Value
            $password_hash = $this->hashPassword($password);

            // Check DB table for record
            $record = $this->repository->findBy([
                "username" => $username,
                "password_hash" => $password_hash
            ]);

            // Validate Record
            if(is_array($record) && count($record) === 1 && is_a($record[0], BaseModel::class)){
                // Model Exists and is valid
                $model = $record[0];

                // Set Session & user_id property
                $this->session->start();
                $this->session->set("user_id", $model->getId());
                $this->session->set("username", $model->getUsername());

                // TODO: Update updated_at, last_login_at properties
                
                // Return boolean on success
                return true;

            } else {
                // Return false on failure  
                return false;
            }
            
        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * Load
         */
        /**-------------------------------------------------------------------------*/
        public function load(): ?UserModel{
            // Check for user_id
            if(!$this->session->has("user_id")){
                return NULL;
            }

            // Check for record
            $record = $this->repository->findById(
                $this->session->get("user_id")
            );

            if(is_a($record, UserModel::class)){
                return $record;
            }

            // Default
            return NULL;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Register user:
         * - Create new user record
         * - Populate new model and return
         */
        /**-------------------------------------------------------------------------*/
        public function register($username, $password, $email){
            // Hash Password
            $password_hash = $this->hashPassword($password);

            // Align properties, create model, and perform save()
            $model = $this->repository->save(new UserModel([
                "username"      => $username,
                "email"         => $email,
                "password_hash" => $password_hash,
                "first_name"    => "asdasdsa",
                "last_name"     => "sdadsdasa",
                "created_at"    => Utility::createTS(),
                "updated_at"    => Utility::createTS(),
                "is_active"     => 1,
                "last_login_at" => Utility::createTS()
            ]));

            // Validate Insert
            if(is_a($model, UserModel::class)){
                // Return Model on success
                return $model;
            }
            else {
                // Return on failure
                return NULL;
            }
        }
    }
?>