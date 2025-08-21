<?php

    namespace App\Services;
    use App\Models\QuizModel;
    use App\Models\UserModel;
    use App\Models\UserQuizModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

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
        private SessionManager $session;

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
            SessionManager $session_manager,
        ){
            $this->quizRepo         = $quiz_repository;
            $this->userQuizzesRepo  = $user_quiz_repo;
            $this->questionRepo     = $question_repo;
            $this->answerRepo       = $answers_repo;
            $this->session          = $session_manager;
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
        public function createDataObject(array $questions, QuizModel $quiz, int $length){

            // Form Question Object
            $data_object["questions"] = array_reduce($questions, function($acc, $question){
                //Get id
                $question_id = $question->getId();

                // Query Answers Table
                $answers = array_reduce($this->answerRepo->findBy(["question_id" => $question_id]), function($carry, $answer){
                    $carry[] = $answer->toArray();
                    // Push
                    return $carry;

                }, []);

                $question_arr = [
                    "id"    => $question->getId(),
                    "text"  => $question->getQuestionText(),
                    "category"      => $question->getCategoryId(),
                    "times_asked"   => $question->getTimesAsked(),
                    "correct_attempts_count"    => $question->getCorrectAttemptsCount(),
                    "incorrect_attempts_count"  => $question->getIncorrectAttemptsCount(),
                    "difficulty" => $question->getDifficultyId(),
                    "skip_count" => $question->getSkipCount(),
                    "total_time_spent_seconds" => $question->getTotalTimeSpentSeconds(),
                    "last_played_at" => $question->getLastPlayedAt()
                ];
                $question_arr["answers"] = $answers;

                $acc[] = $question_arr;

                // Return acc
                return $acc;
            }, []);

            // Append other data
            $data_object["quiz"] = $quiz->toArray();
            $data_object["quiz"]["length"] = $length;

            // TODO: Validation and shorted reponse body
            // Return data object
            return $data_object;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Store quiz data in session
         */
        /**-------------------------------------------------------------------------*/
        public function storeQuizSession(array $quiz_data){
            
            // TODO: Check if session with user exists

            // Assign to SESSION
            $_SESSION["quiz_data"] = $quiz_data;

            // Return boolean
            return true;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Load User Quiz Data
         */
        /**-------------------------------------------------------------------------*/
        public function loadUserQuizzes(int $user_id){
            /**
             * Load User Quizzes
             * - Find records by user_id
             * - Pull records by user_id from quizzes
             * - Render Data Object
             */
            // Query UserQuizzes
            $userQuizzes = $this->userQuizzesRepo->findByForeignId("user_id", $user_id);

            // Return if empty
            if(empty($userQuizzes)){
                return [];
            }

            // Find by quiz_id
            $records = [];
            foreach($userQuizzes as $userQuiz){

                // Query Quiz Table
                $quiz = $this->quizRepo->findById($userQuiz->getQuizId());

                // Query Difficulty Table
                

                // Query Category Table


                // Assemble Data Object
                $records[] = [
                    "id"            => $quiz->getId(),
                    "title"         => $quiz->getTitle(),
                    "is_completed"  => $userQuiz->getIsCompleted() === 1 ? "Yes" : "No",
                    "completed_at"  => $userQuiz->getCompletedAt(),
                    "length"        => $userQuiz->getTotalQuestions(),
                    "score"         => $userQuiz->getScore(),
                    "last_played"   => $userQuiz->getLastActivityAt(),
                    "difficulty"    => $quiz->getDifficultyId(),
                    "category"      => $quiz->getCategoryId()
                ];
            }
            return $records;

        }
    }

?>