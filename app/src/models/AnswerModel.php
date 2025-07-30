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
    class AnswerModel extends Model {
        /**
         * Model Properties
         */
        protected static $table_name = 'answers';
        protected static $p_key = 'id';
        /**
         * Object Properties
         */
        public $id;
        public $question_id;
        public $is_correct;
        public $answer_text;
        public $created_at;
        public $updated_at;
        public $explaination_text;
        public $times_shown;
        public $selection_count;

        /**-------------------------------------------------------------------------*/
        /**
         * Returns answer record by question id
         * @static
         */
        /**-------------------------------------------------------------------------*/
        public static function pullAnswers($question_id){

            /**
             * @var array $records
             */
            $records = [];

            /**
             * Form query
             * TODO: Columns
             */
            $cols   = "id, is_correct, answer_text, explanation_text";
            $sql    = "SELECT ".$cols." FROM " .static::$table_name. " WHERE question_id = :question_id";
            $stmt   = static::$db->prepare($sql);
            $stmt->bindValue("question_id", $question_id, PDO::PARAM_INT);
            /**
             * Perform Query
             */
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            /**
             * TODO: Form Records into Models of Answers
             * Verify and Return
             */
            if(empty($records)){
                throw new Exception("Unable to collate answers for question_id: ".$question_id);
            }
            return $records;
        }
    }