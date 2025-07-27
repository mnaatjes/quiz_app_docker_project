<?php

    /**
     * Require Utils and Framework
     */
    require_once('/var/www/app/utils/enable_errors.php');
    require_once('/var/www/app/utils/db_connect.php');
    require_once('/var/www/app/simple_http_manager/SimpleHttpManager.php');

    /**
     * Debug: Response
     */
    $router = new Router();
    
    $router->get('', function($req, $res){
        /**
         * Require Config
         */
        $dbConfig = require('/var/www/app/config/db_config.php');
        $res->addHeader("Content-Type", "application/json");
        $res->setBody(json_encode(db_connect($dbConfig, "Select MAX(PID) FROM categories"), JSON_PRETTY_PRINT));
        $res->send();
    });

    $router->dispatch();
?>