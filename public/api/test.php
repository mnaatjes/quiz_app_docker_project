<?php

    /**
     * Require Utils and Framework
     */
    require_once('/var/www/app/utils/enable_errors.php');
    require_once('/var/www/app/simple_http_manager/SimpleHttpManager.php');

    /**
     * Debug: Response
     */
    $router = new Router();
    
    $router->get('/banana', function($req, $res){
        $res->addHeader("Content-Type", "application/json");
        $res->setBody("I Am A Banana");
        $res->send();
    });

    $router->dispatch();
?>