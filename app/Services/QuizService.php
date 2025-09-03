<?php

    namespace App\Services;

    use App\Models\AnswerModel;
    use App\Models\CategoryModel;
    use App\Models\DifficultyModel;
    use App\Models\QuestionModel;
    use App\Models\QuizModel;
    use App\Models\UserQuizModel;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;
    use mnaatjes\mvcFramework\SessionsCore\SessionManager;
    use App\Utils\Utility;
use PDO;

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
        private BaseRepository $categoryRepo;
        private BaseRepository $difficultyRepo;
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
            BaseRepository $category_repo,
            BaseRepository $difficulty_repo,
            SessionManager $session_manager,
        ){
            $this->quizRepo         = $quiz_repository;
            $this->userQuizzesRepo  = $user_quiz_repo;
            $this->questionRepo     = $question_repo;
            $this->answerRepo       = $answers_repo;
            $this->categoryRepo     = $category_repo;
            $this->difficultyRepo   = $difficulty_repo;
            $this->session          = $session_manager;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Generate Questions for a new quiz
         * 
         * @param int $category_id
         * @param int $difficulty_id
         * @param int $length
         */
        /**-------------------------------------------------------------------------*/
        public function generateQuestions($category_id, $difficulty_id, $length){
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
        private function parseQuestionIds(array $questions){
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
        public function storeQuizRecord(array $questions, string $title, int $category_id, int $difficulty_id){
            // Parse Question Ids
            $questionIds = $this->parseQuestionIds($questions);

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
         * 
         * TODO: change name of function to "storeNew..." or "insert..."
         */
        /**-------------------------------------------------------------------------*/
        public function storeUserQuizRecord(int $quiz_id, int $user_id, int $length){

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
                $difficulty = $this->difficultyRepo->findById($quiz->getDifficultyId());


                // Query Category Table
                $category = $this->categoryRepo->findById($quiz->getCategoryId());

                // Assemble Data Object
                $records[] = [
                    "id"            => $quiz->getId(),
                    "title"         => $quiz->getTitle(),
                    "is_completed"  => $userQuiz->getIsCompleted() === 1 ? "Yes" : "No",
                    "completed_at"  => $userQuiz->getCompletedAt(),
                    "length"        => $userQuiz->getTotalQuestions(),
                    "score"         => $userQuiz->getScore(),
                    "last_played"   => $userQuiz->getLastActivityAt(),
                    "difficulty"    => $difficulty->getName(),
                    "category"      => $category->getName()
                ];
            }
            return $records;

        }

        /**-------------------------------------------------------------------------*/
        /**
         * Find Category by Id
         */
        /**-------------------------------------------------------------------------*/
        private function findCategoryById(int $category_id): ?CategoryModel{
            return $this->categoryRepo->findById($category_id) ?? NULL;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Find Difficulty by Id
         */
        /**-------------------------------------------------------------------------*/
        private function findDifficultyById(int $difficulty_id): ?DifficultyModel{
            return $this->difficultyRepo->findById($difficulty_id) ?? NULL;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Get quiz data object from records by quiz id
         */
        /**-------------------------------------------------------------------------*/
        public function getQuizObject(int $quiz_id){
            /**
             * Data object to return
             * @var array $data
             */
            $data = [];

            // Pull quiz record from table
            $quiz = $this->quizRepo->findById($quiz_id);
            $data["quiz"] = [
                "id"    => $quiz->getId(),
                "title" => $quiz->getTitle(),
                "difficulty" => $this->findDifficultyById(
                    $quiz->getDifficultyId()
                )->getName(),
                "category" => $this->findCategoryById(
                    $quiz->getCategoryId()
                )->getName()
            ];
            
            // Use ID Map to pull questions
            $questions = array_reduce(json_decode($quiz->getQuizIdMap()), function($acc, $id){
                // Find Question Record
                $model = $this->questionRepo->findById($id);
                if(!is_null($model)){
                    // Assign Object Values
                    $question = $model->toArray();

                    // Query Answer Data
                    $answers = $this->answerRepo->findByForeignId("question_id", $model->getId());
                    
                    // Assign as property of question
                    $question["answers"] = array_reduce($answers, function($acc, $obj){

                        $acc[] = $obj->toArray();

                        // Return accumulator
                        return $acc;
                    }, []);

                    // Map to return array
                    $acc[] = $question;   
                }
                return $acc;
            }, []); 

            // Assign properties
            $data["question_map"] = json_decode($quiz->getQuizIdMap());
            $data["questions"] = $questions;

            // Return Data Object
            return $data;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update submitted quiz
         * 
         * @param int $user_id
         * @param int $quiz_id
         * @param array $answers_array Array of question_id => answer_id pairs of id strings: "2342" => "342" from POST params
         * @param array $quiz_id_map Array of question id strings: "32423", "234324", "24243" from POST params
         * @return bool True on success
         */
        /**-------------------------------------------------------------------------*/
        public function updateSubmittedQuiz($user_id, $quiz_id, $answers_array, $quiz_id_map){
            /**
             * Update Questions and Answers
             */
            $userQuizData = $this->updateSubmittedQuestions($answers_array, $quiz_id_map);

            // Validate Update of Questions
            if(!is_array($userQuizData) || empty($userQuizData)){
                // Exit failure
                return false;
            }
            /**
             * Update UserQuiz
             */
            $userQuizUpdated = $this->updateSubmittedUserQuiz($user_id, $quiz_id, $userQuizData);

            // Return result
            return $userQuizUpdated;
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update Submitted UserQuiz
         */
        /**-------------------------------------------------------------------------*/
        public function updateSubmittedUserQuiz(int $user_id, int $quiz_id, array $data){
            // Get existing model
            $model = $this->loadUserQuiz($user_id, $quiz_id);

            // Validate
            if(is_null($model)){
                // Exit
                return false;
            }

            // Update with new data
            $model->setStartedAt($data["started_at"]);
            $model->setCompletedAt($data["completed_at"]);
            $model->setScore($data["score"]);
            $model->setCorrectAnswersCount($data["correct_answers_count"]);
            $model->setIncorrectAnswersCount($data["incorrect_answers_count"]);
            $model->setSkippedQuestionsCount($data["skipped_questions_count"]);
            $model->setTimeTakenSeconds($data["time_taken_seconds"]);
            $model->setIsCompleted($data["is_completed"]);
            $model->setLastActivityAt($data["last_activity_at"]);

            // Update UserQuiz Record and return result
            return $this->updateUserQuizRecord($model);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update Submitted Questions and Answers
         * 
         * @param array $answers_array Array of question_id => answer_id pairs of id strings: "2342" => "342" from POST params
         * @param array $quiz_id_map Array of question id strings: "32423", "234324", "24243" from POST params
         * @return array|bool False on failure; Assoc Array of data for User Quiz on success
         */
        /**-------------------------------------------------------------------------*/
        public function updateSubmittedQuestions($answers_array, $quiz_id_map){
            /**
             * User Quiz Data for updating user quiz record
             * @var array $data
             */
            $data = [
                "started_at" => Utility::getCurrentTS(), // TODO: Change value to real value
                "completed_at" => Utility::getCurrentTS(),
                "score" => 0,
                "correct_answers_count" => 0,
                "incorrect_answers_count" => 0,
                "skipped_questions_count" => 0,
                "time_taken_seconds" => 0,
                "is_completed" => 0,
                "last_activity_at" => Utility::getCurrentTS()
            ];

            /**
             * Loop and update Question and Answer Records
             */
            foreach($quiz_id_map as $question_id){
                /**
                 * Load question model from id
                 * @var QuestionModel $questionModel
                 */
                $questionModel = $this->loadQuestion((int)$question_id);

                // Collect Existing Question Properties
                $timesAsked = $questionModel->getTimesAsked() + 1;
                $skipCount  = $questionModel->getSkipCount();
                $correctCount   = $questionModel->getCorrectAttemptsCount();
                $incorrectCount = $questionModel->getIncorrectAttemptsCount();

                // Find Answer Models
                $answers = $this->loadAnswers((int)$question_id);

                // Check if question skipped
                $questionAnswered = array_key_exists($question_id, $answers_array);
                if(!$questionAnswered){
                    // Add to skip count
                    $skipCount++;

                    // Update userquiz data
                    $data["skipped_questions_count"]++;
                }

                // Loop answer models to determine other property values
                foreach($answers as $answerModel){
                    // Get relavant properties
                    $timesShown     = $answerModel->getTimesShown();
                    $selectionCount = $answerModel->getSelectionCount();

                    // Add to times shown
                    $timesShown++;

                    // Check if question was answered
                    if($questionAnswered){
                        // Question Answered
                        // Check correct | incorrect
                        if((int)$answers_array[$question_id] === $answerModel->getId()){
                            // Add to selection count
                            $selectionCount++;

                            // Check correct
                            if($answerModel->getIsCorrect() === 1){
                                // Correct
                                $correctCount++;

                                // Update userquiz data
                                $data["score"]++;
                                $data["correct_answers_count"]++;

                            } else {
                                // Incorrect
                                $incorrectCount++;

                                // Update userquiz data
                                $data["incorrect_answers_count"]++;
                            }
                        }
                    }

                    // Update Answers Properties
                    $answerModel->setTimesShown($timesShown);
                    $answerModel->setSelectionCount($selectionCount);
                    $answerModel->setUpdatedAt(Utility::getCurrentTS());

                    // Update Answer Record
                    $answerResult = $this->updateAnswerRecord($answerModel);
                    
                    // TODO: Break loop if failure to update Answer
                }

                // Assign Properties
                $questionModel->setTimesAsked($timesAsked);
                $questionModel->setSkipCount($skipCount);
                $questionModel->setCorrectAttemptsCount($correctCount);
                $questionModel->setIncorrectAttemptsCount($incorrectCount);
                $questionModel->setLastStatUpdateAt(Utility::getCurrentTS());
                $questionModel->setLastPlayedAt(Utility::getCurrentTS());

                // Update Question Record
                $questionResult = $this->updateQuestionRecord($questionModel);
                // TODO: Break loop if failure to update Question
            }

            // Check if quiz completed
            $data["is_completed"] = (count($quiz_id_map) === count($answers_array)) ? 1 : 0;

            // Loop completed: Return Success
            return $data;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Load Question Model
         * @param int $id
         * @return QuestionModel
         */
        /**-------------------------------------------------------------------------*/
        public function loadQuestion(int $id){
            $model = $this->questionRepo->findById($id);
            if(is_a($model, QuestionModel::class)){
                return $model;
            } else {
                return NULL;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Load Answers from question Ids
         */
        /**-------------------------------------------------------------------------*/
        public function loadAnswers(int $question_id){
            return $this->answerRepo->findByForeignId("question_id", $question_id);
        }
        
        /**-------------------------------------------------------------------------*/
        /**
         * Load Answer Model
         * @param int $id
         * @return QuestionModel
         */
        /**-------------------------------------------------------------------------*/
        public function loadAnswer(int $id){
            $model = $this->answerRepo->findById($id);
            if(is_a($model, AnswerModel::class)){
                return $model;
            } else {
                return NULL;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update Question Record
         * @param QuestionModel $model
         * @return bool True on success
         */
        /**-------------------------------------------------------------------------*/
        public function updateQuestionRecord(QuestionModel $model){
            $result = $this->questionRepo->save($model);
            if(is_a($result, QuestionModel::class)){
                return true;
            }
            return false;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update Answer Record
         * @param AnswerModel $model
         * @return bool True on success
         */
        /**-------------------------------------------------------------------------*/
        public function updateAnswerRecord(AnswerModel $model){
            $result = $this->answerRepo->save($model);
            if(is_a($result, AnswerModel::class)){
                return true;
            }
            return false;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Load UserQuiz
         * 
         * @param int $user_id
         * @param int $quiz_id
         * @return UserQuiz
         */
        /**-------------------------------------------------------------------------*/
        public function loadUserQuiz(int $user_id, $quiz_id){
            $results = $this->userQuizzesRepo->findBy([
                "user_id"   => $user_id,
                "quiz_id"   => $quiz_id
            ]);

            // Validate Results
            if(!empty($results) && is_array($results) && count($results) === 1){
                $model = $results[0];

                // Validate model and return
                return is_a($model, UserQuizModel::class) ? $model : NULL;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Load quiz
         */
        /**-------------------------------------------------------------------------*/
        public function loadQuiz(int $quiz_id){
            $model = $this->quizRepo->findById($quiz_id);
            // Validate and return
            return is_a($model, QuizModel::class) ? $model : NULL;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Update UserQuiz Record
         */
        /**-------------------------------------------------------------------------*/
        public function updateUserQuizRecord(UserQuizModel $model){
            $result = $this->userQuizzesRepo->save($model);
            if(is_a($result, UserQuizModel::class)){
                return true;
            }
            return false;
        }
    }

?>