<?php
    /**
     * Require Model Abstract
     */
    //require_once('/var/www/app/utils/math_nCr.php');
    require_once('/var/www/app/src/models/Model.php');

    /**-------------------------------------------------------------------------*/
    /**
     * Difficulties Model
     */
    /**-------------------------------------------------------------------------*/
    class QuestionModel extends Model {
        /**
         * Model Properties
         */
        protected static $table_name = 'questions';
        protected static $p_key = 'id';
        /**
         * Object Properties
         */
        public $id;
        public $question_text;
        public $type;
        public $category_id;
        public $created_at;
        public $updated_at;
        public $is_active;
        public $image_url;
        public $hint_text;
        public $source_reference;
        public $times_asked;
        public $correct_attempts_count;
        public $incorrect_attempts_count;
        public $difficulty_id;
        public $skip_count;
        public $total_time_spent_seconds;
        public $last_stat_update_at;
        public $last_played_at;


        /**-------------------------------------------------------------------------*/
        /**
         * Grabs records that correspond to criteria
         * - Questions are selected at random within criteria
         * - Questions are sorted by ID ASC
         * 
         * @return array
         */
        /**-------------------------------------------------------------------------*/
        public static function pullQuestions($cat_id, $diff_id, $quiz_length){
            /**
             * @var array $questions Records from questions table
             */
            $questions = [];

            /**
             * Form SQL
             */
            $cols   = "id, question_text, type, image_url, hint_text, source_reference";
            $sql    = "SELECT ".$cols." FROM ".static::$table_name." WHERE category_id = :category_id AND difficulty_id = :difficulty_id AND is_active = 1 ORDER BY RAND() LIMIT ".$quiz_length;
            $stmt   = static::$db->prepare($sql);
            
            /**
             * Bind Parameters
             */
            $stmt->bindParam(':category_id', $cat_id, PDO::PARAM_INT);
            $stmt->bindParam('difficulty_id', $diff_id, PDO::PARAM_INT);
            
            /**
             * Execute Query
             */
            $stmt->execute();
            $questions = $stmt->fetchAll();

            /**
             * Verify and Return
             */
            if(empty($questions)){
                throw new Exception("Unable to list questions that matches Criteria");
            }
            /**
             * Sort Question IDs ASC
             */
            asort($questions);

            /**
             * @var array $results
             */
            $results = [];
            
            /**
             * Pull Questions
             */
            foreach($questions as $q){
                /**
                 * Declare Object
                 */
                $item = $q;
                $item["answers"] = [];
                
                /**
                 * Grab question id and perform query
                 */
                $question_id = $q["id"];
                $item["answers"] = AnswerModel::pullAnswers($question_id);
                
                /**
                 * Push Item to Results
                 */
                $results[] = $item;
            }

            /**
             * TODO: Validate
             * Return results
             */
            return $results;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Count Distinct Questions
         */
        /**-------------------------------------------------------------------------*/
        public static function countDistinct($category_id, $difficulty_id, $group_size){
            /**
             * Count floor by limit with properties:
             * - Form SQL
             * - Bind Properties
             * - Execute and return
             */
            $sql    = "SELECT FLOOR(COUNT(*) / ". $group_size .") AS distinct_groups_of_10 FROM questions WHERE category_id = :category_id AND difficulty_id = :difficulty_id";
            $stmt   = static::$db->prepare($sql);
            // Bind
            $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindValue(':difficulty_id', $difficulty_id, PDO::PARAM_INT);
            // Execute
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        }
    }