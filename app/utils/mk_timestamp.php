<?php

    /**
     * Make Timestamp
     * 
     * @param void
     * @return string MySQL compatible timestamp
     */
    function mk_timestamp(){
        /**
         * Set TimeZone
         * TODO: Change in  php.ini
         */
        date_default_timezone_set('America/Detroit');
        /**
         * Generate Timestamp
         */
        $ts = time();
        return date("Y-m-d H:i:s");
    }