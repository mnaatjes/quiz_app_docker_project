<?php

    /**
     * config/services/Middleware.php
     */

    use App\Middleware\UserAuth;
    use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * User Authentication Middleware
     * @param SessionManager
     */
    $container->set(UserAuth::class, new UserAuth(
        $container->get(SessionManager::class)
    ));

?>