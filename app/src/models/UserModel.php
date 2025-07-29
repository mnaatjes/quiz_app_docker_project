<?php
    /**
     * Require Model Abstract
     */
    require_once('/var/www/app/src/models/Model.php');

    /**
     * User Model
     * 
     * @var object UserModel
     * @version 1.0
     * @since 1.0:
     *      
     */
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

        /**
         * Create User
         * 
         */
        public function createUser($data){
            
            /**
             * TODO: Validate
             */

            /**
             * Fill Data
             */
            parent::fill($data);

            /**
             * Perform Save
             */
            parent::save();

        }
    }