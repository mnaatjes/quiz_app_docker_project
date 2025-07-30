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
    class QuizModel extends Model {
        /**
         * Model Properties
         */
        protected static $table_name = 'quizzes';
        protected static $p_key = 'id';
        /**
         * Object Properties
         */
        public $id;
        public $quiz_seed;
        public $description;
        public $category_id;
        public $difficulty_id;
        public $created_at;
        public $updated_at;

    }