<?php
    namespace App\Controllers;
    use App\Controllers\AppController;
    use mnaatjes\mvcFramework\DataAccess\BaseRepository;
    use mnaatjes\mvcFramework\HttpCore\HttpRequest;
    use mnaatjes\mvcFramework\HttpCore\HttpResponse;
    /**-------------------------------------------------------------------------*/
    /**
     * Quiz Controller inhereting AppController
     * 
     * @since 1.0.0:
     * - Removed constructor
     * - Reset index(), store(), show()
     * 
     * @since 1.1.0:
     * - Cleared index() method
     * - Moved index() method login to DashboardController@index()
     * 
     * @version 1.1.1
     */
    /**-------------------------------------------------------------------------*/
    class QuizController extends AppController {

        /**-------------------------------------------------------------------------*/
        /**
         * 
         */
        /**-------------------------------------------------------------------------*/
        public function index(HttpRequest $req, HttpResponse $res): void{}

        /**-------------------------------------------------------------------------*/
        /**
         * Assemble quiz into data object and render 
         */
        /**-------------------------------------------------------------------------*/
        public function show(HttpRequest $req, HttpResponse $res, $params): void{

            /**
             * Find and validate quiz_id param
             */
            if(!isset($params["quiz_id"])){
                // TODO: Redirect
                // Failed to find quiz id
                var_dump("Count not find quiz: " . $params["quiz_id"] . " to play!");
            }

            /**
             * Use QuizService to assemble quiz data object
             */
            $quizDataObject = $this->QuizService->getQuizObject($params["quiz_id"]);

            /**
             * Render page
             */
            $res->render("play_quiz", $quizDataObject);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Create new quiz record, userquiz record
         */
        /**-------------------------------------------------------------------------*/
        public function create(HttpRequest $req, HttpResponse $res, $params): void{
            /**
             * Retrieve user_id from session
             */
            $user_id = $this->UserService->getUserFromSession();
            if(is_null($user_id)){
                // Set error message and redirect on failure
                $this->ErrorService->setSession("Unknown User");
                $res->redirect("/index.php/login");
            }

            // Define Properties
            $category_id    = $req->getPostParam("category_id");
            $difficulty_id  = $req->getPostParam("difficulty_id");
            $title          = $req->getPostParam("title");
            $length         = 10; // TODO: Alter / Assign as default to now

            // Pull Questions
            $questions = $this->QuizService->generateQuestions(
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
            $quiz = $this->QuizService->storeQuizRecord(
                $questions,
                $title,
                $category_id,
                $difficulty_id
            );

            // Validate Quiz
            if(is_null($quiz)){
                var_dump("Error: Unable to save quiz!");
            }

            // Create Record in UserQuizzes to DB Table 
            $userQuiz = $this->QuizService->storeUserQuizRecord($quiz->getId(), $_SESSION["user_id"], $length);
            
            // Validate
            if(!is_object($userQuiz)){
                var_dump("Error: Unable to store UserQuiz!");
            }

            /**
             * Redirect to play quiz
             * @see /quizzes/{quiz_id}
             */
            $res->redirect("/index.php/quizzes/" . $quiz->getId());
        }        
        /**-------------------------------------------------------------------------*/
        /**
         * Update completed quiz after play or abandonment
         * @see /quizzes/{quiz_id}/submit
         */
        /**-------------------------------------------------------------------------*/
        public function submit($req, $res, $params): void{
            /**
             * Collect Form Data
             */
            var_dump($req->getPostParams());

            /**
             * Parse form data into associated models
             */


            /**
             * Update Records:
             * - users
             * - quizzes
             * - user_quizzes
             * - questions
             * - answers
             */

            /**
             * Redirect to dashboard
             */
            //$res->redirect("/index.php/dashboard");
        }
    }

?>