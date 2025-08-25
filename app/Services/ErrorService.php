<?php

    namespace App\Services;
    use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * Error Service
     * 
     * @since 1.0.0:
     * - Created
     * 
     * @version 1.0.0
     */
    class ErrorService {
        private SessionManager $session;

        /**
         * 
         */
        public function __construct($session_manager_instance){
            $this->session = $session_manager_instance;
        }

        /**
         * 
         */
        public function setSession(string $message){
            $this->session->set("error", $message);
        }

        /**
         * 
         */
        public function hasSession(){
            return $this->session->has("error");
        }

        /**
         * 
         */
        public function getSession(){
            return $this->session->get("error", NULL);
        }

        /**
         * 
         */
        public function removeSession(){
            $this->session->remove("error");
        }
    }

?>