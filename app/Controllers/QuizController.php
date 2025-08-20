<?php
    namespace App\Controllers;
    use App\Controllers\AppController;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
    /**-------------------------------------------------------------------------*/
    /**
     * Quiz Controller inhereting AppController
     */
    /**-------------------------------------------------------------------------*/
    class QuizController extends AppController {

        /**
         * @var Object $service
         */
        protected object $service;
        protected object $userService;

        /**-------------------------------------------------------------------------*/
        /**
         * Construct
         */
        /**-------------------------------------------------------------------------*/
        public function __construct(BaseRepository $repository, $user_service, $quiz_service){
            // Assign Main Service
            $this->service = $quiz_service;

            // Assign User Service
            $this->userService = $user_service;

            // Invoke BaseRepository Construct
            parent::__construct($repository);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function index(HttpRequest $req, HttpResponse $res): void{
            $data = $this->repository->all();
            
            $res->sendJson(array_map(function($obj){return $obj->toJSON();}, $data));

        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, $params): void{
            
            
        }

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function store(HttpRequest $req, HttpResponse $res, $params): void{

            /**
             * Check User Service for authentic user
             */
            $isValidUser = $this->userService->isValidSession();

            if($isValidUser === false){
                var_dump("Error: Unable to authenticate user!");
            }
            
            /**
             * Quiz Store Flow
             * 1) Capture Params:
             *      - user_id
             *      - difficulty_id
             *      - category_id
             *      - title
             *      - assign length (default = 10)
             * 
             * 2) Pull Questions by cat_id, diff_id:
             *      - Generate quiz_id_map JSON string
             * 
             * 3) Create record in quizzes table:
             *      - insert: quiz_id_map, description, cat_id, diff_id
             *      - grab lastInsertId as quiz_id
             * 
             * 4) Create Record in UserQuizzes table:
             *      - use user_id, quiz_id, length
             *      - grab lastInsertId as user_quiz_id
             * 
             * 5) Return relavant data via API:
             *      - Query answers for associated question_ids_arr
             *      - format JSON response
             *      - Send Data and redirect
             */
            // Define Properties
            $category_id    = $req->getPostParam("category_id");
            $difficulty_id  = $req->getPostParam("difficulty_id");
            $title          = $req->getPostParam("title");
            $length         = 10;

            // Pull Questions
            $questions = $this->service->pullQuestions(
                $category_id,
                $difficulty_id,
                $length
            );

            // Validate questions
            if(empty($questions)){
                // Send Error
                var_dump("Error: Could not create Quiz!");
            }

            // Store Quiz
            $quiz = $this->service->storeQuiz(
                $questions,
                $title,
                $category_id,
                $difficulty_id
            );

            // Validate Quiz
            if(is_null($quiz)){
                var_dump("Error: Unable to save quiz!");
            }

            // DEBUGGING TODO: Change to session variable
            $user_id = 12;

            // Create Record in UserQuizzes to DB Table 
            $userQuiz = $this->service->storeUserQuiz($quiz->getId(), $_SESSION["user_id"], $length);
            
            // Validate
            if(!is_object($userQuiz)){
                var_dump("Error: Unable to store UserQuiz!");
            }

            // Form Data Response Object
            $dataObject = $this->service->createDataObject(
                $questions,
                $quiz,
                $length
            );
            
            // Store Quiz to Session
            $stored = $this->service->storeQuizSession($dataObject);

            if($stored === true){
                // Redirect
                //$res->redirect("/index.php/quiz/play");
                $res->redirect("/index.php/quiz/test");

            } else {
                // Error
                var_dump("Error: Unable to store quiz!");
            }
        }
    }

?>