<?php

    namespace App\Services;

use App\Models\CategoryModel;
use App\Models\DifficultyModel;
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
    }

?>