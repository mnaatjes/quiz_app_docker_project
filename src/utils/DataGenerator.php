<?php

    namespace mnaatjes\DataAccess\utils;

use Exception;

    /**
     * Data Generator Static Class for aiding in development and integration of Database and ORM Classes
     * 
     * @version 1.1.4
     * @since 1.0.0:
     * - Created
     * - Added randomString, hashString, and getUser methods
     * 
     * @since 1.1.4
     * - Added getRandomUser, getRandomEmail, getRandomName, getRandomDateTime
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
        /**
         * Get Random Name
         */
        /**-------------------------------------------------------------------------*/
        public static function getRandomName(){
            // Get Path
            $path = self::getResourcePath() . "/resources/data/users.json";

            // Read file
            $data = self::readJSON($path);
            
            // Return random name
            return [
                "firstName" => $data->firstNames[array_rand($data->firstNames)],
                "lastName" => $data->lastNames[array_rand($data->lastNames)]
            ];

        }
        /**-------------------------------------------------------------------------*/
        /**
         * Utility Method: Get Random Email Host
         * 
         * @param void
         * @return string Email host from JSON list; e.g. gmail.com
         */
        /**-------------------------------------------------------------------------*/
        public static function getRandomEmailHost(){
            // Get Path
            $path = self::getResourcePath() . "/resources/data/users.json";

            // Read file
            $data = self::readJSON($path);

            // Return value
            return $data->emailHosts[array_rand($data->emailHosts)];
        }


        /**-------------------------------------------------------------------------*/
        /**
         * Get Random User
         * 
         * @param void
         */
        /**-------------------------------------------------------------------------*/
        public static function getRandomUser(){
            // Get Path
            $path = self::getResourcePath() . "/resources/data/users.json";

            // Read file
            $data = self::readJSON($path);

            // Build User
            $fname = $data->firstNames[array_rand($data->firstNames)];
            $lname = $data->lastNames[array_rand($data->lastNames)];
            $prefix = $fname[0] . "." . $lname;

            return [
                "username"  => $prefix,
                "email"     => $prefix . "@" . $data->emailHosts[array_rand($data->emailHosts)],
                "password"  => self::hashString(self::randomString(10))
            ];
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Helper Method: Returns Resource Path
         */
        /**-------------------------------------------------------------------------*/
        private static function getResourcePath(){
            $dir = dirname(__DIR__, 2);

            try {
                 if(is_dir($dir) === true){
                    return $dir;
                 }
            } catch(Exception $e){
                return false;
            }
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Helper Method: Read JSON File
         * 
         * @return array Array of JSON data
         */
        /**-------------------------------------------------------------------------*/
        private static function readJSON(string $path){
            // Validate extension
            if(strpos($path, ".json") === 0){
                throw new Exception("Resource MUST be a JSON file!");
            }

            // Validate Path
            if(!file_exists($path)){
                throw new Exception("Unable to find file in " . __METHOD__);
            }

            // Get Contents and Decode
            $contents   = file_get_contents($path);
            $data       = json_decode($contents);

            // Check array conversion successful
            if($data === NULL){
                throw new Exception("Error: Invalid JSON format");
            } elseif(json_last_error() !== JSON_ERROR_NONE){
                throw new Exception("Unable to properly decode JSON file!");
            }

            // return data array
            return $data;
        }

        /**-------------------------------------------------------------------------*/
        /**
         * Utlility Method: Creates random DATETIME timestamp
         * 
         * @param string $start_date "Y-m-d H:i:s"
         * @return string $end_date date() Today
         */
        /**-------------------------------------------------------------------------*/
        public static function getRandomDateTime($start_date="2000-01-01 00:00:00", $end_date=""){
            // Set default date
            if(empty($end_date)){
                $end_date = date("Y-m-d H:i:s");
            }
            // Get date times
            $start_ts   = strtotime($start_date);
            $end_ts     = strtotime($end_date);

            // If start after end --> reverse
            if($start_ts > $end_ts){
                list($start_ts, $end_ts) = [$end_ts, $start_ts];
            }

            // Generate random timestamp
            $random_ts = mt_rand($start_ts, $end_ts);

            // Convert to Y-m-d H:i:s and return
            return date("Y-m-d H:i:s", $random_ts);
        }

        /**-------------------------------------------------------------------------*/
    }

?>