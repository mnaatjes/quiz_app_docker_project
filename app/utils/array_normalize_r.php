<?php

    /**
     * Recursively Normalize an Array
     * 
     * @param array $arr
     * @param string $order Default ASC | else DESC
     * 
     * @return array Normalized array
     */
    function array_normalize_r(array $arr, $order="ASC"){
        /**
         * @var array $normal Normalized Array; Copy of $arr
         */
        $normal = $arr;
        /**
         * Normalize Recursively
         */
        foreach($normal as $key => &$value){
            if(is_array($value) && !empty($value)){
                // Recursive
                $value = array_normalize_r($value, $order);
            }
        }

        /**
         * Unset reference to value and array
         */
        unset($value);
        
        /**
         * Sort normal array in order
         */
        if($order === "DESC"){
            arsort($normal);
        } else if($order === "ASC"){
            asort($normal);
        }

        /**
         * Return array
         */
        return $normal;
    }
