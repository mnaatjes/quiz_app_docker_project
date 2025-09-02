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
         * 
         */
    }
?>