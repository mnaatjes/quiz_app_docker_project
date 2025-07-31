<?php
    
    /**
     * Calculates the factorial of a non-negative integer.
     * This function is suitable for smaller numbers due to potential integer overflow.
     *
     * 
     * @param int $n The number for which to calculate the factorial.
     * @return int The factorial of $n.
     * @throws InvalidArgumentException If $n is negative.
     */
    function math_fact(int $n){
        if ($n < 0) {
            throw new InvalidArgumentException("Factorial is not defined for negative numbers.");
        }
        if ($n === 0 || $n === 1) {
            return 1;
        }

        $result = 1;
        for ($i = 2; $i <= $n; $i++) {
            $result *= $i;
        }
        return $result;
    }