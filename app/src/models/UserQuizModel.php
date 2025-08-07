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

        /**-------------------------------------------------------------------------*/
        /**
         * Store UserQuiz: Create / Insert in DB table
         */
        /**-------------------------------------------------------------------------*/
        public static function store($user_id, $quiz_id){
            /**
             * Check if quiz_id exists in records
             */
            $exists = static::userOwnsQuiz($user_id, $quiz_id);
            
            /**
             * Save to user_quizzes table 
             * - Insert new 
             * - Update existing
             */
            return static::save([
                "last_activity_at"  => mk_timestamp(),
                "updated_at"        => mk_timestamp()
            ], is_int($exists) ? $exists : NULL );

        }

        /**-------------------------------------------------------------------------*/
        /**
         * Find User Quizzes
         * 
         * @return array Array of quiz_ids
         */
        /**-------------------------------------------------------------------------*/
        public static function findQuizIdsByUser($user_id){
            /**
             * Grab records from UserQuizzes
             */
            $quiz_ids = static::getPropsByParams(["quiz_id"], ["user_id" => $user_id]);
            
            /**
             * Parse and return simple array
             */
            if(!empty($quiz_ids)){
                return array_column($quiz_ids, "quiz_id");
            } else {
                return [];
            }

        }

        
        /**-------------------------------------------------------------------------*/
        /**
         * CRUD Method: Record Exists in table by quiz_id and user_id Foreign Keys
         * 
         * @param int $f_key
         * 
         * @return bool|int
         */
        /**-------------------------------------------------------------------------*/
        public static function userOwnsQuiz($f_key_quiz, $f_key_user){
            /**
             * Form SQL
             */
            $sql    = "SELECT ".static::$p_key." FROM ".static::$table_name." WHERE quiz_id = :quiz_id AND user_id = :user_id LIMIT 1";
            $stmt   = static::$db->prepare($sql);
            /**
             * Bind Properties
             */
            $stmt->bindParam(":quiz_id", $f_key_quiz, PDO::PARAM_INT);
            $stmt->bindParam(":user_id", $f_key_user, PDO::PARAM_INT);
            $stmt->execute();

            /**
             * Fetch and return boolean
             * - Check if boolean or int
             * - Parse value
             */
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            if(is_array($record) && isset($record["id"])){
                /**
                 * Record Exists, return UserQuiz ID
                 */
                return $record["id"];
            } else {
                /**
                 * Record does not exist, return bool false
                 */
                return false;
            }
        }

    }