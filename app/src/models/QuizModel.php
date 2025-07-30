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
        // TODO: Add column for json of quiz
        // TODO: Method for determining length

        /**-------------------------------------------------------------------------*/
        /**
         * Collect Quiz
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
             * Generate Quiz Seed
             */

            /**
             * Write Quiz array to JSON
             */
            $json = json_encode($quiz, JSON_PRETTY_PRINT);
            var_dump($json);
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Generate Quiz Seed
         * 
         * @param int $cat_id Category ID
         * @param int $diff_id Difficulty ID
         * @param array $quiz_id_arr Array of quiz IDs
         * 
         * @return string $seed
         */
        /**-------------------------------------------------------------------------*/
        protected function generateSeed($cat_id, $diff_id, $quiz_id_arr){}
    }