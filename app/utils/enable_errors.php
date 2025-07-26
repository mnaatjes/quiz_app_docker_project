<?php
    /**
     * Enable Errors
     */
    function enable_errors($enable=true){
        /**
         * Enabled
         */
        if((is_bool($enable) && $enable === true) || (is_dir(dirname($enable)))){
            // E_ALL ON
            error_reporting(E_ALL);
            // DISPLAY ON
            ini_set('display_errors', 1);

            // Log
            if(file_exists($enable)){
                ini_set('log_errors', 1);
                ini_set('error_log', $enable);
            } else {
                ini_set('log_errors', 0);
            }
        } elseif(!is_dir($enable)) {
            // Throw exception 
            throw new Exception("Filepath does not exist!");
        } else {
            // Turn Errors Off
            ini_set('display_errors', 0);
            ini_set('log_errors', 0);
        }
    }
?>