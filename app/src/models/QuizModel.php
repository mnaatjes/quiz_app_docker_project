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
             * @var array $quiz_id_arr
             */
            $quiz_id_arr = [];
            foreach($quiz as $question){
                /**
                 * Collector
                 */
                $curr = [
                    "question_id"   => $question["id"],
                    "answer_ids"    => [],
                    "answer_id_str" => NULL,
                    "answers_serialized" => NULL
                ];
                // cycle answers
                foreach($question["answers"] as $answer){
                    $curr["answer_ids"][]   = $answer["id"];
                    $curr["answer_id_str"] = is_null($curr["answer_ids"]) ? $answer["id"] : $curr["answer_id_str"]."_".$answer["id"];
                }
                /**
                 * Serialize Answers
                 */
                $answers_json   = json_encode($curr["answer_ids"]);
                $answers_serial = serialize($curr["answer_ids"]);
                $answers_gzc    = gzcompress($answers_json);
                $answers_gze    = gzcompress($answers_json);
                /**
                 * Debugging
                 */
                /*
                var_dump("JSON: " . $answers_json . " | Length: ".strlen($answers_json)." bytes");
                var_dump("Serial: " . $answers_serial . " | Length: ".strlen($answers_serial)." bytes");
                var_dump("GZ Compress: ". $answers_gzc . " | Length: ".strlen($answers_gzc)." bytes");
                var_dump("GZ Encode: ". $answers_gze . " | Length: ".strlen($answers_gze)." bytes", "\n");
                */
                /**
                 * Push to quiz id array
                 */
                //$quiz_id_arr[] = implode("_", $curr[]);

            }
            //var_dump($json);
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Pack an array of numbers
         * 1) Base Convert number
         * 3) JSON encode / Serialize result
         */
        /**-------------------------------------------------------------------------*/
        public function packArr($num_arr){
            /**
             * Cycle Numbers and Base 16 encode
             */
            $acc = [];
            foreach($num_arr as $num){
                $base_hex   = base_convert($num, 10, 16);
                $acc[]      = $base_hex;
            }
            /**
             * Encyphering and Compressing
             */
            $original   = implode("_", $num_arr);
            $imploded   = implode("_", $acc);
            $serialized = serialize($acc);
            var_dump(json_encode([
                "original" => [
                    "str"    => $original,
                    "length" => strlen($original)
                ],
                "imploded" => [
                    "str"    => $imploded,
                    "length" => strlen($imploded)
                ],
                "serialized" => [
                    "str"    => $serialized,
                    "length" => strlen($serialized)
                ],
            ], JSON_PRETTY_PRINT));
            /*
            $res = [];
            foreach($acc as $num){
                $base_unhex = base_convert($num, 16, 10);
                $res[] = $base_unhex;
            }
            */
            //var_dump($res);
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Packs and Encodes Numbers
         */
        /**-------------------------------------------------------------------------*/
        public function packNumber($num){
            /**
             * Processess
             */
            $base_convert_hex = base_convert($num, 10, 16);
            $pack_num = pack('n', $num);
            /**
             * @var array $results
             */
            $results = [
                "num" => [
                    "value" => $num,
                    "size"  => strlen($num) . " bytes",
                    "hex"   => base_convert($num, 10, 16)
                ],
                "pack_num" => [
                    "value" => $pack_num,
                    "size"  => strlen($pack_num) . " bytes",
                    "hex"   => base_convert($pack_num, 10, 16)
                ],
                "base_hex" => [
                    "value" => $base_convert_hex,
                    "size"  => strlen($base_convert_hex) . " bytes"
                ],
            ];
            /**
             * Debugging
             */
            $json = json_encode($results, JSON_PRETTY_PRINT);
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
        protected function generateSeed($cat_id, $diff_id, $quiz_id_arr){

        }
    }