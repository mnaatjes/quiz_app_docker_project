<?php

    /**
     * Database Connect
     * 
     * @param array $config
     * 
     * @return PDO|NULL
     * 
     */
    function db_connect($config){

        /**
         * @var string $dsn Data Source Name
         */
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['db_name'], $config['charset']);

        /**
         * Attempt Connection
         */
        try {
            /**
             * @var object $pdo
             */
            $pdo = new PDO($dsn, $config['user'], $config['password'], $config['options']);
            
            /**
             * Check empty
             */
            if(!is_null($pdo)){
                return $pdo;
            }

        } catch (PDOException $err){
            /**
             * Return error string
             */
            return $err;
        }
    }