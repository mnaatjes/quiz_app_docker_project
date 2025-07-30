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
    class UserQuizModel extends Model {
        /**
         * Model Properties
         */
        protected static $table_name = 'user_quizzes';
        protected static $p_key = 'id';
        /**
         * Object Properties
         */
        public $id;
        public $user_id;
        public $quiz_id;
        public $started_at;
        public $completed_at;
        public $score;
        public $total_questions;
        public $correct_answers_count;
        public $incorrect_answers_count;
        public $skipped_questions_count;
        public $time_taken_seconds;
        public $is_completed;
        public $status;
        public $last_activity_at;
        public $created_at;
        public $updated_at;

    }