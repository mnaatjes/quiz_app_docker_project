<?php

    class UserResponseObject {

        public int $id;
        public string $username;
        public string $fname;
        public string $lname;
        public string $email;


        public function __construct(
            int $id,
            string $username,
            string $fname,
            string $lname,
            string $email
        ){
            $this->id = $id;
            $this->username = $username;
            $this->fname = $fname;
            $this->lname = $lname;
            $this->email = $email;
        }
    }

?>