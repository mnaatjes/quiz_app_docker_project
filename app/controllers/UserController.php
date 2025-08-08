<?php

/**-------------------------------------------------------------------------*/
/**
 * User Controller Class
 */
/**-------------------------------------------------------------------------*/
class UserController {

    private UserRepository $userRepository;
    
    /**-------------------------------------------------------------------------*/
    /**
     * Constructor for User Controller
     * 
     * @param UserRepository $user_respository Dependency to interact with User Table in DB
     */
    /**-------------------------------------------------------------------------*/
    public function __construct(UserRepository $user_repository) {
        /**
         * Assign Dependency
         */
        $this->userRepository = $user_repository;
    }
    /**-------------------------------------------------------------------------*/
    /**
     * Displays a user's profile.
     * e.g., accessed via /user/profile?id=5
     */
    /**-------------------------------------------------------------------------*/
    public function showProfile(int $id): void {
        // 1. Controller receives input from the request
        $user = $this->userRepository->findById($id);
    }

    /**-------------------------------------------------------------------------*/
    /**
     * Create User Action
     * 
     * Handles the $req and $res for creating a user
     */
    /**-------------------------------------------------------------------------*/
    public function createUserAction($req, $res){

        /**
         * Create new User
         */
        $user = $this->userRepository->createUser($req["params"]);

        /**
         * Validate
         */
        if(is_null($user)){
            $error = ["msg" => "Failed to create user"];
        }

        /**
         * Build Reponse
         */
        var_dump([
            "msg" => "Success!",
            "user" => [
                "id"        => $user->getId(),
                "username"  => $user->getUsername(),
                "email"     => $user->getEmail()
            ]
        ]);

    }

}