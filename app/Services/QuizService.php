<?php

    namespace App\Services;
    use App\Models\QuizModel;
use App\Models\UserQuizModel;
use mnaatjes\mvcFramework\DataAccess\BaseRepository;
use mnaatjes\mvcFramework\MVCCore\BaseModel;

    /**-------------------------------------------------------------------------*/
    /**
     * UserService Class associated with UserController, UserRepo, UserModel
     * 
     * @since 1.0.0:
     * - Created
     * - Added authernticate(), login(), hashPassword() methods
     * 
     * @version 1.0.0
     * 
     */
    /**-------------------------------------------------------------------------*/
    class QuizService {

        private BaseRepository $quizRepo;
        private BaseRepository $userQuizzesRepo;
        private BaseRepository $questionRepo;
        private BaseRepository $answerRepo;

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(
            BaseRepository $quiz_repository,
            BaseRepository $user_quiz_repo,
            BaseRepository $question_repo,
            BaseRepository $answers_repo,
        ){
            $this->quizRepo         = $quiz_repository;
            $this->userQuizzesRepo  = $user_quiz_repo;
            $this->questionRepo     = $question_repo;
            $this->answerRepo       = $answers_repo;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Pull Questions
         * 
         * @param int $category_id
         * @param int $difficulty_id
         * @param int $length
         */
        /**-------------------------------------------------------------------------*/
        public function pullQuestions($category_id, $difficulty_id, $length){
            // Query DB table with props
            $data = $this->questionRepo->findByLimit([
                "category_id"   => $category_id,
                "difficulty_id" => $difficulty_id,
            ], $length);
            
            // Validate
            if(is_array($data) && count($data) === $length){
                // Return Questions
                return $data;

            } else {
                // Return empty array if unable to populate
                return [];
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function createTS(){
            $now = new \DateTime();
            return $now->format('Y-m-d H:i:s');
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        private function getQuestionIds(array $questions){
            return array_reduce($questions, function($acc, $obj){
                if(is_a($obj, BaseModel::class)){
                    $acc[] = $obj->getId();
                }
                return $acc;
            }, []);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         * @param array $questions
         * @param string $title
         * @param int $category_id
         * @param int $difficulty_id
         * @return bool
         */
        /**-------------------------------------------------------------------------*/
        public function storeQuiz(array $questions, string $title, int $category_id, int $difficulty_id){
            // Parse Question Ids
            $questionIds = $this->getQuestionIds($questions);

            // Validate
            if(empty($questionIds)){
                var_dump("Error: Could not parse ids");
            }

            // Parse questionIds to json
            $quiz_id_map = json_encode($questionIds);
            
            // Perform Save
            $quiz = $this->quizRepo->save(new QuizModel([
                "quiz_id_map"   => $quiz_id_map,
                "title"         => $title,
                "category_id"   => $category_id,
                "difficulty_id" => $difficulty_id,
                "created_at"    => $this->createTS(),
                "updated_at"    => $this->createTS()
            ]));

            if(is_a($quiz, QuizModel::class)){
                return $quiz;
            } else {
                return NULL;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Store UserQuiz in DB user_quizzes
         * 
         * @param array $questions
         * @param string $title
         * @param int $category_id
         * @param int $difficulty_id
         * @return bool
         */
        /**-------------------------------------------------------------------------*/
        public function storeUserQuiz(int $quiz_id, int $user_id, int $length){

            // Save new record
            $userQuiz = $this->userQuizzesRepo->save(new UserQuizModel([
                'user_id' => $user_id,
                'quiz_id' => $quiz_id,
                'started_at' => $this->createTS(),
                'completed_at' => NULL,
                'score' => 0,
                'total_questions' => $length,
                'correct_answers_count' => 0,
                'incorrect_answers_count' => 0,
                'skipped_questions_count' => 0,
                'time_taken_seconds' => 0,
                'is_completed' => 0,
                'status' => 1,
                'last_activity_at' => $this->createTS(),
                'created_at' => $this->createTS(),
                'updated_at' => $this->createTS()
            ]));

            // TODO: Validate
            // Return model
            return $userQuiz;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function getDataObject(array $questions, string $title, int $category_id, int $difficulty_id, int $length){

            // Form Question Object
            $data_object["quiz"] = array_reduce($questions, function($acc, $question){
                //Get id
                $q_id = $question->getId();

                // Query Answers Table
                $answers = array_map(function($obj){
                    return $obj->toArray();
                }, $this->answerRepo->findBy(["question_id" => $q_id]));

                $acc[] = [
                    "question"  => $question->toArray(),
                    "answers"   => $answers
                ];

                return $acc;
            }, []);

            // Append other data
            $data_object["title"]       = $title;
            $data_object["category"]    = $category_id;
            $data_object["difficulty"]  = $difficulty_id;
            $data_object["length"]      = $length;

            // TODO: Validation and shorted reponse body
            // Return data object
            return $data_object;
        }
    }

?>