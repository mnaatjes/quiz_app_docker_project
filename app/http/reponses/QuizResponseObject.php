<?php

    //require_once('QuestionReponseObject');

    class QuizResponseObject {

        public int $id;
        public ?string $description;
        public array $questions;

        public function __construct(
            int $id,
            ?string $description,
            array $questions=[],

        ){
            $this->id           = $id;
            $this->description  = $description;
            $this->questions    = $questions;
        }
    }

?>