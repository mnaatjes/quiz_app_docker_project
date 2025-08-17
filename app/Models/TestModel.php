<?php
    namespace App\Models;
    use mnaatjes\mvcFramework\MVCCore\BaseModel;

    class TestModel extends BaseModel {

        private $id;
        private $username;
        private $email;
        private $passwordHash;
        private $firstName;
        private $lastName;
        private $createdAt;
        private $updatedAt;
        private $isActive;
        private $lastLoginAt;
    }

?>