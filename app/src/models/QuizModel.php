<?php
    /**
     * Require Model Abstract
     */
    require_once('/var/www/app/utils/mk_timestamp.php');
    require_once('/var/www/app/utils/array_normalize_r.php');
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
        public $quiz_id_map;
        public $description;
        public $category_id;
        public $difficulty_id;
        public $created_at;
        public $updated_at;
        // TODO: Method for determining length of quiz

        /**-------------------------------------------------------------------------*/
        /**
         * Generate Quiz
         * 
         * @param int $cat_id Category ID
         * @param int $diff_id Difficulty ID
         * @param int $user_id User ID for checking played questions
         * @param int $quiz_length Total number of questions
         * 
         * @return array 
         */
        /**-------------------------------------------------------------------------*/
        public function generateQuiz($cat_id, $diff_id, $user_id, $quiz_length=10){
            /**
             * Check for existing records in UserQuiz:
             * - Query UserQuizzes for entries by user_id
             * - Get array of quiz_ids from UserQuiz by user_id
             * - Collect json quiz_id_map rows from corresponding quiz_id where cateogry_id and diff_id match criteria
             * - Consolidate quiz_id_maps into one array of question_ids representing used quizzes
             */
            $quiz_ids = UserQuizModel::findQuizIdsByUser($user_id);
            
            /**
             * Question Ids that exist from previous quizzes by user
             * @var array $question_ids
             */
            $question_ids = [];

            /**
             * Check if previous user quiz entries exist
             */
            if($quiz_ids){
                /**
                 * User has previous entries in user_quizzes:
                 */
                $quiz_maps      = static::findMapsbyCriteria([1, 2, 3, 4, 5], 8, 1);
                $question_ids   = static::processQuizMaps($quiz_maps);
            }

            /**
             * Perform Question Pull
             */
            $questions_arr = QuestionModel::pullQuestions($cat_id, $diff_id, $question_ids, $quiz_length);
            
            /**
             * Create New Record in quizzes table
             */
            $new_quiz_id = static::saveNewQuiz($questions_arr, "New Description", $cat_id, $diff_id);
            var_dump($new_quiz_id);
            /**
             * Create New Record in user_quizzes:
             * - user_id
             * - New quiz_id
             */
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Save New Quiz
         * 
         * @param array $quiz_id_map Quiz associative array from Generate Quiz
         * @param string $description Text string of quiz description
         * @param int $category_id 
         * @param int $difficulty_id
         * 
         */
        /**-------------------------------------------------------------------------*/
        protected static function saveNewQuiz($questions_arr, $description, $category_id, $difficulty_id){

            /**
             * Grab question ids from $questions
             * @var string $quiz_id_map JSON representation of question ids from $questions_arr
             */
            $question_ids = json_encode(array_column($questions_arr, "id"));

            /**
             * Format properties
             * Execute Save
             * Return new PKey value
             */
            return static::save([
                "quiz_id_map"   => $question_ids,
                "description"   => $description,
                "category_id"   => $category_id,
                "difficulty_id" => $difficulty_id,
                "created_at"    => mk_timestamp(),
                "updated_at"    => mk_timestamp()
            ]);

        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * Find Quiz by criteria from list of quiz_ids
         */
        /**-------------------------------------------------------------------------*/
        public static function findMapsbyCriteria($quiz_ids, $category_id, $difficulty_id){
            /**
             * Prepare SQL
             */
            // Create an array of unique named placeholders like :id0, :id1, etc.
            $placeholders = array_map(function($index) {
                return ":id" . $index;
            }, array_keys($quiz_ids));

            // Join them into a string for the IN clause
            $filters = implode(", ", $placeholders);
            
            $sql = "SELECT quiz_id_map FROM " . static::$table_name . " WHERE id IN (" . $filters . ") AND category_id = :category_id AND difficulty_id = :difficulty_id";
            $stmt = static::$db->prepare($sql);
            
            /**
             * Bind Values and Execute
             */
            // Bind each quiz_id to its unique placeholder
            foreach ($quiz_ids as $index => $id) {
                $stmt->bindValue(":id" . $index, $id, PDO::PARAM_INT);
            }
            
            // Bind the other values
            $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
            $stmt->bindValue(":difficulty_id", $difficulty_id, PDO::PARAM_INT);
            
            if($stmt->execute()){
                return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), "quiz_id_map");
            } else {
                return [];
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public static function processQuizMaps($quiz_maps){
            /**
             * Convert strings to arrays
             */
            $data = array_merge(...array_map(function($str){return json_decode($str);}, $quiz_maps));
            asort($data);
            return $data;
        }
        /**-------------------------------------------------------------------------*/
        /**
         * Find Quiz record by JSON quiz_id_map
         * @static
         * 
         * @param string $json
         * 
         * @return bool|int
         */
        /**-------------------------------------------------------------------------*/
        protected static function findByQuizMap($json){
            /**
             * Form SQL
             */
            $sql = "SELECT ".static::$p_key." FROM ".static::$table_name." WHERE CAST(quiz_id_map AS JSON) = CAST(? AS JSON)";

            /**
             * Prepare and Execute
             * - Inject json during execution
             */
            $stmt = static::$db->prepare($sql);
            $stmt->execute([$json]);

            /**
             * Return Column
             */
            return $stmt->fetchColumn();

        }
        
    }