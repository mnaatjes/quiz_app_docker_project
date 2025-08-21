<?php
    /**
     * config/services/UserServices.php
     */
    use App\Controllers\UserController;
    use App\Repositories\UserRepository;
    use App\Services\UserService;
use mnaatjes\mvcFramework\SessionsCore\SessionManager;

    /**
     * Bind UserRepository
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \App\Repositories\UserRepository
     */
    $container->set(UserRepository::class, new UserRepository(
        // Get ORM Instance
        $container->get("orm")
    ));

    /**
     * Bind UserService
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \App\Services\UserService
     */
    $container->set(UserService::class, new UserService(
        // Bind UserRepository
        $container->get(UserRepository::class),
        $container->get(SessionManager::class)
    ));

    /**
     * Bind UserController
     *
     * @param \mnaatjes\mvcFramework\Container $container The dependency injection container.
     * @return \App\Services\UserController
     */
    $container->set(UserController::class, new UserController(
        // Bind User Service
        $container->get(UserService::class)
    ));
?>