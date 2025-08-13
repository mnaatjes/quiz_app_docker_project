<?php

    namespace mnaatjes\DataAccess\utils;

use Exception;

    /**
     * Data Generator Static Class for aiding in development and integration of Database and ORM Classes
     * 
     * @version 1.0.0
     * @since 1.0.0:
     * - Created
     * 
     * @static
     */
    class DataGenerator {

        /**-------------------------------------------------------------------------*/
        /**
         * Generate Random String
         * 
         * @static
         * @param int $length Default = 10
         * @return string
         */
        /**-------------------------------------------------------------------------*/
        public static function randomString(int $length=10): string{
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Generate Hash String
         * 
         * @param string $data
         * @param string $algorithm Default = sha256
         * 
         * @return string Hashkey
         */
        /**-------------------------------------------------------------------------*/
        public static function hashString(string $data, string $algorithm = 'sha256'): string{
            // The hash_init() function initializes a hashing context.
            $context = hash_init($algorithm);

            // The hash_update() function adds data to the hashing context.
            hash_update($context, $data);

            // The hash_final() function returns the calculated hash as a string.
            return hash_final($context);
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Generate Dummy User: Designed to work in tandem with Users table from Test DB
         * 
         * @param void
         * @return array Array of dummy user data
         */
        /**-------------------------------------------------------------------------*/
        public static function getUser(): array{
            // Generate and return
            return [
                "username"  => self::randomString(12),
                "email"     => self::randomString(8) . "@gmail.com",
                "password"  => self::hashString(self::randomString(20))
            ];
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Create Test DB and Tables: user, 
         */
        /**-------------------------------------------------------------------------*/

        /**-------------------------------------------------------------------------*/
        /**
         * Helper Method: Count lines of a file
         * 
         * @param string $path Filename
         * 
         * TODO: Finish with resource integration for later use for data like real names, emails, etc
         */
        /**-------------------------------------------------------------------------*/
        private static function countLines(string $file){
            /**
             * @var string $path Path to resources/data 
             */
            $path   = dirname(__DIR__, 2) . "/resources/data/" . $file;

            // Validate
            if(!file_exists($path)){
                throw new Exception("Unable to identify resource in " . __METHOD__);
            }

            // Set Count
            $count  = 0;

            // Open file and count
            $handle = fopen($path, "r");

            // Validate handle
            if($handle === false){
                return 0;
            }
            
            while(!feof($handle)){
                fgets($handle);
                $count++;
            }
            // Close and return
            fclose($handle);
            return $count;
        }
        /**-------------------------------------------------------------------------*/
    }

?>