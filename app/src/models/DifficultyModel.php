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

    }
    