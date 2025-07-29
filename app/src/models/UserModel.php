<?php
    /**
     * Require Model Abstract
     */
    require_once('/var/www/app/src/models/Model.php');

    /**-------------------------------------------------------------------------*/
    /**
     * User Model
     * 
     * @var object UserModel
     * @version 1.0
     * @since 1.0:
     *      
     */
    /**-------------------------------------------------------------------------*/
    class UserModel extends Model{
        /**
         * Model Properties
         */
        protected static $table_name = 'users';
        protected static $p_key = 'id';
        /**
         * User Object Properties
         */
        public $id;
        public $username;
        public $email;
        protected $password_hash;
        public $first_name;
        public $last_name;
        public $created_at;
        public $updated_at;
        public $is_active;
        public $last_login_at;

        /**-------------------------------------------------------------------------*/
        /**
         * Load User
         */
        /**-------------------------------------------------------------------------*/
        public function loadUser(): bool{
            /**
             * Run get()
             */
            $result = $this->get();
            
            /**
             * Verify Result
             */
            if(empty($result)){
                throw new Exception("Unable to find User!");
            }

            /**
             * Fill model object
             */
            $this->fill($result);

            /**
             * Return Default
             */
            return true;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Create User
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function createUser($data){
            
            /**
             * TODO: Validate
             */

            /**
             * Fill Data
             */
            $this->fill($data);
            /**
             * Perform Save and Return results
             */
            return $this->save();
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update User
         */
        /**-------------------------------------------------------------------------*/
        public function updateUser(){
            /**
             * TODO: Validate
             */

            /**
             * Perform Save and return
             */
            return $this->save();
        }
    }