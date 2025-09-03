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
    }
?>