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
         */
        /**-------------------------------------------------------------------------*/
        public function generateQuiz($cat_id, $diff_id, $user_id, $quiz_length=10){
            /**
             * Collect random Question IDs by cat_id and diff_id
             */
            $quiz = QuestionModel::pullQuestions($cat_id, $diff_id, $quiz_length, $user_id);
            
            /**
             * @var array $quiz_id_arr Collector for quiz ids
             */
            $quiz_id_arr = [];
            foreach($quiz as $question){
                /**
                 * Collect Question IDs
                 */
                $curr = [
                    "question_id"   => $question["id"],
                    "answer_ids"    => []
                ];
                /**
                 * Collect Answer IDs
                 */
                foreach($question["answers"] as $answer){
                    $curr["answer_ids"][] = $answer["id"];
                }
                /**
                 * Push to array
                 */
                $quiz_id_arr[] = $curr;
            }
            /**
             * Convert to JSON
             */
            $json = json_encode($quiz_id_arr, JSON_PRETTY_PRINT);

            /**
             * Create Record in Quizzes Table
             */

            /**
             * Create Entry in UserQuizzes Table
             */
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Create Quiz: Create in DB table
         * 
         * @return int $quiz_id
         */
        /**-------------------------------------------------------------------------*/
        private function createQuiz($json){

        }

        /**-------------------------------------------------------------------------*/
        /**
         * Create UserQuizzes: Create UserQuizzes Record
         */
        /**-------------------------------------------------------------------------*/
        private function updateUserQuizzes($user_id, $quiz_id, $quiz_length, ){
            
        }
        
    }