<?php
/**
 * @file db_config.php
 * 
 * @version 1.0 
 * @since 7/27/25
 * 
 * Prevent http connection to this document
 */
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Direct access to this configuration file is forbidden.');
}

/**
 * @var array DB Configuration Credentials
 */
return [
    'host'      => 'quiz-app-db',
    'db_name'   => 'quiz_db',
    'user'      => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'options'   => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];