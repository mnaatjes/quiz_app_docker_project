<?php
    /**
     * Declare Namespace
     */
    namespace mnaatjes\DataAccess\Utils;
    
    /**
     * DotEnv (.env) Class for loading Environment Variables
     * 
     * @version 1.0.0
     * @since 1.0.0
     * - Created
     * - Added to data-access/utils
     * 
     * @static 
     */
    class DotEnv {

        /**
         * Filepath to .env file
         * @var string $path
         */

        protected string $path;

        /**
         * Load .env file
         * 
         * @static
         * @param string $path
         * @return 
         * @throws \RuntimeException if .env file not readable
         */
        public static function load(string $path){
            // Validate path
            if(!is_readable($path)){
                throw new \RuntimeException("Unable to read file: " . $path);
            }

            /**
             * Open file and load lines into array
             * @var array $lines
             */
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            /**
             * Loop and assign as Environment Variables:
             * - Skip comments
             * - Grab $key => $value pairs
             * - Sanitize entires & strip quotes
             * - Set $_ENV Variables
             */
            foreach($lines as $line){
                // Skip comments
                if(strpos(trim($line), "#") === 0){
                    continue;
                }

                // Explode into array of key, value pairs to validate
                $parts = explode("=", $line, 2);

                // Check for equals operator
                if(count($parts) !== 2){
                    continue;
                }

                // Grab key, value pairs
                list($key, $value) = $parts;

                // Sanitize
                $key    = trim($key);
                $value  = trim($value);

                // Strip quotes from values if exist
                if(in_array($value[0] ?? '', ['"', "'"])){ // <- if NULL, set empty to avoid error
                    $value = substr($value, 1, -1);
                }

                // Set ENV Variables
                if(!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)){
                    // Check if the value is a JSON string
                    $decoded = json_decode($value, true);
                    if(json_last_error() === JSON_ERROR_NONE){
                        // Store --> cannot use setENV
                        $_ENV[$key]     = $decoded;
                        $_SERVER[$key]  = $decoded;

                    } else {
                        // Put ENV variables (string values)
                        putenv(sprintf('%s=%s', $key, $value));
                        $_ENV[$key]     = $value;
                        $_SERVER[$key]  = $value;
                    }
                }
            }
        }
    }
?>