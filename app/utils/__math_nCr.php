<?php
    require_once('/var/www/app/utils/math_factorial.php');
    /**
     * Calculates "n choose r" (nCr) using either GMP or BCMath extensions for arbitrary precision.
     *
     * @param int|string $n The total number of items.
     * @param int|string $r The number of items to choose.
     * @return string The calculated nCr value as a string.
     * @throws InvalidArgumentException If n or r are negative, or if r is greater than n.
     * @throws RuntimeException If neither GMP nor BCMath extensions are enabled.
     */
    function math_nCr($n, $r): string
    {
        // Validate inputs
        if (bccomp($n, '0') < 0 || bccomp($r, '0') < 0) {
            throw new InvalidArgumentException("n and r must be non-negative.");
        }
        if (bccomp($r, $n) > 0) {
            throw new InvalidArgumentException("r cannot be greater than n.");
        }
        if (bccomp($r, '0') === 0 || bccomp($r, $n) === 0) {
            return '1';
        }
        if (bccomp($r, '1') === 0 || bccomp($r, bcsub($n, '1')) === 0) {
            return (string)$n;
        }

        $useGmp = extension_loaded('gmp');
        $useBcmath = extension_loaded('bcmath');

        if (!$useGmp && !$useBcmath) {
            throw new RuntimeException("Either GMP or BCMath extension must be enabled for arbitrary precision arithmetic.");
        }

        // Optimization: C(n, r) = C(n, n-r)
        if (bccomp($r, bcdiv($n, '2')) > 0) {
            $r = bcsub($n, $r);
        }

        if ($useGmp) {
            // Use GMP for best performance if available
            $numerator = gmp_init('1');
            $denominator = gmp_init('1');

            for ($i = 0; bccomp($i, $r) < 0; $i = bcadd($i, '1')) {
                $numerator = gmp_mul($numerator, gmp_sub($n, $i));
                $denominator = gmp_mul($denominator, gmp_add($i, '1'));
            }
            return gmp_strval(gmp_div($numerator, $denominator));
        } else {
            // Fallback to BCMath if GMP is not available
            $numerator = '1';
            $denominator = '1';

            for ($i = '0'; bccomp($i, $r) < 0; $i = bcadd($i, '1')) {
                $numerator = bcmul($numerator, bcsub($n, $i));
                $denominator = bcmul($denominator, bcadd($i, '1'));
            }
            return bcdiv($numerator, $denominator);
        }
    }