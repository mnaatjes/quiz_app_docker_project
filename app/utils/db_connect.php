<?php

    /**
     * Database Connect
     * 
     * @param array $config
     * @param string $query
     * 
     */
    function db_connect($config, $query){

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
             * @var object $stmt Prepares Query
             */
            $stmt = $pdo->query($query);

            /**
             * @var array $records Results from query
             */
            $records = $stmt->fetchAll();

            /**
             * Close PDO connection
             * Return Results
             */
            $pdo = null;
            return $records;

        } catch (PDOException $err){
            /**
             * Return error string
             */
            return $err;
        }

        return $dsn;
    }