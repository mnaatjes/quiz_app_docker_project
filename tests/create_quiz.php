<?php

    /**
     * Require Utils and Framework
     */
    require_once('/var/www/app/utils/enable_errors.php');
    require_once('/var/www/app/utils/db_connect.php');
    require_once('/var/www/app/simple_http_manager/SimpleHttpManager.php');
    /**
     * Require Models, Controllers, and other Objects
     */
    require_once('/var/www/app/src/models/UserModel.php');
    /**
     * Enable Utilities
     */
    enable_errors();
    
    /**
     * Attempt PDO Connection to DB
     */
    $dbConfig = require('/var/www/app/config/db_config.php');
    
    if(empty($dbConfig)){
        throw new Exception('Unable to identify Database Configuration!');
    }

    /**
     * @var object|null PDO connection
     */
    $pdo = db_connect($dbConfig);
    
    /**
     * @var object User
     */
    $user = new UserModel($pdo);
    var_dump($user->createUser([
        'id' => null,
        'username' => 'newuser123',
        'email' => 'newuser@example.com',
        'password_hash' => password_hash('securepassword123', PASSWORD_DEFAULT),
        'first_name' => 'John',
        'last_name' => 'Doe',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'is_active' => 1,
        'last_login_at' => null
    ]));

?>