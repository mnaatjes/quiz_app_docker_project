<?php
    namespace App\Utils;
    /**
     * Utility Static Class
     */
    class Utility {

        /**
         * Generates a time-stamp in sql format
         * @param void
         * @return string
         */
        public static function createTS(){
            $now = new \DateTime();
            return $now->format('Y-m-d H:i:s');
        }

        /**
         * get Current ts
         */
        public static function getCurrentTS(){
            $now = new \DateTime();
            return $now->format('Y-m-d H:i:s');
        }

        /**
         * Print Json
         * @param mixed $data
         */
        public static function printJSON($data){
            printf('<pre>%s</pre>', json_encode($data, JSON_PRETTY_PRINT));
        }

        /**
         * Debug Session
         */
        public static function debugSession(){
            $isActiveSession = session_status() !== PHP_SESSION_ACTIVE ? false : true;
            $issetSession    = isset($_SESSION);
            $isEmptySession = $issetSession ? empty($_SESSION) : true;
            // Assign Default
            $data = [
                "session_status" => session_status(),
                "active" => $isActiveSession,
                "isset" => $issetSession,
                "empty" => $isEmptySession,
                "parameters" => $issetSession ? $_SESSION : NULL
            ];

            printf('
                <pre 
                    style="
                        font-size:12px; 
                        font-family:Arial;
                        z-index: 200;
                        margin: 12px;
                        padding 6px;
                    "
                >%s</pre>
            ', json_encode($data, JSON_PRETTY_PRINT));
        }
    }
?>