<?php

    namespace App\Services;
    use App\Models\UserModel;

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

        /**-------------------------------------------------------------------------*/
        /**
         * Authenticator
         * 
         * @return NULL|UserModel
         */
        /**-------------------------------------------------------------------------*/
        public function authenticate(?array $data): ?UserModel{

            // Validate
            if(!is_array($data) || (is_array($data) && count($data) !== 1)){
                // Dump Error
                return NULL;

            } elseif(is_a($data[0], UserModel::class)){
                // Grab User Model Object
                // Return User Object
                return $data[0];

            } else {
                // Dump Error
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
    }
?>