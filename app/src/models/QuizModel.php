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
         */
        /**-------------------------------------------------------------------------*/
        public function generateQuiz($cat_id, $diff_id, $user_id, $quiz_length=10){
            /**
             * Collect random Question IDs by cat_id and diff_id
             */
            $quiz = QuestionModel::pullQuestions($cat_id, $diff_id, $quiz_length);
            
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
            
            $json = '
                [
                    {
                        "answer_ids": [
                            80,
                            46392
                        ],
                        "question_id": 80
                    },
                    {
                        "answer_ids": [
                            110,
                            46422
                        ],
                        "question_id": 110
                    },
                    {
                        "answer_ids": [
                            391,
                            46703,
                            92878,
                            132890
                        ],
                        "question_id": 391
                    },
                    {
                        "answer_ids": [
                            515,
                            46827,
                            92956,
                            132968
                        ],
                        "question_id": 515
                    },
                    {
                        "answer_ids": [
                            622,
                            46934,
                            93039,
                            133051
                        ],
                        "question_id": 622
                    },
                    {
                        "answer_ids": [
                            968,
                            47280,
                            93331,
                            133343
                        ],
                        "question_id": 968
                    },
                    {
                        "answer_ids": [
                            993,
                            47305
                        ],
                        "question_id": 993
                    },
                    {
                        "answer_ids": [
                            1157,
                            47469,
                            93476,
                            133488
                        ],
                        "question_id": 1157
                    },
                    {
                        "answer_ids": [
                            1198,
                            47510,
                            93507,
                            133519
                        ],
                        "question_id": 1198
                    },
                    {
                        "answer_ids": [
                            1288,
                            47600
                        ],
                        "question_id": 1288
                    }
                ]
            ';
            
            /**
             * Check if map exists in any entries
             */
            $exists = static::findByQuizMap($json);

            if(is_int($exists)){
                /**
                 * Map exists in quizzes table:
                 * - Get number of possible quiz generations
                 * - Loop until solution found
                 */
                
                $distinct = QuestionModel::countDistinct($cat_id, $diff_id, $quiz_length);

                var_dump($distinct);
                
            } else {
                var_dump("DOES NOT EXIST");
                /**
                 * Map does NOT exist in quizzes table:
                 * - Perform Save and Return results
                 * - Validate
                 * - Assign id to model object
                 */
                /*
                $result = static::save([
                    "quiz_id_map"   => $json,
                    "description"   => "",
                    "category_id"   => $cat_id,
                    "difficulty_id" => $diff_id,
                    "created_at"    => mk_timestamp(),
                    "updated_at"    => mk_timestamp()
                ]);
                */
            }
            /**
             * Evaluate:
             * - On success, load into model
             * - On failure, TODO: Error handling
             */
            //var_dump($result);
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