<?php
    /**
     * Require Model Abstract
     */
    require_once('/var/www/app/src/models/Model.php');

    /**-------------------------------------------------------------------------*/
    /**
     * Difficulties Model
     */
    /**-------------------------------------------------------------------------*/
    class DiffModel extends Model {
        /**
         * Model Properties
         */
        protected static $table_name = 'difficulties';
        protected static $p_key = 'id';
        /**
         * Difficulties Object Properties
         */
        public $id;
        public $name;
        public $level_value;
        public $description;
        public $created_at;
        public $updated_at;

        /**-------------------------------------------------------------------------*/
        /**
         * Select Criteria
         */
        /**-------------------------------------------------------------------------*/
        public function listCriteria(){
            /**
             * @var array $results
             */
            $results = [];

            /**
             * Form Query
             */
            $sql    = "SELECT " . static::$p_key . ", name, level_value, description FROM " . static::$table_name;
            $stmt   = self::$db->prepare($sql);

            /**
             * Execute and return value
             */
            $stmt->execute();
            $results = $stmt->fetchAll();

            if(empty($results)){
                throw new Exception("Unable to load Criteria!");
            }
            return $results;
        }
    }
    