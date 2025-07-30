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
    class CategoryModel extends Model {
        /**
         * Model Properties
         */
        protected static $table_name = 'categories';
        protected static $p_key = 'id';
        /**
         * Object Properties
         */
        public $id;
        public $name;
        public $quiz_id;
        public $description;
        public $slug;
        public $image_url;
        public $icon_name;
        public $sort_order;
        public $is_active;
        public $created_at;
        public $updated_at;
        public $parent_category_id;
        public $selection_count;

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
            $sql    = "SELECT " . static::$p_key . ", name, description, slug, image_url, icon_name FROM " . static::$table_name . " WHERE is_active = 1";
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