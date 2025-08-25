<?php

    namespace App\Controllers;
    use App\Controllers\AppController;

    /**
     * Controller for Dashboard of user quiz data
     * - Controls the Dashboard view loading and rendering
     * 
     * @since 1.0.0:
     * - Created
     * 
     * 
     * @version 1.0.0
     */
    class DashboardController extends AppController {

        /**-------------------------------------------------------------------------*/
        /**
         * Dashboard Index: Display all user quizzes based on user_id
         * 
         * @see GET /dashboard
         */
        /**-------------------------------------------------------------------------*/
        public function index($req, $res): void{
            /**
             * Load user object using UserService
             * @var UserModel $user
             */
            $user = $this->UserService->load();

            /**
             * Load UserQuizzes
             */
            $userQuizzes = $this->QuizService->loadUserQuizzes($user->getId());
            
            /**
             * Data object to pass to dashboard
             * @var array $data
             */
            $data = [
                "user" => $user->toArray(),
                "user_quizzes" => $userQuizzes
            ];

            // Render
            $res->render("dashboard", $data);
        }

    }
?>